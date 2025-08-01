<?php

namespace App\Http\Controllers;

use App\Models\MwContactU;
use App\Models\MwListingUser;
use App\Models\MwPlaceAnAd;
use App\Http\Resources\MwContactUResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Helper;
use ParagonIE\ConstantTime\Hex;

class MwContactUController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->input('per_page', 10);
            $sortField = $request->input('sort_by', 'id');
            $sortOrder = $request->input('sort_order', 'desc');

            $criteria = new QueryCriteria();
            $criteria->with(['mw_place_an_ad.mw_listing_user', 'mw_place_an_ad.mw_ad_images']);


            if ($user->isAgent()) {
    
                $userAdIds = MwPlaceAnAd::where('user_id', $user->user_id)
                    ->pluck('id')
                    ->toArray();
                    
                if (!empty($userAdIds)) {
                    $criteria->whereIn('ad_id', $userAdIds);
                } else {
                    $criteria->where('ad_id', '=', -1);
                }
                
            } elseif ($user->isAgency()) {
                
                $agencyId = $user->user_id;
                
                $agentIds = MwListingUser::where('parent_user', $agencyId)
                    ->where('user_type', 'A')
                    ->pluck('user_id')
                    ->toArray();
                    
                $allUserIds = array_merge([$agencyId], $agentIds);
                
                $allAdIds = \App\Models\MwPlaceAnAd::whereIn('user_id', $allUserIds)
                    ->pluck('id')
                    ->toArray();
                    
                if (!empty($allAdIds)) {
                    $criteria->whereIn('ad_id', $allAdIds);
                } else {
                    
                    $criteria->where('ad_id', '=', -1);
                }
                
            } elseif ($user->isDeveloper()) {
               
                $userAdIds = \App\Models\MwPlaceAnAd::where('user_id', $user->user_id)
                    ->pluck('id')
                    ->toArray();
                    
                if (!empty($userAdIds)) {
                    $criteria->whereIn('ad_id', $userAdIds);
                } else {
                   
                    $criteria->where('ad_id', '=', -1);
                }
            }
            
            if ($request->filled('ad_id')) {
                $criteria->where('ad_id', '=', $request->ad_id);
            }

            if ($request->filled('contact_type')) {
                $criteria->where('contact_type', '=', $request->contact_type);
            }

            if ($request->filled('is_read')) {
                $criteria->where('is_read', '=', $request->is_read);
            }

            if ($request->filled('search')) {
                $criteria->where(function($subCriteria) use ($request) {
                    $subCriteria->whereLike('name', $request->search)
                               ->orWhere(function($orCriteria) use ($request) {
                                   $orCriteria->whereLike('email', $request->search);
                               });
                });
            }

            if ($request->filled('date_from') && $request->filled('date_to')) {
                $criteria->whereBetween('date_added', [$request->date_from, $request->date_to]);
            } elseif ($request->filled('date_from')) {
                $criteria->where('date_added', '>=', $request->date_from);
            } elseif ($request->filled('date_to')) {
                $criteria->where('date_added', '<=', $request->date_to);
            }

            $criteria->orderBy($sortField, $sortOrder);

            $inquiries = $criteria->applyToEloquent(MwContactU::query())
                                 ->paginate($perPage);

            return MwContactUResource::collection($inquiries)
                ->additional([
                    'meta' => [
                        'total' => $inquiries->total(),
                        'current_page' => $inquiries->currentPage(),
                        'per_page' => $inquiries->perPage(),
                        'last_page' => $inquiries->lastPage(),
                        'user_role' => $user->getRoleName(),
                        'filters' => [
                            'ad_id' => $request->input('ad_id'),
                            'contact_type' => $request->input('contact_type'),
                            'is_read' => $request->input('is_read'),
                            'search' => $request->input('search'),
                            'date_from' => $request->input('date_from'),
                            'date_to' => $request->input('date_to'),
                            'channel' => $request->input('channel'),
                        ]
                    ]
                ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve inquiries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function show(string $id)
    {
        try {
            $criteria = new QueryCriteria();
            $criteria->where('id', '=', $id)
                    ->with(['mw_place_an_ad']);

            $inquiry = $criteria->applyToEloquent(MwContactU::query())
                               ->firstOrFail();

            if ($inquiry->is_read === '0') {
                $inquiry->update(['is_read' => '1']);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Inquiry retrieved successfully',
                'data' => new MwContactUResource($inquiry)
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Inquiry not found'
            ], 404);
        }
    }

    public function markAsRead(Request $request, string $id)
    {
        try {
            $inquiry = MwContactU::findOrFail($id);

            $inquiry->update(['is_read' => '1']);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Inquiry marked as read',
                'data' => new MwContactUResource($inquiry->load(['mw_place_an_ad']))
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to mark inquiry as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsUnread(Request $request, string $id)
    {
        try {
            $inquiry = MwContactU::findOrFail($id);

            $inquiry->update(['is_read' => '0']);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Inquiry marked as unread',
                'data' => new MwContactUResource($inquiry->load(['mw_place_an_ad']))
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to mark inquiry as unread',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkMarkAsRead(Request $request)
    {
        $request->validate([
            'inquiry_ids' => 'required|array',
            'inquiry_ids.*' => 'integer|exists:mysql_legacy.mw_contact_u,contact_id'
        ]);

        try {
            $updatedCount = MwContactU::whereIn('contact_id', $request->inquiry_ids)
                ->where('is_read', '0')
                ->update(['is_read' => '1']);

            return new JsonResponse([
                'status' => 'success',
                'message' => "Successfully marked {$updatedCount} inquiries as read",
                'data' => [
                    'updated_count' => $updatedCount,
                    'inquiry_ids' => $request->inquiry_ids
                ]
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to mark inquiries as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function EnquiryCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'meassage' => 'required|string',
            'ad_id' => 'required|exists:mysql_legacy.mw_place_an_ad,id'
        ]);

        if ($validator->fails()) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contact = MwContactU::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'meassage' => $request->meassage,
                'ad_id' => $request->ad_id,
                'contact_type' => $request->contact_type ?? '',
                'date_added' => now(),
                'date' => now(),
                'type' => 0,
                'is_read' => '0',
                'city' => '',
                'url' => '',
                'channel' => 'A'
            ]);

            // if ($request->contact_type = 'ENQUIRY') {
            //     $template_name= 'enquiry';
            // } else {
            //     $template_name= 'contact us';
            // }

            // Helper::SendEmail($request->email,$template_name,$contact);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Contact form submitted successfully',
                'data' => new MwContactUResource($contact)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to submit contact form: ' . $e->getMessage()
            ], 500);
        }
    }
}
