<?php

namespace App\Http\Controllers;

use App\Models\MwAgentReview;
use App\Http\Resources\MwAgentReviewResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MwAgentReviewController extends Controller
{
    /**
     * Display a listing of agent reviews
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $sortField = $request->input('sort_by', 'review_id');
            $sortOrder = $request->input('sort_order', 'desc');

            $criteria = new QueryCriteria();
            $criteria->with(['mw_listing_user', 'mw_listing_user_agent']);

            if ($request->filled('agent_id')) {
                $criteria->where('agent_id', '=', $request->agent_id);
            }

            if ($request->filled('status')) {
                $statusMap = [
                    'waiting' => 'W',
                    'approved' => 'A',
                    'rejected' => 'R'
                ];

                if (!array_key_exists($request->status, $statusMap)) {
                    return new JsonResponse([
                        'status' => 'error',
                        'message' => 'Invalid status parameter. Valid options: waiting, approved, rejected'
                    ], 400);
                }

                $criteria->where('status', '=', $statusMap[$request->status]);
            }


            if ($request->filled('rating')) {
                $criteria->where('rating', '=', $request->rating);
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

            $reviews = $criteria->applyToEloquent(MwAgentReview::query())
                               ->paginate($perPage);

            return MwAgentReviewResource::collection($reviews)
                ->additional([
                    'meta' => [
                        'total' => $reviews->total(),
                        'current_page' => $reviews->currentPage(),
                        'per_page' => $reviews->perPage(),
                        'last_page' => $reviews->lastPage(),
                        'filters' => [
                            'agent_id' => $request->input('agent_id'),
                            'status' => $request->input('status'),
                            'rating' => $request->input('rating'),
                            'search' => $request->input('search'),
                            'date_from' => $request->input('date_from'),
                            'date_to' => $request->input('date_to'),
                        ]
                    ]
                ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified review
     */
    public function show(string $id)
    {
        try {
            $criteria = new QueryCriteria();
            $criteria->where('review_id', '=', $id)
                    ->with(['mw_listing_user', 'mw_listing_user_agent']);

            $review = $criteria->applyToEloquent(MwAgentReview::query())
                              ->firstOrFail();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review retrieved successfully',
                'data' => new MwAgentReviewResource($review)
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Review not found'
            ], 404);
        }
    }

    /**
     * Approve a review
     */
    public function approveReview(Request $request, string $id)
    {
        try {
            $review = MwAgentReview::findOrFail($id);

            if ($review->status === 'A') {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Review is already approved'
                ], 400);
            }

            $review->update([
                'status' => 'A',
                'last_updated' => now()
            ]);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review approved successfully',
                'data' => new MwAgentReviewResource($review->load(['mw_listing_user', 'mw_listing_user_agent']))
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to approve review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a review
     */
    public function rejectReview(Request $request, string $id)
    {
        try {
            $review = MwAgentReview::findOrFail($id);

            if ($review->status === 'R') {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Review is already rejected'
                ], 400);
            }

            $review->update([
                'status' => 'R',
                'last_updated' => now()
            ]);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review rejected successfully',
                'data' => new MwAgentReviewResource($review->load(['mw_listing_user', 'mw_listing_user_agent']))
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to reject review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer|exists:mysql_legacy.mw_listing_users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'property_type' => 'nullable|integer',
            'location' => 'required|string|max:255',
            'when_interact' => 'required|string|max:255',
            'sect' => 'nullable|string|max:255',
            'property_link' => 'nullable|url|max:500',
            'user_id' => 'nullable|integer|exists:mysql_legacy.mw_listing_users,user_id'
        ]);

        try {
            $review = MwAgentReview::create([
                'agent_id' => $request->agent_id,
                'rating' => $request->rating,
                'review' => $request->review,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'property_type' => $request->property_type,
                'location' => $request->location,
                'when_interact' => $request->when_interact,
                'date_added' => now(),
                'last_updated' => now(),
                'sect' => $request->sect,
                'property_link' => $request->property_link,
                'status' => 'W',
                'user_id' => $request->user_id,
            ]);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review submitted successfully and is waiting approval',
                'data' => new MwAgentReviewResource($review->load(['mw_listing_user', 'mw_listing_user_agent']))
            ], 201);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'review' => 'sometimes|nullable|string|max:1000',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:15',
            'property_type' => 'sometimes|nullable|integer',
            'location' => 'sometimes|string|max:255',
            'when_interact' => 'sometimes|string|max:255',
            'sect' => 'sometimes|nullable|string|max:255',
            'property_link' => 'sometimes|nullable|url|max:500',
        ]);

        try {
            $review = MwAgentReview::findOrFail($id);

            if ($review->status !== 'W') {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Only waiting reviews can be updated'
                ], 400);
            }

            $updateData = $request->only([
                'rating', 'review', 'name', 'email', 'phone',
                'property_type', 'location', 'when_interact',
                'sect', 'property_link'
            ]);
            $updateData['last_updated'] = now();

            $review->update($updateData);

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review updated successfully',
                'data' => new MwAgentReviewResource($review->load(['mw_listing_user', 'mw_listing_user_agent']))
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified review
     */
    public function destroy(string $id)
    {
        try {
            $review = MwAgentReview::findOrFail($id);

            if ($review->status !== 'W') {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Only waiting reviews can be deleted'
                ], 400);
            }

            $review->delete();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Review deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
