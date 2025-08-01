<?php

namespace App\Http\Controllers;

use App\Models\MwListingUser;
use App\Models\MwUserMainCategory;
use App\Models\MwUserLanguage;
use App\Http\Resources\MwListingUserResource;
use App\Http\Resources\MwListingUserAgencyResource;
use App\Http\Resources\MwListingUserAgentResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\password;

class MwListingUserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = MwListingUser::query()
                  ->where('isTrash', '0');

            if ($request->filled('type')) {
            switch ($request->type) {
            case 'agent':
                $query->where('user_type', 'A');
                break;
            case 'agency':
                $query->where('user_type', 'K');
                break;
            case 'developer':
                $query->where('user_type', 'D');
                break;
            // case 'landlord':
            //     $query->where('user_type', 'L');
            //     break;
            case 'visitor':
                $query->where('user_type', 'U');
                break;
            }

            if($request->type == 'agent'){
                 if ($request->filled('agency_id')) {
                    $query->where('parent_user', $request->input('agency_id'));
                 }
            }
        }

        if ($request->filled('company_name')) {
            $query->where(function($q) use ($request) {
                $q->where('company_name', 'LIKE', '%' . $request->input('company_name') . '%');
            });
        }
        if ($request->filled('address')) {
            $query->where(function($q) use ($request) {
                $q->where('address', 'LIKE', '%' . $request->input('address') . '%');
            });
        }

        $users = $query->paginate($perPage);

        if ($request->filled('type') && $request->type === 'agent') {
            return MwListingUserAgentResource::collection($users)
                ->additional([
                    'meta' => [
                        'total' => $users->total(),
                        'current_page' => $users->currentPage(),
                        'per_page' => $users->perPage(),
                        'last_page' => $users->lastPage(),
                    ]
                ]);
        } else {
            return MwListingUserAgencyResource::collection($users)
                ->additional([
                    'meta' => [
                        'total' => $users->total(),
                        'current_page' => $users->currentPage(),
                        'per_page' => $users->perPage(),
                        'last_page' => $users->lastPage(),
                    ]
                ]);
        }

    }

    public function show(string $id)
    {
        try {
            $user = MwListingUser::with([
                'mw_place_an_ads',
                'mw_user_main_categories',
                'mw_listing_users',
                'mw_listing_user',
                'mw_service',
                'mw_user_languages.mw_language'
            ])->findOrFail($id);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'User details retrieved successfully',
                'data' => new MwListingUserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $userType = $request->input('user_type');

            if (!Auth::user()->isAgency()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Access denied.',
                    'required_role' => 'agency'
                ], 403);
            }

            if (!$userType) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => ['user_type' => ['The user type field is required.']]
                ], 422);
            }

            $validator = $this->getValidatorForUserType($request, $userType, null);

            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $createData = $this->getCreateDataForUserType($request, $userType);
            $user = MwListingUser::create($createData);

            if ($userType === 'K' && $request->has('specialization')) {
                $this->updateUserSpecializations($user->user_id, $request->specialization);
            }

            if ($request->has('languages_known')) {
                $languagesKnown = $request->languages_known;
                if (!in_array(1, $languagesKnown)) {
                    array_push($languagesKnown, 1);
                }
                $this->updateUserLanguages($user->user_id, $languagesKnown);
            } else {
                $this->updateUserLanguages($user->user_id, [1]);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => $this->getUserTypeName($userType) . ' created successfully and is pending admin approval',
                'data' => new MwListingUserResource($user)
            ], 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = MwListingUser::findOrFail($id);

            $validator = $this->getValidatorForUserType($request, $user->user_type, $id);

            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if ($user->user_type === 'K' && $request->has('specialization')) {
                $this->updateUserSpecializations($id, $request->specialization);
            }

            if ($request->has('languages_known')) {
                $languagesKnown = $request->languages_known;
                if (!in_array(1, $languagesKnown)) {
                    array_push($languagesKnown, 1);
                }
                $this->updateUserLanguages($user->user_id, $languagesKnown);
            } else {
                $this->updateUserLanguages($user->user_id, [1]);
            }

            $updateData = $this->getUpdateDataForUserType($request, $user->user_type);
            $user->update($updateData);

            return new JsonResponse([
                'status' => 'success',
                'message' => $this->getUserTypeName($user->user_type) . ' updated successfully',
                'data' => new MwListingUserResource($user)
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getValidatorForUserType(Request $request, string $userType, string $id = null)
    {
        $uniqueRule = $id ? 'unique:mysql_legacy.mw_listing_users,email,' . $id . ',user_id' : 'unique:mysql_legacy.mw_listing_users,email';

        $isUpdate = !is_null($id);

        $baseRules = [
            'first_name' => ($isUpdate ? 'nullable' : 'required') . '|string|max:150',
            'last_name' => ($isUpdate ? 'nullable' : 'required') . '|string|max:150',
            'email' => ($isUpdate ? 'nullable' : 'required') . '|email|max:150|' . $uniqueRule,
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string|max:500',
            'password' => ($isUpdate ? 'nullable' : 'required') . '|string|min:8|max:255',
            'password_confirm' => ($isUpdate ? 'nullable' : 'required') . '|required_with:password|same:password',
            'user_type' => 'required|in:K,A,D,U',
        ];

        $typeSpecificRules = match($userType) {
            'K' => $this->getAgencyValidationRules($id),
            'A' => $this->getAgentValidationRules(),
            'D' => $this->getDeveloperValidationRules(),
            'U' => [],
            default => []
        };

        return Validator::make($request->all(), array_merge($baseRules, $typeSpecificRules));
    }

    private function getAgencyValidationRules(string $id = null): array
    {
        $uniqueRule = $id ? 'unique:mysql_legacy.mw_listing_users,company_email,' . $id . ',user_id' : 'unique:mysql_legacy.mw_listing_users,company_email';

        return [
            'rera_number' => 'nullable|string|max:50',
            'rera_date_issued' => 'nullable|date',
            'rera_document' => 'nullable|string|max:255',
            'arra_number' => 'required|string|max:150',
            'arra_expiry_date' => 'nullable|date',
            'arra_document' => 'nullable|string|max:255',
            'trade_license_number' => 'required|string|max:70',
            'trade_license_date_issued' => 'required|date',
            'trade_license_document' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:50',
            'vat_expiry_date' => 'nullable|date',
            'vat_registration_certificate' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:250',
            'no_of_employees' => 'required|integer|min:1',
            'company_logo' => 'nullable|string|max:255',
            'company_email' => 'required|email|max:250|' . $uniqueRule,
            'whatsapp' => 'required|string|max:16',
            'website' => 'nullable|url|max:250',
            'facebook' => 'nullable|url|max:250',
            'instagram' => 'nullable|url|max:250',
            'twitter' => 'nullable|url|max:250',
            'youtube' => 'nullable|url|max:250',
            'specialization' => 'required|array',
            'specialization.*' => 'integer|exists:mysql_legacy.mw_category,category_id',
            'description' => 'nullable|string',
        ];
    }

    private function getAgentValidationRules(): array
    {
        return [
            'agency_id' => 'required|integer',
            'arra_number' => 'required|string|max:150',
            'arra_expiry_date' => 'nullable|date',
            'arra_document' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:15',
            'whatsapp' => 'required|string|max:16',
            'languages_known' => 'required|array',
            'description' => 'required|string|max:2000',
            'licence_no' => 'required|string|max:50',
            'country_id' => 'required|integer',
            'gender' => 'required|in:M,F',
            'user_status' => 'required|in:A,I',
            'service_id' => 'nullable|integer',
            'image' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
        ];
    }

    private function getDeveloperValidationRules(): array
    {
        return [
            'company_name' => 'required|string|max:250',
            'company_logo' => 'nullable|string|max:255',
            'licence_no' => 'required|string|max:50',
            'state_id' => 'required|integer',
            'website' => 'nullable|url|max:250',
            'dob' => 'nullable|date',
            'languages_known' => 'nullable|array',
            'xml_image' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'slug' => 'required|string|max:255',
        ];
    }

    private function getCreateDataForUserType(Request $request, string $userType): array
    {
        $baseData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $request->profile_image ?? '',
            'user_type' => $userType,
            'parent_user' => Auth::user()->user_id,
            'status' => 'W',
            'isTrash' => 0,
            'email_verified' => '0',
            'admin_approved' => '0',
            'verified' => '0',
            'documents_submitted' => '0',
            'filled_info' => '1',
            'date_added' => now(),
            'send_me' => 'N',
            'xml_inserted' => '0',
            'featured' => 'N',
            'total_reviews' => '0',
            'zip' => ' ',
            'fax' => ' ',
            'verification_code' => ' ',
            'reset_key' => ' ',
            'education_level' => '0',
            'position_level' => '0',
            'updates' => ' ',
            'advertisement' => ' ',
            'cover_letter' => ' ',
            'xml_image' => ' ',
            'country' => 1,
            'state' => 1,
            'city' => 1,
            'avg_r' => 0.00
        ];

        $typeSpecificData = match($userType) {
            'K' => $this->getAgencyCreateData($request),
            'A' => $this->getAgentCreateData($request),
            'D' => $this->getDeveloperCreateData($request),
            'U' => [],
            default => []
        };

        return array_merge($baseData, $typeSpecificData);
    }

    private function getAgencyCreateData(Request $request): array
    {
        $socialUrls = json_encode([
            'facebook' => $request->facebook ?? '',
            'instagram' => $request->instagram ?? '',
            'twitter' => $request->twitter ?? '',
            'youtube' => $request->youtube ?? ''
        ]);

        return [
            'company_name' => $request->company_name,
            'company_logo' => $request->company_logo,
            'company_email' => $request->company_email,
            'whatsapp' => $request->whatsapp,
            'website' => $request->website,
            'description' => $request->description,
            'country' => $request->country_id,
            'state' => $request->state_id,
            'contact_person' => $request->contact_person,
            'licence_no' => $request->arra_number,
            'licence_no_expiry' => $request->arra_expiry_date,
            'upload_arra_id' => $request->arra_document,
            'trade_license_number' => $request->trade_license_number,
            'trade_license_expiry' => $request->trade_license_date_issued,
            'xml_image' => $request->trade_license_document,
            'eid_number' => $request->vat_number,
            'eid_expiry_date' => $request->vat_expiry_date,
            'upload_eid' => $request->vat_registration_certificate,
            'no_of_employees' => $request->no_of_employees,
            'social_urls' => $socialUrls,
            'facebook' => $request->facebook,
            'twiter' => $request->twitter,
        ];
    }

    private function getAgentCreateData(Request $request): array
    {

         $socialUrls = json_encode([
            'facebook' => $request->facebook ?? '',
            'instagram' => $request->instagram ?? '',
            'twitter' => $request->twitter ?? '',
        ]);

        return [
            'parent_user' => $request->agency_id,
            'mobile' => $request->mobile,
            'whatsapp' => $request->whatsapp,
            'description' => $request->description,
            'licence_no' => $request->licence_no,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'calls_me' => $request->gender,
            'user_status' => $request->user_status,
            'designation_id' => $request->service_id,
            'arra_number' => $request->arra_number,
            'arra_doc' => $request->arra_document,
            'arra_date' => $request->arra_expiry_date,
            'eid_number' => $request->eid_number,
            'upload_eid' => $request->eid_document,
            'eid_expiry_date' => $request->eid_expiry_date,
            'dob' => $request->dob,
            'social_urls' => $socialUrls,
            'facebook' => $request->facebook,
            'twiter' => $request->twitter,
        ];
    }

    private function getDeveloperCreateData(Request $request): array
    {
        return [
            'company_name' => $request->company_name,
            'company_logo' => $request->company_logo,
            'licence_no' => $request->licence_no,
            'state_id' => $request->state_id,
            'website' => $request->website,
            'dob' => $request->dob,
            'description' => $request->description,
            'slug' => $request->slug,
        ];
    }



    ///////////////////////////// Update user data based  on user type /////////////////////////////////////

    private function getUpdateDataForUserType(Request $request, string $userType): array
    {
        $baseData = [
            'last_updated' => now()
        ];


        if ($request->has('first_name')) {
            $baseData['first_name'] = $request->first_name;
        }
        if ($request->has('last_name')) {
            $baseData['last_name'] = $request->last_name;
        }
        if ($request->has('email')) {
            $baseData['email'] = $request->email;
        }
        if ($request->has('phone')) {
            $baseData['phone'] = $request->phone;
        }
        if ($request->has('address')) {
            $baseData['address'] = $request->address;
        }
        if ($request->has('profile_image')) {
            $baseData['image'] = $request->profile_image ?? '';
        }

        // $specialization = $this->updateUserSpecializations($request->id ,$request->specialization) ?? [];

        $typeSpecificData = match($userType) {
            'K' => $this->getAgencyUpdateData($request),
            'A' => $this->getAgentUpdateData($request),
            'D' => $this->getDeveloperUpdateData($request),
            'U' => [],
            default => []
        };

        return array_merge($baseData, $typeSpecificData);
    }

    private function getAgencyUpdateData(Request $request): array
    {
        $socialUrls = json_encode([
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'youtube' => $request->youtube
        ]);

        return [
            'company_name' => $request->company_name,
            'company_logo' => $request->company_logo,
            'company_email' => $request->company_email,
            'whatsapp' => $request->whatsapp,
            'website' => $request->website,
            'description' => $request->description,
            'contact_person' => $request->contact_person,
            'licence_no' => $request->arra_number,
            'licence_no_expiry' => $request->arra_expiry_date,
            'upload_arra_id' => $request->arra_document,
            'trade_license_number' => $request->trade_license_number,
            'trade_license_expiry' => $request->trade_license_date_issued,
            'xml_image' => $request->trade_license_document,
            'eid_number' => $request->vat_number,
            'eid_expiry_date' => $request->vat_expiry_date,
            'upload_eid' => $request->vat_registration_certificate,
            'no_of_employees' => $request->no_of_employees,
            'specialization' => $request->specialization ?? [],
            'social_urls' => $socialUrls,
            'facebook' => $request->facebook,
            'twiter' => $request->twitter,
        ];
    }

    private function getAgentUpdateData(Request $request): array
    {
        return [
            'mobile' => $request->mobile,
            'whatsapp' => $request->whatsapp,
            'description' => $request->description,
            'licence_no' => $request->licence_no,
            'country_id' => $request->country_id,
            'calls_me' => $request->gender,
            'user_status' => $request->user_status,
            'designation_id' => $request->service_id,
            'arra_number' => $request->arra_number,
            'arra_doc' => $request->arra_document,
            'arra_date' => $request->arra_expiry_date,
            'eid_number' => $request->eid_number,
            'upload_eid' => $request->eid_document,
            'eid_expiry_date' => $request->eid_expiry_date,
            'dob' => $request->dob,
        ];
    }

    private function getDeveloperUpdateData(Request $request): array
    {
        return [
            'company_name' => $request->company_name,
            'company_logo' => $request->company_logo,
            'licence_no' => $request->licence_no,
            'state_id' => $request->state_id,
            'website' => $request->website,
            'dob' => $request->dob,
            'description' => $request->description,
            'slug' => $request->slug,
        ];
    }

    private function updateUserSpecializations(int $userId, array $categoryIds): void
    {
        MwUserMainCategory::where('user_id', $userId)->delete();

        if (!empty($categoryIds)) {
            $insertData = [];
            foreach ($categoryIds as $categoryId) {
                $insertData[] = [
                    'user_id' => $userId,
                    'category_id' => $categoryId,
                ];
            }
            MwUserMainCategory::insert($insertData);
        }
    }

    private function updateUserLanguages(int $userId, array $languageIds): void
    {
        MwUserLanguage::where('user_id', $userId)->delete();

        if (!empty($languageIds)) {
            $insertData = [];
            foreach ($languageIds as $languageId) {
                $insertData[] = [
                    'user_id' => $userId,
                    'language_id' => $languageId,
                ];
            }
            MwUserLanguage::insert($insertData);
        }
    }

    private function getUserTypeName(string $userType): string
    {
        return match($userType) {
            'K' => 'Agency',
            'A' => 'Agent',
            'D' => 'Developer',
            'U' => 'User',
            default => 'User'
        };
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => ['required', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
                'new_password_confirmation' => 'required|string|same:new_password'
            ]);

            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $listingUser = MwListingUser::where('user_id', $user->user_id)->first();

            if (!$listingUser) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'User profile not found'
                ], 404);
            }


            if (!Hash::check($request->current_password, $listingUser->password)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Current password is incorrect',
                    'errors' => [
                        'current_password' => ['The current password is incorrect']
                    ]
                ], 422);
            }


            $listingUser->update([
                'password' => Hash::make($request->new_password),
                'last_updated' => now()
            ]);

            $user->tokens()->delete();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Password changed successfully'
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to change password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
