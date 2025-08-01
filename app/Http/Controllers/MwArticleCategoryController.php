<?php

namespace App\Http\Controllers;

use App\Models\MwArticleCategory;
use App\Http\Resources\MwArticleCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MwArticleCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_by', 'category_id');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = MwArticleCategory::query()
            ->with(['mw_article_categories']);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->orderBy($sortField, $sortOrder)
                          ->paginate($perPage);

        return MwArticleCategoryResource::collection($categories)
            ->additional([
                'meta' => [
                    'total' => $categories->total(),
                    'current_page' => $categories->currentPage(),
                    'per_page' => $categories->perPage(),
                    'last_page' => $categories->lastPage(),
                ]
            ]);
    }
}
