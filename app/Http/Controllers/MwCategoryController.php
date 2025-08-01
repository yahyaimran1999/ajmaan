<?php

namespace App\Http\Controllers;

use App\Models\MwCategory;
use App\Models\MwListingTypeField;
use App\Models\MwTranslateRelation;
use App\Models\MwTranslationData;
use App\Http\Resources\MwCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class MwCategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = MwCategory::with(['mw_section'])
                ->where('status', 'A')
                 ->where('isTrash', '0');

            if ($request->filled('type')) {
                if ($request->type === 'buy') {
                    $categories->where('section_id', 1);
                } elseif ($request->type === 'rent') {
                    $categories->where('section_id', 2);
                } elseif ($request->type === 'new_development') {
                    $categories->where('section_id', 3);
                } elseif ($request->type === 'commercial' || $request->type === 'residential') {
                    $listingType = match($request->type) {
                        'residential' => 118,
                        'commercial' => 120,
                        default => null
                    };

                    if ($listingType) {
                        $categoryIds = MwListingTypeField::where('listing_type', $listingType)
                            ->pluck('category_id')
                            ->toArray();

                        if (!empty($categoryIds)) {
                            $categories->whereIn('category_id', $categoryIds);
                        }
                    }
                }
            }

            $result = $categories->orderBy('category_id', 'asc')
                               ->get();

            if ($result->isEmpty()) {
                return new JsonResponse([
                    'status' => 'success',
                    'message' => 'No categories found',
                    'data' => []
                ], 200);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => MwCategoryResource::collection($result)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Category fetch error: ' . $e->getMessage());
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve categories'
            ], 500);
        }
    }

    public function getUserSpecializations()
    {
        try {
            $categories = MwCategory::where('isTrash', '0')
                ->whereIn('f_type', ['C', 'U'])
                ->select('category_id', 'category_name', 'slug')
                ->orderBy('category_name')
                ->get();
                
            $result = $categories->map(fn($item) => [
                'id' => $item->category_id,
                'name' => $item->category_name,
                'slug' => $item->slug
            ]);
            
            return new JsonResponse([
                'status' => 'success',
                'message' => 'User specializations retrieved successfully',
                'data' => $result
            ], 200);
            
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve user specializations',
                'debug' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ] : null
            ], 500);
        }
    }
}
