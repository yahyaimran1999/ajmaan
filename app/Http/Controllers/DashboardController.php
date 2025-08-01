<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MwListingUser;
use App\Models\MwPlaceAnAd;
use App\Models\MwUserPackage;
use App\Models\MwStatistic;
use App\Models\MwStatisticsPage;
use App\Models\MwAdFavourite;
use App\Models\MwContactU;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    private const SECTION_SALE = 1;
    private const SECTION_RENT = 2;
    private const DEFAULT_LIMIT = 10;

    public function index()
    {
        try {
            $member = Auth::user();
            
            $counter = MwPlaceAnAd::getCounter($member->user_id);
            $findactivePackages = MwUserPackage::findActivePackages($member->user_id);
            $last30DaysPageviews = MwStatisticsPage::pageCount('30day');
            $last30DaysCallCount = MwStatistic::callCount('30day');

            // Ensure we get integer values from the statistics
            $pageviewsCount = $this->extractCountValue($last30DaysPageviews);
            $callCount = $this->extractCountValue($last30DaysCallCount);

            if($member->isAgent()){
                $last30DaysMailCount = MwStatistic::mailCount('30day');
                $currentMonthMailCount = MwStatistic::mailCount('current_month');
                $mailCount = $this->extractCountValue($last30DaysMailCount);
                $currentMonthMail = $this->extractCountValue($currentMonthMailCount);
            } elseif($member->isUser()) {
                $totalFavourites = $this->getTotalFavourites($member->user_id);
                $totalEnquiries = $this->getTotalEnquiries($member->user_id);
            } else {
                $last30DaysMailCount = MwStatistic::mailCount('30day');
                $mailCount = $this->extractCountValue($last30DaysMailCount);
            }
            
            // Initialize variables to avoid undefined variable issues
            $mailCount = $mailCount ?? 0;
            $currentMonthMail = $currentMonthMail ?? 0;
            $totalFavourites = $totalFavourites ?? 0;
            $totalEnquiries = $totalEnquiries ?? 0;
            
            $topLocationsBySale = $this->getTopLocationsByListings(self::SECTION_SALE);
            $topLocationsByRent = $this->getTopLocationsByListings(self::SECTION_RENT);
            $topLocationsBySaleLeads = $this->getTopLocationsByLeads(self::SECTION_SALE);
            $topLocationsByRentLeads = $this->getTopLocationsByLeads(self::SECTION_RENT);

            $memberData = [
                'user_id' => $member->user_id,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'user_type' => $member->user_type,
                'user_role' => $member->getRoleName(),
            ];

            if ($member->isAgency()) {
                $memberData['company_name'] = $member->company_name;
                $memberData['company_logo'] = $member->company_logo;
            } else {
                $memberData['image'] = $member->image;
            }

            $statisticsData = [];
            if ($member->isAgent()) {
                $statisticsData = [
                    'leads' => $mailCount,
                    'current_month_leads' => $currentMonthMail,
                ];
            } elseif ($member->isUser()) {
                $statisticsData = [
                    'total_favourites' => $totalFavourites,
                    'total_enquiries' => $totalEnquiries,
                ];
            } else {
                $statisticsData = [
                    'impressions' => $pageviewsCount,
                    'clicks' => $callCount,
                    'leads' => $mailCount,
                ];
            }

            $responseData = [
                'member' => $memberData,
                'statistics' => $statisticsData,
            ];

            if ($member->isAgency() || $member->isAgent()) {
                $responseData['properties'] = [
                    'published' => $counter['approved'] ?? 0,
                    'for_sale' => $counter['sale'] ?? 0, 
                    'for_rent' => $counter['rent'] ?? 0
                ];
            }

            if ($member->isAgency()) {
                $responseData['subscription'] = $findactivePackages ? [
                    'package_name' => $findactivePackages->package_name,
                    'validity' => $findactivePackages->ValidityNewTile,
                    'quotas' => [
                        'listings' => $findactivePackages->ads_allowed,
                        'featured' => $findactivePackages->number_of_featured_ads,
                        'hot' => $findactivePackages->number_of_hot_ads,
                        'daily_refresh' => $findactivePackages->daily_refresh_limit
                    ]
                ] : null;
            }

            
            if ($member->isAgency()) {
                $latestAgents = $this->getLatestAgents($member->user_id);
                $latestSaleListings = $this->getLatestSaleListings($member->user_id, self::DEFAULT_LIMIT, true); // include agents
                
                $responseData['latest_agents'] = $latestAgents;
                $responseData['latest_sale_listings'] = $latestSaleListings;
            } elseif ($member->isAgent()) {
                $latestSaleListings = $this->getLatestSaleListings($member->user_id, self::DEFAULT_LIMIT, false); // own only
                
                $responseData['latest_sale_listings'] = $latestSaleListings;
            } elseif ($member->isUser()) {
                $userFavorites = $this->getUserFavoriteAds($member->user_id);
                $userRecentViews = $this->getUserRecentViews($member->user_id);
                
                $responseData['favorite_ads'] = $userFavorites;
                $responseData['recent_views'] = $userRecentViews;
            }

            
            if (!$member->isUser()) {
                $responseData['analytics'] = [
                    'topLocations' => [
                        'byListings' => [
                            'sale' => $topLocationsBySale,
                            'rent' => $topLocationsByRent
                        ],
                        'byLeads' => [
                            'sale' => $topLocationsBySaleLeads,
                            'rent' => $topLocationsByRentLeads
                        ]
                    ]
                ];
            }

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function getTopLocationsByListings(int $sectionId): array
    {
        $userId = Auth::id();
        
        return MwPlaceAnAd::query()
            ->selectRaw('COUNT(mw_place_an_ad.id) as count, mw_city.city_name')
            ->join('mw_city', 'mw_city.city_id', '=', 'mw_place_an_ad.city')
            ->join('mw_listing_users as usr', 'usr.user_id', '=', 'mw_place_an_ad.user_id')
            ->where('mw_place_an_ad.status', 'A')
            ->where('mw_place_an_ad.isTrash', '0')
            ->where('mw_place_an_ad.section_id', $sectionId)
            ->where(function($query) use ($userId) {
            $query->whereRaw('CASE WHEN usr.parent_user IS NOT NULL 
                THEN (usr.parent_user = ? OR mw_place_an_ad.user_id = ?) 
                ELSE mw_place_an_ad.user_id = ? END', [$userId, $userId, $userId]);
            })
            ->groupBy('mw_place_an_ad.city', 'mw_city.city_name')
            ->orderByDesc(DB::raw('COUNT(mw_place_an_ad.id)'))
            ->limit(self::DEFAULT_LIMIT)
            ->get()
            ->pluck('count', 'city_name')
            ->toArray();
    }
    
    private function getTopLocationsByLeads(int $sectionId): array
    {
        $userId = Auth::id();
        
        return DB::connection('mysql_legacy')->table('mw_statistics as stats')
            ->selectRaw('SUM(stats.count) as count, mw_city.city_name')
            ->join('mw_place_an_ad as ad', 'stats.id', '=', 'ad.id')
            ->join('mw_city', 'mw_city.city_id', '=', 'ad.city')
            ->join('mw_listing_users as usr', 'usr.user_id', '=', 'ad.user_id')
            ->where('ad.status', 'A')
            ->where('ad.isTrash', '0')
            ->where('ad.section_id', $sectionId)
            ->where('stats.type', 'E') // Add this to only count email leads
            ->where(function($query) use ($userId) {
                $query->whereRaw('CASE WHEN usr.parent_user IS NOT NULL 
                    THEN (usr.parent_user = ? OR ad.user_id = ?) 
                    ELSE ad.user_id = ? END', [$userId, $userId, $userId]);
            })
            ->groupBy('ad.city', 'mw_city.city_name')
            ->orderByDesc(DB::raw('SUM(stats.count)'))
            ->limit(self::DEFAULT_LIMIT)
            ->get()
            ->pluck('count', 'city_name')
            ->toArray();
    }

    private function getLatestAgents(int $agencyId, int $limit = self::DEFAULT_LIMIT): array
    {
        return MwListingUser::where('parent_user', $agencyId)
            ->where('user_type', 'A')
            ->where('isTrash', '0')
            ->where('status', 'A')
            ->orderBy('user_id', 'desc')
            ->limit($limit)
            ->get([
                'user_id',
                'first_name',
                'last_name',
                'image',
                'date_added'
            ])
            ->map(function ($agent) {
                return [
                    'user_id' => $agent->user_id,
                    'name' => trim($agent->first_name . ' ' . $agent->last_name),
                    'image' => $agent->image,
                    'joined_date' => $agent->date_added->format('Y-m-d'),
                ];
            })
            ->toArray();
    }

    private function getLatestSaleListings(int $userId, int $limit = self::DEFAULT_LIMIT, bool $includeAgents = false): array
    {
        $query = MwPlaceAnAd::query()
            // ->where('section_id', 1)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->with(['mw_city', 'mw_category', 'mw_listing_user','mw_section','mw_ad_images'])
            ->orderBy('id', 'desc')
            ->limit($limit);

        if ($includeAgents) {
           
            $agentIds = MwListingUser::where('parent_user', $userId)
                ->where('user_type', 'A')
                ->pluck('user_id')
                ->toArray();
            
            $allUserIds = array_merge([$userId], $agentIds);
            $query->whereIn('user_id', $allUserIds);
        } else {
            
            $query->where('user_id', $userId);
        }

        return $query->get([
                'id',
                'ad_title',
                'price',
                'status',
                'city',
                'category_id',
                'section_id',
                'user_id',
                'date_added',
            ])
            ->map(function ($listing) {
                return [
                    'id' => $listing->id,
                    'title' => $listing->ad_title,
                    'price' => $listing->price,
                    'status' => 'Published',
                    'city' => $listing->mw_city->city_name ?? null,
                    'category' => $listing->mw_category->category_name ?? null,
                    'type' => $listing->mw_section->section_name ?? null,
                    'image' => $listing->mw_ad_images()->first()->image_name ?? null,
                    'listed_by' => $listing->mw_listing_user ? 
                        trim($listing->mw_listing_user->first_name . ' ' . $listing->mw_listing_user->last_name) : null,
                    'listed_date' => $listing->date_added->format('Y-m-d'),
                ];
            })
            ->toArray();
    }

    private function getTotalFavourites(int $userId): int
    {
        return MwAdFavourite::where('user_id', $userId)->count();
    }

    private function getTotalEnquiries(int $userId): int
    {
        return MwContactU::where('user_id', $userId)->count();
    }

    private function getUserFavoriteAds(int $userId, int $limit = self::DEFAULT_LIMIT): array
    {
        return MwAdFavourite::query()
            ->where('user_id', $userId)
            ->with(['mw_place_an_ad.mw_city', 'mw_place_an_ad.mw_category', 'mw_place_an_ad.mw_section', 'mw_place_an_ad.mw_ad_images'])
            ->orderBy('ad_id', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($favorite) {
                $ad = $favorite->mw_place_an_ad;
                if (!$ad) return null;
                
                return [
                    'id' => $ad->id,
                    'title' => $ad->ad_title,
                    'price' => $ad->price,
                    'bedrooms' => $ad->bedrooms,
                    'category_name' => $ad->mw_category->category_name ?? null,
                    'city_name' => $ad->mw_city->city_name ?? null,
                    'slug' => $ad->slug,
                    'image' => $ad->mw_ad_images->where('isTrash', '0')->first()->image_name ?? null,
                    'status' => $ad->status,
                    'purpose' => $ad->mw_section->section_name,
                    'viewed_date' => $ad->date_added->format('Y-m-d H:i:s'),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function getUserRecentViews(int $userId, int $limit = self::DEFAULT_LIMIT): array
    {
        $results = MwStatisticsPage::with(['mw_place_an_ad.mw_listing_user', 'mw_place_an_ad.mw_ad_images', 'mw_place_an_ad.mw_section'])
            ->where('user_id', $userId)
            ->select(['pid', 'user_id', 'date'])
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($stat) {
                $ad = $stat->mw_place_an_ad;
                if (!$ad) return null;
                
                return (object)[
                    'ad_id' => $ad->id,
                    'ad_title' => $ad->ad_title,
                    'price' => $ad->price,
                    'ad_bedrooms' => $ad->bedrooms,
                    'category_name' => $ad->mw_category->category_name ?? null,
                    'city_name' => $ad->mw_city->city_name ?? null,
                    'slug' => $ad->slug,
                    'image' => $ad->mw_ad_images->where('isTrash', '0')->first()->image_name ?? null,
                    'status' => $ad->status,
                    'section_name' => $ad->mw_section->section_name,
                    'date' => $stat->date->format('Y-m-d')
                ];
            })
            ->filter();

        return collect($results)->map(function ($view) {
            return [
                'id' => $view->ad_id,
                'title' => $view->ad_title,
                'price' => $view->price,
                'bedrooms' => $view->ad_bedrooms,
                'category_name' => $view->category_name,
                'city_name' => $view->city_name,
                'slug' => $view->slug,
                'image' => $view->image,
                'status' => $view->status,
                'purpose' => $view->section_name,
                'viewed_date' => $view->date,
            ];
        })->toArray();
    }

    private function formatStatisticValue($value): int
    {
        return is_object($value) && isset($value->s_count) ? (int)$value->s_count : (int)$value;
    }

    private function extractCountValue($value)
    {
        if (is_object($value)) {
            if (isset($value->s_count)) {
                return (int)$value->s_count;
            }
            if (isset($value->count)) {
                return (int)$value->count;
            }
            if (isset($value->total)) {
                return (int)$value->total;
            }
            
            if (method_exists($value, 'count')) {
                return $value->count();
            }

            return 0;
        }

        return (int)$value;
    }
}

