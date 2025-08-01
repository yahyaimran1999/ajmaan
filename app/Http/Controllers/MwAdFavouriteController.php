<?php

namespace App\Http\Controllers;

use App\Models\MwAdFavourite;
use App\Http\Resources\MwAdFavouriteResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MwAdFavouriteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $criteria = new QueryCriteria();
        $criteria->with(['mw_place_an_ad']);

        if ($request->filled('user_id')) {
            $criteria->where('user_id', '=', $request->user_id);
        }

        $favourites = $criteria->applyToEloquent(MwAdFavourite::query())
                             ->paginate($perPage);

        return MwAdFavouriteResource::collection($favourites)
            ->additional([
                'meta' => [
                    'total' => $favourites->total(),
                    'current_page' => $favourites->currentPage(),
                    'per_page' => $favourites->perPage(),
                    'last_page' => $favourites->lastPage(),
                ]
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'ad_id' => 'required|integer',
        ]);

        try {
            $criteria = new QueryCriteria();
            $criteria->where('user_id', '=', $request->user_id)
                    ->where('ad_id', '=', $request->ad_id);

            $existing = $criteria->applyToEloquent(MwAdFavourite::query())->first();

            if ($existing) {
                $favourite = $existing;
            } else {
                $favourite = MwAdFavourite::create([
                    'user_id' => $request->user_id,
                    'ad_id' => $request->ad_id,
                ]);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Added to favourites successfully',
                'data' => new MwAdFavouriteResource($favourite)
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to add to favourites'
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $criteria = new QueryCriteria();
            $criteria->where('user_id', '=', $request->user_id)
                    ->where('ad_id', '=', $request->ad_id);

            $criteria->applyToEloquent(MwAdFavourite::query())->delete();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Removed from favourites successfully'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to remove from favourites'
            ], 500);
        }
    }
}
