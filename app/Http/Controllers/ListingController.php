<?php

namespace App\Http\Controllers;

use App\Models\MwLanguage;
use App\Models\MwService;
use App\Models\MwCountry;
use App\Models\MwCity;
use App\Models\MwState;
use App\Models\MwCategory;
use App\Models\MwDeveloper;
use App\Models\MwPlaceAnAd;
use App\Models\MwAmenitiesCategoryList;
use App\Http\Resources\MwLanguageResource;
use App\Http\Resources\MwServiceResource;
use App\Http\Resources\MwCountryResource;
use App\Http\Resources\MwCityResource;
use App\Http\Resources\MwStateResource;
use App\Http\Resources\MwCategoryResource;
use App\Http\Resources\MwDeveloperResource;
use App\Http\Resources\MwListPlaceAnAdResource;
use App\Http\Resources\MwAmenitiesCategoryGroupResource;
use App\Database\Criteria\QueryCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ListingController extends Controller
{
    public function languages()
    {
        try {
            $languages = MwLanguage::all();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Languages retrieved successfully',
                'data' => MwLanguageResource::collection($languages)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve languages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function services()
    {
        try {
            $services = MwService::where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Services retrieved successfully',
                'data' => MwServiceResource::collection($services)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve services',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function countries()
    {
        try {
            $countries = MwCountry::where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Countries retrieved successfully',
                'data' => MwCountryResource::collection($countries)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve countries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cities()
    {
        try {
            $cities = MwCity::where('isTrash', '0')
                           ->whereNull('parent_id')
                           ->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Cities retrieved successfully',
                'data' => MwCityResource::collection($cities)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function community($city_id)
    {
        try {
            $cities = MwCity::where('isTrash', '0')
                           ->where('parent_id', $city_id)
                           ->get();

            if (!$city_id) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'City ID is required'
                ], 400);
            }

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Sub Cities retrieved successfully',
                'data' => MwCityResource::collection($cities)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function states()
    {
        try {
            $states = MwState::where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'States retrieved successfully',
                'data' => MwStateResource::collection($states)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve states',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function categories()
    {
        try {
            $categories = MwCategory::where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Categories retrieved successfully',
                'data' => MwCategoryResource::collection($categories)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function statesByCountry($countryId)
    {
        try {
            $states = MwState::where('country_id', $countryId)->where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'States retrieved successfully',
                'data' => MwStateResource::collection($states)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve states',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function citiesByState($stateId)
    {
        try {
            $cities = MwCity::where('state_id', $stateId)->where('isTrash', '0')->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Cities retrieved successfully',
                'data' => MwCityResource::collection($cities)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function developers()
    {
        try {
            $developers = MwDeveloper::where('status', 'A')
                                   ->where('isTrash', '0')
                                   ->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Developers retrieved successfully',
                'data' => MwDeveloperResource::collection($developers)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve developers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function amenities(Request $request)
    {
        try {

            $category_id = $request->input('category_id');

             if (!$request->filled('category_id')) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Category ID is required'
                ], 400);
            }

            $amenitiesCategories = MwAmenitiesCategoryList::with(['mw_amenity' => function($query) {
                $query->where('isTrash', '0');
            }])
            ->where('category_id', $category_id)
            ->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Amenities retrieved successfully',
                'data' => new MwAmenitiesCategoryGroupResource($amenitiesCategories)
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve amenities categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userCities(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            if (!$user_id) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'User ID is required'
                ], 400);
            }
            
           $cities = MwCity::select('mw_city.city_id', 'mw_city.city_name')
                           ->join('mw_place_an_ad', 'mw_city.city_id', '=', 'mw_place_an_ad.city')
                           ->where('mw_place_an_ad.user_id', $user_id)
                           ->where('mw_place_an_ad.isTrash', '0')
                           ->where('mw_place_an_ad.status', 'A')
                           ->where('mw_city.isTrash', '0')
                           ->groupBy('mw_city.city_id', 'mw_city.city_name')
                           ->orderByRaw('COUNT(mw_place_an_ad.id) DESC')
                           ->get();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'User cities retrieved successfully',
                'data' => $cities->map(function($city) {
                    return [
                        'city_id' => $city->city_id,
                        'city_name' => $city->city_name
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve user cities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
