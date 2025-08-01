<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MwUserPackage;
use App\Models\MwPackageNew;
use App\Http\Resources\UserPackageResource;
use Carbon\Carbon;

class UserPackageController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::user()->user_id;
            
            // Get all user packages with package details
            $userPackages = MwUserPackage::with(['mw_package_new', 'mw_price_plan_order'])
                ->select([
                    'mw_user_packages.*',
                    DB::raw('CASE 
                        WHEN mw_user_packages.validity = 0 THEN "Unlimited"
                        WHEN DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) >= CURDATE() THEN "Active"
                        ELSE "Expired"
                    END as package_status'),
                    DB::raw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) as expiry_date'),
                    DB::raw('DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) as days_remaining')
                ])
                ->leftJoin('mw_package_new as pkg', 'pkg.package_id', '=', 'mw_user_packages.package_id')
                ->leftJoin('mw_package_new as pkg_parent', 'pkg_parent.package_id', '=', 'pkg.parent_id')
                ->where('mw_user_packages.user_id', $userId)
                ->where(function($query) {
                    $query->whereNull('mw_user_packages.deleted_status')
                          ->orWhere('mw_user_packages.deleted_status', '0');
                })
                ->orderByRaw('CASE WHEN mw_user_packages.status = "active" THEN 0 ELSE 1 END')
                ->orderBy('mw_user_packages.date_added', 'desc')
                ->get();

            $packages = $userPackages->map(function($package) {
                return new UserPackageResource($package);
            });

            return response()->json([
                'success' => true,
                'data' => $packages,
                'total' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user packages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display specific package details
     */
    public function show($id)
    {
        try {
            $userId = Auth::user()->user_id;
            
            $userPackage = MwUserPackage::with(['mw_package_new', 'mw_price_plan_order'])
                ->select([
                    'mw_user_packages.*',
                    DB::raw('CASE 
                        WHEN mw_user_packages.validity = 0 THEN "Unlimited"
                        WHEN DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) >= CURDATE() THEN "Active"
                        ELSE "Expired"
                    END as package_status'),
                    DB::raw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) as expiry_date'),
                    DB::raw('DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) as days_remaining')
                ])
                ->leftJoin('mw_package_new as pkg', 'pkg.package_id', '=', 'mw_user_packages.package_id')
                ->leftJoin('mw_package_new as pkg_parent', 'pkg_parent.package_id', '=', 'pkg.parent_id')
                ->where('mw_user_packages.id', $id)
                ->where('mw_user_packages.user_id', $userId)
                ->where(function($query) {
                    $query->whereNull('mw_user_packages.deleted_status')
                          ->orWhere('mw_user_packages.deleted_status', '0');
                })
                ->first();

            if (!$userPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found or access denied'
                ], 404);
            }

            $packageDetails = new UserPackageResource($userPackage, true);

            return response()->json([
                'success' => true,
                'data' => $packageDetails
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve package details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only active packages
     */
    public function active()
    {
        try {
            $userId = Auth::user()->user_id;
            
            $userPackages = MwUserPackage::with(['mw_package_new', 'mw_price_plan_order'])
                ->select([
                    'mw_user_packages.*',
                    DB::raw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) as expiry_date'),
                    DB::raw('DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) as days_remaining')
                ])
                ->where('mw_user_packages.user_id', $userId)
                ->where('mw_user_packages.status', 'active')
                ->where(function($query) {
                    $query->whereNull('mw_user_packages.deleted_status')
                          ->orWhere('mw_user_packages.deleted_status', '0');
                })
                ->where(function($query) {
                    $query->where('mw_user_packages.validity', 0)
                          ->orWhereRaw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) >= CURDATE()');
                })
                ->orderBy('mw_user_packages.date_added', 'desc')
                ->get();

            $packages = $userPackages->map(function($package) {
                $package->package_status = 'Active';
                return new UserPackageResource($package);
            });

            return response()->json([
                'success' => true,
                'data' => $packages,
                'total' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active packages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only expired packages
     */
    public function expired()
    {
        try {
            $userId = Auth::user()->user_id;
            
            $userPackages = MwUserPackage::with(['mw_package_new', 'mw_price_plan_order'])
                ->select([
                    'mw_user_packages.*',
                    DB::raw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) as expiry_date'),
                    DB::raw('DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) as days_remaining')
                ])
                ->where('mw_user_packages.user_id', $userId)
                ->where(function($query) {
                    $query->whereNull('mw_user_packages.deleted_status')
                          ->orWhere('mw_user_packages.deleted_status', '0');
                })
                ->where('mw_user_packages.validity', '>', 0)
                ->whereRaw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) < CURDATE()')
                ->orderBy('mw_user_packages.date_added', 'desc')
                ->get();

            $packages = $userPackages->map(function($package) {
                $package->package_status = 'Expired';
                return new UserPackageResource($package);
            });

            return response()->json([
                'success' => true,
                'data' => $packages,
                'total' => $packages->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve expired packages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get packages summary
     */
    public function summary()
    {
        try {
            $userId = Auth::user()->user_id;
            
            $activePackages = MwUserPackage::where('user_id', $userId)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereNull('deleted_status')
                          ->orWhere('deleted_status', '0');
                })
                ->where(function($query) {
                    $query->where('validity', 0)
                          ->orWhereRaw('DATE_ADD(date_added, INTERVAL validity DAY) >= CURDATE()');
                })
                ->count();

            $expiredPackages = MwUserPackage::where('user_id', $userId)
                ->where(function($query) {
                    $query->whereNull('deleted_status')
                          ->orWhere('deleted_status', '0');
                })
                ->where('validity', '>', 0)
                ->whereRaw('DATE_ADD(date_added, INTERVAL validity DAY) < CURDATE()')
                ->count();

            $totalPackages = MwUserPackage::where('user_id', $userId)
                ->where(function($query) {
                    $query->whereNull('deleted_status')
                          ->orWhere('deleted_status', '0');
                })
                ->count();

            // Get current package usage
            $currentPackage = MwUserPackage::findActivePackages($userId);
            $currentUsage = [];
            
            if ($currentPackage) {
                $currentUsage = [
                    'package_name' => $currentPackage->package_name ?? 'Unknown Plan',
                    'total_listings' => [
                        'used' => $currentPackage->used_ad ?? 0,
                        'allowed' => $currentPackage->ads_allowed ?? 0,
                        'remaining' => max(0, ($currentPackage->ads_allowed ?? 0) - ($currentPackage->used_ad ?? 0))
                    ],
                    'featured_listings' => [
                        'used' => $currentPackage->featured_used ?? 0,
                        'allowed' => $currentPackage->number_of_featured_ads ?? 0,
                        'remaining' => max(0, ($currentPackage->number_of_featured_ads ?? 0) - ($currentPackage->featured_used ?? 0))
                    ],
                    'hot_listings' => [
                        'used' => $currentPackage->hot_used ?? 0,
                        'allowed' => $currentPackage->number_of_hot_ads ?? 0,
                        'remaining' => max(0, ($currentPackage->number_of_hot_ads ?? 0) - ($currentPackage->hot_used ?? 0))
                    ],
                    'daily_refresh' => [
                        'used' => $currentPackage->refresh_used ?? 0,
                        'allowed' => $currentPackage->daily_refresh_limit ?? 0,
                        'remaining' => max(0, ($currentPackage->daily_refresh_limit ?? 0) - ($currentPackage->refresh_used ?? 0))
                    ]
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => [
                        'active_packages' => $activePackages,
                        'expired_packages' => $expiredPackages,
                        'total_packages' => $totalPackages
                    ],
                    'current_usage' => $currentUsage
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve packages summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a package
     */
    public function cancel($id)
    {
        try {
            $userId = Auth::user()->user_id;
            
            $userPackage = MwUserPackage::where('id', $id)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereNull('deleted_status')
                          ->orWhere('deleted_status', '0');
                })
                ->first();

            if (!$userPackage) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package not found, already cancelled, or access denied'
                ], 404);
            }

            // Update package status to cancelled
            $userPackage->status = 'cancelled';
            $userPackage->deleted_status = '1';
            $userPackage->save();

            return response()->json([
                'success' => true,
                'message' => 'Package cancelled successfully',
                'data' => new UserPackageResource($userPackage)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel package',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}