<?php

namespace App\Http\Controllers;

use App\Models\MwAreaGuide;
use App\Http\Resources\MwAreaGuideResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MwAreaGuideController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $criteria = new QueryCriteria();
        $criteria->with([
                'mw_state',
                'mw_city',
                'mw_category',
                'mw_master',
                'mw_guide_images',
                'mw_gide_faqs',
                'mw_contact_us'
            ]);

        if ($request->filled('search')) {
            $criteria->where(function($subCriteria) use ($request) {
                $subCriteria->whereLike('title', $request->search)
                           ->orWhere(function($orCriteria) use ($request) {
                               $orCriteria->whereLike('highlight', $request->search);
                           });
            });
        }

        if ($request->filled('category_id')) {
            $criteria->where('category_id', '=', $request->category_id);
        }

        if ($request->filled('type')) {
            switch ($request->type) {
            case 'school':
                $criteria->where('f_type', '=', 'S');
                break;
            case 'building':
                $criteria->where('f_type', '=', 'B');
                break;
            case 'area':
                $criteria->where('f_type', '=', 'A');
                break;
            case 'hostel':
                $criteria->where('f_type', '=', 'Hg');
                break;
            }
        }

        $criteria->orderBy($sortField, $sortOrder);

        $guides = $criteria->applyToEloquent(MwAreaGuide::query())
                          ->paginate($perPage);

        return MwAreaGuideResource::collection($guides)
            ->additional([
                'meta' => [
                    'total' => $guides->total(),
                    'current_page' => $guides->currentPage(),
                    'per_page' => $guides->perPage(),
                    'last_page' => $guides->lastPage(),
                ]
            ]);
    }

    public function show(string $id)
    {
        try {
            $criteria = new QueryCriteria();
            $criteria->where('id', '=', $id)
                    ->with([
                        'mw_state',
                        'mw_city',
                        'mw_category',
                        'mw_master',
                        'mw_guide_images',
                        'mw_gide_faqs',
                        'mw_contact_us'
                    ]);

            $guide = $criteria->applyToEloquent(MwAreaGuide::query())
                             ->firstOrFail();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Area guide retrieved successfully',
                'data' => new MwAreaGuideResource($guide)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Area guide not found'
            ], 404);
        }
    }
}
