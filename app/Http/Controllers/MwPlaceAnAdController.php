<?php

namespace App\Http\Controllers;

use App\Models\MwPlaceAnAd;
use App\Models\MwListingTypeField;
use App\Models\MwListingUser;
use App\Models\MwUserPackage;
use App\Models\MwAdImage;
use App\Models\MwAdAmenity;
use App\Models\MwAdFaq;
use App\Models\MwAdFloorPlan;
use App\Models\MwAdNearestSchool;
use App\Models\MwAdPropertyType;
use App\Http\Resources\MwListPlaceAnAdResource;
use App\Http\Resources\MwPlaceAnAdResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MwPlaceAnAdController extends Controller
{

       public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = MwPlaceAnAd::query()
            ->where('isTrash', '0')
            ->with([
                'mw_section',
                'mw_city',
                'mw_developer',
                'mw_district',
                'mw_listing_user',
                'mw_country',
                'mw_state',
                'mw_ad_images',
            ]);

        if ($request->filled('type')) {

            if ($request->type === 'sold') {
                $query->where('s_r', 1);
            } else {
                $query->where('section_id', match($request->type) {
                    'buy' => 1,
                    'rent' => 2,
                    'new_development' => 3,
                    default => 1
                });
            }

            if ($request->filled('category_type')) {
                $listingType = match($request->category_type) {
                    'residential' => 118,
                    'commercial' => 120,
                    default => null
                };

                if ($listingType) {
                    $categoryIds = MwListingTypeField::where('listing_type', $listingType)
                        ->pluck('category_id')
                        ->toArray();

                    if (!empty($categoryIds)) {
                        $query->whereIn('category_id', $categoryIds);
                    }
                }
            }
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        } elseif ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        } elseif ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', $request->bathrooms);
        }

        if ($request->filled('min_area') && $request->filled('max_area')) {
            $query->whereBetween('builtup_area', [$request->min_area, $request->max_area]);
        } elseif ($request->filled('min_area')) {
            $query->where('builtup_area', '>=', $request->min_area);
        } elseif ($request->filled('max_area')) {
            $query->where('builtup_area', '<=', $request->max_area);
        }

        if ($request->filled('min_plot_area') && $request->filled('max_plot_area')) {
            $query->whereBetween('plot_area', [$request->min_plot_area, $request->max_plot_area]);
        } elseif ($request->filled('min_plot_area')) {
            $query->where('plot_area', '>=', $request->min_plot_area);
        } elseif ($request->filled('max_plot_area')) {
            $query->where('plot_area', '<=', $request->max_plot_area);
        }

        if ($request->filled('search')) {
            $searchTerms = explode(" ", trim($request->search));
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('ad_title', 'LIKE', '%'.$term.'%')
                             ->orWhere('ad_description', 'LIKE', '%'.$term.'%');
                    });
                }
            });
        }

        $allowedSortFields = ['id', 'price', 'date_added', 'bedrooms', 'bathrooms', 'builtup_area'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $ads = $query->paginate($perPage);

        return MwListPlaceAnAdResource::collection($ads)
            ->additional([
                'meta' => [
                    'total' => $ads->total(),
                    'current_page' => $ads->currentPage(),
                    'per_page' => $ads->perPage(),
                    'last_page' => $ads->lastPage(),
                ]
            ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $user_id = $user->user_id;

            $packageUserId = $user->isAgent() ? $user->parent_user : $user_id;

            $packageCheck = $this->checkUserPackage($packageUserId);
            if ($packageCheck !== true) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => $packageCheck,
                ], 403);
            }

            $validator = $this->getValidationRules($request);
            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $adData = $this->prepareAdData($request);
            $ad = MwPlaceAnAd::create($adData);

            $this->handleRelatedData($ad, $request);
            $this->updatePackageUsage($user_id);

            DB::commit();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Property created successfully',
                'data' => new MwPlaceAnAdResource($ad->load([
                    'mw_section', 'mw_city', 'mw_category', 'mw_developer',
                    'mw_subcategory', 'mw_district', 'mw_listing_user', 'mw_country',
                    'mw_state', 'mw_community', 'mw_sub_community', 'mw_ad_amenity',
                    'mw_ad_faqs', 'mw_ad_floor_plans', 'mw_ad_images', 'mw_ad_nearest_schools',
                    'mw_ad_property_types', 'mw_ad_favourites',
                ]))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to create property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $ad = MwPlaceAnAd::where('id', $id)
                ->with([
                    'mw_section', 'mw_city', 'mw_category', 'mw_developer',
                    'mw_subcategory', 'mw_district', 'mw_listing_user', 'mw_country',
                    'mw_state', 'mw_community', 'mw_sub_community', 'mw_ad_amenity',
                    'mw_ad_faqs', 'mw_ad_floor_plans', 'mw_ad_images', 'mw_ad_nearest_schools',
                    'mw_ad_property_types', 'mw_ad_favourites'
                ])
                ->firstOrFail();

            return new JsonResponse([
                "status" => "success",
                "message" => "Property details successfully retrieved",
                "data" => new MwPlaceAnAdResource($ad)
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Property not found or error occurred",
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $ad = MwPlaceAnAd::findOrFail($id);
            $user_id = Auth::user()->user_id;
            
            if ($ad->user_id != $user_id && !$this->hasAdminPermission($request)) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Unauthorized to update this property',
                ], 403);
            }

            
            $validator = $this->getValidationRules($request, true);
            if ($validator->fails()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $adData = $this->prepareAdData($request, true);
            $ad->update($adData);

            $this->handleRelatedData($ad, $request);

            DB::commit();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Property updated successfully',
                'data' => new MwPlaceAnAdResource($ad->load([
                    'mw_section', 'mw_city', 'mw_category', 'mw_developer',
                    'mw_subcategory', 'mw_district', 'mw_listing_user', 'mw_country',
                    'mw_state', 'mw_community', 'mw_sub_community', 'mw_ad_amenity',
                    'mw_ad_faqs', 'mw_ad_floor_plans', 'mw_ad_images', 'mw_ad_nearest_schools',
                    'mw_ad_property_types', 'mw_ad_favourites',
                ]))
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to update property',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $ad = MwPlaceAnAd::findOrFail($id);
            $user_id = Auth::user()->user_id;

            if ($ad->user_id != $user_id && !$this->hasAdminPermission(request())) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete this property',
                ], 403);
            }

            DB::beginTransaction();

            $ad->update([
                'isTrash' => '1',
                'last_updated' => Carbon::now()
            ]);

            DB::commit();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Property deleted successfully',
                'data' => [
                    'id' => $ad->id,
                    'ad_title' => $ad->ad_title,
                    'deleted_at' => Carbon::now()->toDateTimeString()
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Property not found',
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to delete property',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function agencyAndAgentAds(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortField = $request->input('sort_by', 'id');
            $sortOrder = $request->input('sort_order', 'desc');
            
            if (!$request->filled('agency_id') && !$request->filled('agent_id')) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Either Agency ID or Agent ID is required'
                ], 400);
            }
            
            $statusMap = [
                'published' => 'A',
                'inactive' => 'I',
                'waiting' => 'W',
                'rejected' => 'R',
                'draft' => 'D',
            ];
            
            $criteria = new QueryCriteria();
            $criteria->where('isTrash', '0');
            
            // Agency or Agent filtering
            if ($request->filled('agency_id')) {
                $agencyId = $request->agency_id;
                $agentIds = MwListingUser::where('parent_user', $agencyId)
                    ->where('user_type', 'A')
                    ->pluck('user_id')
                    ->toArray();
                    
                $allUserIds = array_merge([$agencyId], $agentIds);
                $criteria->whereIn('user_id', $allUserIds);
                
            } elseif ($request->filled('agent_id')) {
                $criteria->where('user_id', '=', $request->agent_id);
            }
            
            // Type filtering
            if ($request->filled('type')) {
                if ($request->type === 'sold') {
                    $criteria->where('s_r', 1);
                } else {
                    $criteria->where('section_id', match($request->type) {
                        'buy' => 1,
                        'rent' => 2,
                        'new_development' => 3,
                        default => 1
                    });
                }
                
                if ($request->filled('category_type')) {
                    $listingType = match($request->category_type) {
                        'residential' => 118,
                        'commercial' => 120,
                        default => null
                    };
                    if ($listingType) {
                        $categoryIds = MwListingTypeField::where('listing_type', $listingType)
                            ->pluck('category_id')
                            ->toArray();
                        if (!empty($categoryIds)) {
                            $criteria->whereIn('category_id', $categoryIds);
                        }
                    }
                }
            }
            
            // Status filtering
            if ($request->filled('status')) {
                if (!array_key_exists($request->status, $statusMap)) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Invalid status parameter. Valid options: published, inactive, waiting, rejected, draft'
                    ], 400);
                }
                $criteria->where('status', '=', $statusMap[$request->status]);
            }
            
            // Category filtering
            if ($request->filled('category_id')) {
                $criteria->where('category_id', '=', $request->category_id);
            }
            
            // Ads type filtering
            if ($request->filled('ads_type')) {
                $adsType = $request->ads_type;
                switch ($adsType) {
                    case 'promoted':
                        $criteria->where('promoted', '=', '1');
                        break;
                    case 'featured':
                        $criteria->where('featured', '=', 'Y');
                        break;
                    case 'hot':
                        $criteria->where('hot2', '=', 'Y');
                        break;
                    case 'premium':
                        $criteria->where('is_new', '1');
                        break;
                }
            }
            
            // City filtering
            if ($request->filled('city_id')) {
                $criteria->where('city', '=', $request->city_id);
            }
            
            // Bedrooms filtering
            if ($request->filled('bedrooms')) {
                $criteria->where('bedrooms', '=', $request->bedrooms);
            }
            
           
            if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            
            
            if (preg_match('/\d+/', $searchTerm, $matches)) {
                $numericId = (int) $matches[0];
                $criteria->where('id', '=', $numericId);
            } else {
                $criteria->whereLike('ad_title', $searchTerm);
            }
          }
            
            $criteria->orderBy($sortField, $sortOrder);
            
            $ads = $criteria->applyToEloquent(MwPlaceAnAd::query()
                ->with([
                    'mw_section',
                    'mw_city',
                    'mw_category',
                    'mw_developer',
                    'mw_district',
                    'mw_listing_user',
                    'mw_country',
                    'mw_state',
                    'mw_ad_images',
                ]))
                ->paginate($perPage);
            
            // FIXED: Check if no records found
            if ($ads->isEmpty()) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'No records found matching the criteria',
                    'data' => [],
                    'meta' => [
                        'total' => 0,
                        'current_page' => 1,
                        'per_page' => $perPage,
                        'last_page' => 1,
                    ]
                ], 404);
            }
            
            return MwListPlaceAnAdResource::collection($ads)
                ->additional([
                    'status' => 'success',
                    'meta' => [
                        'total' => $ads->total(),
                        'current_page' => $ads->currentPage(),
                        'per_page' => $ads->perPage(),
                        'last_page' => $ads->lastPage(),
                    ]
                ]);
                
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve agency ads',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateReferenceNumber()
    {
        $maxId = MwPlaceAnAd::max('id') ?? 0;
        $newId = $maxId + 1;
        $refNo = 'AP-' . str_pad($newId, 5, "0", STR_PAD_LEFT);
        
        return $refNo;
    }

    private function generateUid(): string
    {
        $unique = rand(100000000, 999999999);

        $exists = MwPlaceAnAd::where('ad_uid', $unique)->exists();
        
        if ($exists) {
            return $this->generateUid();
        }
        
        return (string) $unique;
    }

    private function checkUserPackage($userId)
    {
        $activePackage = MwUserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->where('latest', '1')
            ->where('category_id', '1')
            ->where(function($query) {
                $query->whereNull('deleted_status')
                      ->orWhere('deleted_status', '0');
            })
            ->whereRaw('DATE_ADD(date_added, INTERVAL validity DAY) >= CURDATE()')
            ->first();

        if (!$activePackage) {
            return 'No active package found. Please purchase a package to create ads.';
        }

        if ($activePackage->used_ad >= $activePackage->ads_allowed) {
            return 'Ad limit reached for your current package. Please upgrade or purchase additional ads.';
        }

        return true;
    }


    private function getValidationRules(Request $request, $isUpdate = false)
    {
        $baseRules = [
            'section_id' => 'required|integer|in:1,2,3',
            'ad_title' => 'required|string|max:255',
            'ad_description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'mobile_number' => 'required|string|max:20',
            'country' => 'required|integer|exists:mysql_legacy.mw_countries,country_id',
            'state' => 'required|integer|exists:mysql_legacy.mw_states,state_id',
            'city' => 'required|integer|exists:mysql_legacy.mw_city,city_id',
            
            'ask_for_price' => 'nullable|in:1,0',
            'sub_category_id' => 'nullable|integer|exists:mysql_legacy.mw_subcategory,sub_category_id',
            'district' => 'nullable|integer|exists:mysql_legacy.mw_district,district_id',
            'community_id' => 'nullable|integer',
            'sub_community_id' => 'nullable|integer',
            'developer_id' => 'nullable|integer',
            'location_latitude' => 'nullable|numeric',
            'location_longitude' => 'nullable|numeric',
            'area_location' => 'nullable|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'property_name' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'salesman_email' => 'nullable|email|max:255',
            'ad_title_ar' => 'nullable|string|max:255',
            'ad_description_ar' => 'nullable|string|max:2000',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'construction_status' => 'nullable|in:R,N',
            'transaction_type' => 'nullable|in:R,N',
            
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'car_parking' => 'nullable|integer|min:0',
            'balconies' => 'nullable|integer|min:0',
            'total_floor' => 'nullable|integer|min:0',
            'builtup_area' => 'nullable|numeric|min:0',
            'plot_area' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'furnished' => 'nullable|in:Y,N',
            'maid_room' => 'nullable|in:Y,N',
            'parking' => 'nullable|in:Y,N',
            'FloorNo' => 'nullable|string|max:100',
            'PrimaryUnitView' => 'nullable|string|max:255',
            
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:mysql_legacy.mw_amenities,amenities_id',
            'images' => 'nullable|array',
            'images.*' => 'string|max:500',
            'floor_plans' => 'nullable|array',
            'floor_plans.*.title' => 'required_with:floor_plans|string|max:255',
            'floor_plans.*.file' => 'nullable|string|max:500',
        ];
        
        $sectionRules = [];
        
        if ($request->section_id == 1) {
            $sectionRules = [
                'category_id' => 'required|integer|exists:mysql_legacy.mw_category,category_id',
                'listing_type' => 'required|integer',
            ];
        }

        elseif ($request->section_id == 2) {
            $sectionRules = [
                'category_id' => 'required|integer|exists:mysql_legacy.mw_category,category_id',
                'listing_type' => 'required|integer',
                'rent_paid' => 'required|string|max:10',
                'available_on' => 'required|date',
            ];
        }
        
        elseif ($request->section_id == 3) {
            $sectionRules = [ 
                'completion_year' => 'nullable|integer',
                'sold_or_rented' => 'nullable|date',
                'payment_plan' => 'nullable|string',
                'payment_pdf' => 'nullable|string|max:500',
                'faqs' => 'nullable|array',
                'faqs.*.title' => 'required_with:faqs|string|max:255',
                'faqs.*.file' => 'nullable|string|max:500',
                'nearest_schools' => 'nullable|array',
                'nearest_schools.*.name' => 'required_with:nearest_schools|string|max:255',
                'nearest_schools.*.distance' => 'nullable|numeric|min:0',
                'property_types' => 'nullable|array',
                'property_types.*.type_id' => 'required_with:property_types|integer|exists:mysql_legacy.mw_category,category_id',
                'property_types.*.bed' => 'nullable|integer|min:0',
                'property_types.*.bath' => 'nullable|integer|min:0',
                'property_types.*.title' => 'nullable|string|max:255',
                'property_types.*.from_price' => 'nullable|numeric|min:0',
                'property_types.*.to_price' => 'nullable|numeric|min:0',
                'property_types.*.size' => 'nullable|numeric|min:0',
                'property_types.*.size_to' => 'nullable|numeric|min:0',
            ];
        }
        
        $rules = array_merge($baseRules, $sectionRules);
        
        if ($isUpdate) {
            $rules['section_id'] = 'nullable|integer|in:1,2,3';
            $rules['ad_title'] = 'nullable|string|max:255';
            $rules['ad_description'] = 'nullable|string|max:2000';
            $rules['price'] = 'nullable|numeric|min:0';
        }

        return Validator::make($request->all(), $rules);
    }

    private function prepareAdData(Request $request, $isUpdate = false)
    {
        
        $data = [
            'section_id' => $request->section_id,  
            'ad_title' => $request->ad_title,
            'slug' => $request->ad_title,
            'ad_description' => $request->ad_description,
            'price' => $request->price,
            'mobile_number' => $request->mobile_number,
            'user_id' =>  Auth::user()->user_id,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'p_o_r' => $request->ask_for_price,
            'RefNo' => $this->generateReferenceNumber(),
            'ad_uid' => $this->generateUid(),
        ];

        switch ($request->section_id) {
            case 1: // Buy
                $data['category_id'] = $request->category_id;
                $data['listing_type'] = $request->listing_type;
                $data['bathrooms'] = $request->bathrooms;
                $data['bedrooms'] = $request->bedrooms;
                $data['transaction_type'] = $request->transaction_type;
                $data['construction_status'] = $request->construction_status;
                break;
                
            case 2: // Rent
                $data['category_id'] = $request->category_id;
                $data['listing_type'] = $request->listing_type;
                $data['bathrooms'] = $request->bathrooms;
                $data['bedrooms'] = $request->bedrooms;
                $data['transaction_type'] = $request->transaction_type;
                $data['construction_status'] = $request->construction_status;
                $data['rent_paid'] = $request->rent_paid;
                $data['expiry_date'] = $request->available_on;
                break;
                
            case 3: // New Development
                $data['listing_type'] = 122;
                $data['category_id'] = 122;
                $data['developer_id'] = $request->developer_id;
                $data['c1'] = $request->completion_year ?? null;
                $data['s_date'] = $request->sold_or_rented ?? null;
                $data['construction_status'] = 'N'; 
                $data['transaction_type'] = 'N';
                $data['payment_plan'] = $request->payment_plan;
                $data['payment_pdf'] = $request->payment_pdf;
                break;
        }

        $commonOptionalFields = [
            'sub_category_id', 'district', 'community_id', 'sub_community_id',
            'property_name', 'contact_name', 'contact_email', 'salesman_email',
            'ad_title_ar', 'ad_description_ar', 'meta_title', 'meta_keywords', 
            'meta_description', 'youtube_url', 'FloorNo', 'PrimaryUnitView'
        ];

        // Property-specific fields (for sections 1 & 2 mainly)
        $propertyFields = [
            'car_parking', 'balconies', 'total_floor', 'builtup_area', 'plot_area',
            'year_built', 'furnished', 'maid_room', 'parking', 'location_latitude', 'location_longitude', 
            'area_location', 'street_address'
        ];

        foreach ($commonOptionalFields as $field) {
            if ($request->filled($field)) {
                $data[$field] = $request->$field;
            }
        }

        // Add property fields (mainly for sections 1 & 2)
        if (in_array($request->section_id, [1, 2])) {
            foreach ($propertyFields as $field) {
                if ($request->filled($field)) {
                    $data[$field] = $request->$field;
                }
            }
        }

        if (!$isUpdate) {
            $data['date_added'] = Carbon::now();
            $data['status'] = 'A';
            $data['isTrash'] = '0';
            $data['featured'] = 'N';
            $data['promoted'] = '0';
            $data['recmnded'] = '0';
            $data['model'] = '0';
            $data['image'] = ' ';
            $data['code'] = ' ';
            $data['mandate'] = ' ';
            $data['currency_abr'] = ' ';
            $data['area_measurement'] = 0;
            $data['RetUnitCategory'] = 0;
            $data['RecommendedProperties'] = ' ';
            $data['hot2'] = 'N';
            $data['s_r'] = '0';
            $data['is_new'] = '0';
            $data['deleted'] = '0';
            $data['PropertyID'] = 0;
            $data['ReraStrNo'] = '';
            $data['Rent'] = 0.00;
            $data['RentPerMonth'] = 0.00;
            $data['occupant_status'] = '';
            $data['nearest_metro'] = '';
            $data['nearest_railway'] = '';

            if ($request->section_id == 1) {
                $data['rent_paid'] = ' ';
            }   

            if ($request->section_id == 3) {
                $data['completion_year'] = 0;
                $data['sold_or_rented'] = ' ';
                $data['car_parking'] = 0;
                $data['balconies'] = 0;
                $data['bathrooms'] = 0;
                $data['bedrooms'] = 0;
                $data['FloorNo'] = 0;
                $data['rent_paid'] = ' ';
                $data['total_floor'] = 0;
                $data['builtup_area'] = 0;
                $data['plot_area'] = 0;
                $data['year_built'] = 0;
                $data['furnished'] = 'N';
                $data['maid_room'] = 'N';
                $data['parking'] = 'N';
                $data['location_latitude'] = 0;
                $data['location_longitude'] = 0;
                $data['area_location'] = ' ';
                $data['street_address'] = ' ';
            }
        }

        $data['last_updated'] = Carbon::now();

        return $data;
    }


    private function handleRelatedData(MwPlaceAnAd $ad, Request $request)
    {
        // Handle amenities
        if ($request->has('amenities')) {
            MwAdAmenity::where('ad_id', $ad->id)->delete();
            foreach ($request->amenities as $amenityId) {
                MwAdAmenity::create([
                    'ad_id' => $ad->id,
                    'amenities_id' => $amenityId
                ]);
            }
        }

        // Handle images
        if ($request->has('images')) {
            MwAdImage::where('ad_id', $ad->id)->update(['isTrash' => '1']);
            foreach ($request->images as $index => $image) {
                MwAdImage::create([
                    'ad_id' => $ad->id,
                    'image_name' => $image,
                    'isTrash' => '0',
                    'status' => 'A',
                    'priority' => $index + 1,
                    'xml_image' => ' ',
                    'image_type' => ' ',
                    'Title' => ' ',
                    'IsMarketingImage' => ' ',
                    'ImageRemarks' => ' '
                ]);
            }
        }

        // Handle floor plans
        if ($request->has('floor_plans')) {
            MwAdFloorPlan::where('ad_id', $ad->id)->delete();
            foreach ($request->floor_plans as $floorPlan) {
                MwAdFloorPlan::create([
                    'ad_id' => $ad->id,
                    'floor_title' => $floorPlan['title'],
                    'floor_file' => $floorPlan['file'] ?? null
                ]);
            }
        }

        // Handle FAQs (mainly for section 3 - new developments)
        if ($request->has('faqs')) {
            MwAdFaq::where('ad_id', $ad->id)->delete();
            foreach ($request->faqs as $faq) {
                MwAdFaq::create([
                    'ad_id' => $ad->id,
                    'title' => $faq['title'],
                    'file' => $faq['file'] ?? null,
                    'last_updated' => Carbon::now()
                ]);
            }
        }

        // Handle nearest schools (mainly for section 3 - new developments)
        if ($request->has('nearest_schools')) {
            MwAdNearestSchool::where('ad_id', $ad->id)->delete();
            foreach ($request->nearest_schools as $school) {
                MwAdNearestSchool::create([
                    'ad_id' => $ad->id,
                    'name' => $school['name'],
                    'distance' => $school['distance'],
                    'vicinity' => $school['vicinity'],
                    'rating' => $school['rating'],
                    'user_ratings_total' => $school['user_ratings_total'],
                    'status' => $school['status'],
                    'f_type' => $school['f_type'],
                ]);
            }
        }

        // Handle property types (mainly for section 3 - new developments)
        if ($request->has('property_types')) {
            MwAdPropertyType::where('ad_id', $ad->id)->delete();
            foreach ($request->property_types as $propertyType) {
                MwAdPropertyType::create([
                    'ad_id' => $ad->id,
                    'type_id' => $propertyType['type_id'],
                    'bed' => $propertyType['bed'],
                    'bath' => $propertyType['bath'],
                    'title' => $propertyType['title'],
                    'from_price' => $propertyType['from_price'],
                    'to_price' => $propertyType['to_price'],
                    'size' => $propertyType['size'],
                    'size_to' => $propertyType['size_to'],
                    'last_updated' => Carbon::now(),
                ]);
            }
        }
    }

    private function updatePackageUsage($userId)
    {
        $activePackage = MwUserPackage::where('user_id', $userId)
            ->where('status', 'active')
            ->where('latest', '1')
            ->where('category_id', '1')
            ->where(function($query) {
                $query->whereNull('deleted_status')
                    ->orWhere('deleted_status', '0');
            })
            ->whereRaw('DATE_ADD(date_added, INTERVAL validity DAY) >= CURDATE()')
            ->first();

        if (!$activePackage) {
            $usedFreeAds = MwPlaceAnAd::where('user_id', $userId)->count();
            if ($usedFreeAds >= 3) {
                return 'Free ad limit (3) reached. Please purchase a package to create more ads.';
            }
            return true;
        }

        if ($activePackage->used_ad >= $activePackage->ads_allowed) {
            return 'Ad limit reached for your current package. Please upgrade or purchase additional ads.';
        }

        $activePackage->increment('used_ad');
        return true;
    }

  
    private function hasAdminPermission(Request $request)
    {
        // This would typically check if the user has admin role
        // For now, return false - implement based on your auth system
        return false;
    }
}
