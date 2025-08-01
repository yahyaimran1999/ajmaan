<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\MwStatistic;
use App\Models\MwStatisticsPage;
use App\Models\MwPlaceAnAd;

class StatisticsController extends Controller
{

    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $userId = $user->user_id;

            // Get call statistics
            $totalCallCount = $this->getCallCount($userId);
            $todayCallCount = $this->getCallCount($userId, 'today');
            $thirtyDaysCallCount = $this->getCallCount($userId, '30days');

            // Get page view statistics
            $totalPageCount = $this->getPageCount($userId);
            $todayPageCount = $this->getPageCount($userId, 'today');
            $thirtyDaysPageCount = $this->getPageCount($userId, '30days');

            // Get mail statistics
            $totalMailCount = $this->getMailCount($userId);
            $todayMailCount = $this->getMailCount($userId, 'today');
            $thirtyDaysMailCount = $this->getMailCount($userId, '30days');

            // Get property-wise statistics
            $pageCountByProperty = $this->getPageCountByProperty($userId);
            $emailCountByProperty = $this->getClickCountByProperty($userId, 'E');
            $callCountByProperty = $this->getClickCountByProperty($userId, 'C');

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Statistics retrieved successfully',
                'data' => [
                    // 'calls' => [
                    //     'total' => $totalCallCount,
                    //     'today' => $todayCallCount,
                    //     'last_30_days' => $thirtyDaysCallCount
                    // ],
                    // 'page_views' => [
                    //     'total' => $totalPageCount,
                    //     'today' => $todayPageCount,
                    //     'last_30_days' => $thirtyDaysPageCount
                    // ],
                    // 'emails' => [
                    //     'total' => $totalMailCount,
                    //     'today' => $todayMailCount,
                    //     'last_30_days' => $thirtyDaysMailCount
                    // ],
                    'all_time' => [
                        'pages' => $totalPageCount,
                        'calls' => $totalCallCount,
                        'mail' => $totalMailCount,
                    ],
                    'today' => [
                        'pages' => $todayPageCount,
                        'calls' => $todayCallCount,
                        'mail' => $todayMailCount,
                    ],
                    'last_30_days' => [
                        'pages' => $thirtyDaysPageCount,
                        'calls' => $thirtyDaysCallCount,
                        'mail' => $thirtyDaysMailCount,
                    ],
                    'property_wise' => [
                        'page_views' => $pageCountByProperty,
                        'email_clicks' => $emailCountByProperty,
                        'call_clicks' => $callCountByProperty
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function getCallCount(int $userId, ?string $duration = null): int
    {
        $query = MwPlaceAnAd::where('user_id', $userId)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->with(['mw_statistics' => function ($query) use ($duration) {
                $query->where('type', 'C');
                
                if ($duration === 'today') {
                    $query->whereDate('date', today());
                } elseif ($duration === '30days') {
                    $query->where('date', '>=', now()->subDays(30));
                }
            }]);

        $ads = $query->get();
        
        $totalCount = 0;
        foreach ($ads as $ad) {
            $totalCount += $ad->mw_statistics->sum('count');
        }

        return $totalCount;
    }

    private function getPageCount(int $userId, ?string $duration = null): int
    {
        $query = MwPlaceAnAd::where('user_id', $userId)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->with(['mw_statistics_pages' => function ($query) use ($duration) {
                if ($duration === 'today') {
                    $query->whereDate('date', today());
                } elseif ($duration === '30days') {
                    $query->where('date', '>=', now()->subDays(30));
                }
            }]);

        $ads = $query->get();
        
        $totalCount = 0;
        foreach ($ads as $ad) {
            $totalCount += $ad->mw_statistics_pages->sum('count');
        }

        return $totalCount;
    }

    private function getMailCount(int $userId, ?string $duration = null): int
    {
        $query = MwPlaceAnAd::where('user_id', $userId)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->with(['mw_statistics' => function ($query) use ($duration) {
                $query->where('type', 'E');
                
                if ($duration === 'today') {
                    $query->whereDate('date', today());
                } elseif ($duration === '30days') {
                    $query->where('date', '>=', now()->subDays(30));
                }
            }]);

        $ads = $query->get();
        
        $totalCount = 0;
        foreach ($ads as $ad) {
            $totalCount += $ad->mw_statistics->sum('count');
        }

        return $totalCount;
    }

    private function getPageCountByProperty(int $userId): \Illuminate\Support\Collection
    {
        $ads = MwPlaceAnAd::where('user_id', $userId)
            ->where('isTrash', '0')
            ->where('status', 'A')
            ->with(['mw_statistics_pages'])
            ->get(['id', 'ad_title']);

        $results = $ads->map(function ($ad) {
            return [
                'ad_id' => $ad->id,
                'ad_title' => $ad->ad_title,
                'total_views' => $ad->mw_statistics_pages->sum('count')
            ];
        })
        ->filter(function ($item) {
            return $item['total_views'] > 0;
        })
        ->sortByDesc('total_views')
        ->take(10)
        ->values();

        return $results;
    }

    private function getClickCountByProperty(int $userId, string $type): \Illuminate\Support\Collection
    {
        $ads = MwPlaceAnAd::where('user_id', $userId)
            ->where('isTrash', '0')
            ->where('status', 'A')
            ->with(['mw_statistics' => function ($query) use ($type) {
                $query->where('type', $type);
            }])
            ->get();

        $results = $ads->map(function ($ad) {
            return [
                'ad_id' => $ad->id,
                'ad_title' => $ad->ad_title,
                'total_clicks' => $ad->mw_statistics->sum('count')
            ];
        })
        ->filter(function ($item) {
            return $item['total_clicks'] > 0;
        })
        ->sortByDesc('total_clicks')
        ->take(10)
        ->values();

        return $results;
    }
}