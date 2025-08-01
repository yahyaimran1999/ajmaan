<?php

namespace App\Http\Controllers;

use App\Models\MwArticle;
use App\Http\Resources\MwArticleResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MwArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = MwArticle::query()
            ->with(['mw_article_to_categories.mw_article_category'])
            ->where('f_type', 'Bl')
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->whereHas('mw_article_to_categories', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $articles = $query->paginate($perPage);

        return MwArticleResource::collection($articles)
            ->additional([
                'meta' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'last_page' => $articles->lastPage(),
                ]
            ]);
    }

    public function show(string $id)
    {
        try {
            $article = MwArticle::where('article_id', $id)
                ->with([
                    'mw_article_to_categories',
                    'mw_advertisement_items',
                    'mw_article_view',
                    'mw_blog_comments',
                    'mw_translate_relations' ])
                ->firstOrFail();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Article retrieved successfully',
                'data' => new MwArticleResource($article)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Article not found'
            ], 404);
        }
    }
}
