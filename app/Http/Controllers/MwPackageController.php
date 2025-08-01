<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MwPackageNew;
use App\Http\Resources\MwPackageResource;

class MwPackageController extends Controller
{
    /**
     * Get all packages organized by validity and package type
     */
    public function index()
    {
        try {
            // Fetch main packages (parent packages)
            $mainPackages = MwPackageNew::where('isTrash', '0')
                ->where('status', 'A')
                ->where('f_type', 'N')
                ->orderBy('price_per_month', 'asc')
                ->get();

            // Fetch package validities
            $packageValidities = MwPackageNew::where('isTrash', '0')
                ->where('status', 'A')
                ->where('f_type', 'V')
                ->where('price_per_month', '>', 0)
                ->orderBy('price_per_month', 'asc')
                ->get();

            // Initialize the response structure
            $plans = [
                // 'unlimited' => [
                //     'Basic' => [],
                //     'Pro' => [],
                //     'MAX' => []
                // ],
                '1_month' => [
                    'Basic' => [],
                    'Pro' => [],
                    'MAX' => []
                ],
                // '3_months' => [
                //     'Basic' => [],
                //     'Pro' => [],
                //     'MAX' => []
                // ],
                '6_months' => [
                    'Basic' => [],
                    'Pro' => [],
                    'MAX' => []
                ],
                '12_months' => [
                    'Basic' => [],
                    'Pro' => [],
                    'MAX' => []
                ]
            ];

            // Process package validities
            foreach ($packageValidities as $validity) {
                $validityKey = $this->getValidityKey($validity->validity_in_days);
                $packageType = $this->getPackageType($validity);

                if ($validityKey && isset($plans[$validityKey][$packageType])) {
                    $plans[$validityKey][$packageType][] = [
                        'package_id' => $validity->package_id,
                        'package_name' => $this->getMainPackageName($validity->parent_id, $mainPackages),
                        'price_per_month' => (float)$validity->price_per_month,
                        'price_display' => 'AED ' . number_format($validity->price_per_month, 2),
                        'validity_in_days' => (int)$validity->validity_in_days,
                        'validity_display' => $this->getValidityDisplay($validity->validity_in_days),
                        'parent_id' => $validity->parent_id,
                        'features' => $this->getValidityFeatures($validity->parent_id, $mainPackages)
                    ];
                }
            }

            // Remove unlimited section processing since it's commented out
            // foreach ($mainPackages as $package) {
            //     $packageType = $this->getPackageType($package);
            //     $packageResource = new MwPackageResource($package);
            //     $plans['unlimited'][$packageType][] = $packageResource->toArray(request());
            // }

            return response()->json([
                'success' => true,
                'plans' => $plans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving packages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get validity key for grouping
     */
    private function getValidityKey($validityDays)
    {
        switch ((int)$validityDays) {
            // case 0:
            //     return 'unlimited';
            case 30:
                return '1_month';
            // case 90:
            //     return '3_months';
            case 180:
                return '6_months';
            case 365:
                return '12_months';
            default:
                return null;
        }
    }

    /**
     * Get package type based on package data
     */
    private function getPackageType($package)
    {
        // Get the main package to determine type
        $mainPackage = null;
        if (!empty($package->parent_id)) {
            // For validity packages, get the parent package
            $mainPackage = MwPackageNew::where('package_id', $package->parent_id)->first();
        } else {
            // For main packages, use the package directly
            $mainPackage = $package;
        }

        if (!$mainPackage) {
            return 'Basic'; // Default fallback
        }

        // Use PackageNew model's package_classname() logic
        // From PackageNew.php: package_class '1' returns 'pro'
        if (!empty($mainPackage->package_class) && $mainPackage->package_class == '1') {
            return 'Pro'; // Premium packages mapped to Pro
        }

        // Use systematic classification based on package features and structure
        return $this->classifyPackageByStructure($mainPackage);
    }

    /**
     * Classify package by structure and features from PackageNew model
     */
    private function classifyPackageByStructure($package)
    {
        // First, check package name for explicit classification
        $packageName = strtolower($package->package_name ?? '');
        if (strpos($packageName, 'basic') !== false) {
            return 'Basic';
        }
        if (strpos($packageName, 'pro') !== false) {
            return 'Pro';
        }
        if (strpos($packageName, 'max') !== false || strpos($packageName, 'premium') !== false) {
            return 'MAX';
        }

        // Count premium features with refined scoring
        $premiumFeatures = 0;

        // High-value features that indicate premium packages
        if (!empty($package->verified_badge)) $premiumFeatures += 1;
        if (!empty($package->analytics)) $premiumFeatures += 2;
        if (!empty($package->agent_featured)) $premiumFeatures += 3;
        if (!empty($package->top_agency)) $premiumFeatures += 4;

        // Featured ads scoring (more nuanced)
        if (!empty($package->number_of_featured_ads)) {
            if ($package->number_of_featured_ads >= 10) $premiumFeatures += 3;
            elseif ($package->number_of_featured_ads >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }

        // Hot ads scoring
        if (!empty($package->number_of_hot_ads)) {
            if ($package->number_of_hot_ads >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }

        // Daily refresh scoring
        if (!empty($package->daily_refresh_limit)) {
            if ($package->daily_refresh_limit >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }

        // Standard features (lower weight)
        if (!empty($package->video_link)) $premiumFeatures += 1;
        if (!empty($package->whatsapp)) $premiumFeatures += 1;
        if (!empty($package->email)) $premiumFeatures += 1;

        // Classify based on refined feature score
        if ($premiumFeatures >= 12) {
            return 'MAX';
        } elseif ($premiumFeatures >= 7) {
            return 'Pro';
        } else {
            return 'Basic';
        }
    }

    /**
     * Get validity display text
     */
    private function getValidityDisplay($validityDays)
    {
        $validityOptions = [
            // '0' => 'Unlimited',
            '30' => '1 Month',
            // '90' => '3 Months',
            '180' => '6 Months',
            '365' => '12 Months',
        ];

        return $validityOptions[(string)$validityDays] ?? $validityDays . ' days';
    }

    /**
     * Get main package name from parent_id
     */
    private function getMainPackageName($parentId, $mainPackages)
    {
        foreach ($mainPackages as $package) {
            if ($package->package_id == $parentId) {
                return $package->package_name;
            }
        }
        return '';
    }

    /**
     * Get package features array
     */
    public function getPackageFeatures($package)
    {
        $features = [];

        if (!empty($package->verified_badge)) {
            $features[] = 'Verified Badge';
        }

        if (!empty($package->video_link)) {
            $features[] = 'Video Link';
        }

        if (!empty($package->agent_featured)) {
            $features[] = 'Featured Agent Profile';
        }

        if (!empty($package->number_of_featured_ads)) {
            $features[] = $package->number_of_featured_ads . ' Featured Ads (' . $package->number_of_featured_ads_days . ' days each)';
        }

        if (!empty($package->number_of_hot_ads)) {
            $features[] = $package->number_of_hot_ads . ' Hot Ads (' . $package->number_of_hot_ads_days . ' days each)';
        }

        if (!empty($package->daily_refresh_limit)) {
            $features[] = $package->daily_refresh_limit . ' Daily Refreshes';
        }

        if (!empty($package->top_agency) && !empty($package->top_agency_text)) {
            $features[] = $package->top_agency_text;
        }

        if (!empty($package->priority_text)) {
            $features[] = $package->priority_text;
        }

        if (!empty($package->lead_text)) {
            $features[] = $package->lead_text;
        }

        if (!empty($package->analytics_text)) {
            $features[] = $package->analytics_text;
        } else if (!empty($package->analytics) && !empty($package->analyticsTitle)) {
            $features[] = $package->analyticsTitle . ' analytics & reporting';
        }

        if (!empty($package->email_phone_text)) {
            $features[] = $package->email_phone_text;
        }

        if(!empty($package->agent_featured_text) && !empty($package->agentfeaturedtitle)){
            $features[] = $package->agentfeaturedtitle;
        }

        if (!empty($package->boost_text)) {
            $features[] = $package->boost_text;
        }

        // Filter out null and empty values
        return array_filter($features, function($feature) {
            return !is_null($feature) && trim($feature) !== '';
        });
    }

    /**
     * Get features for validity packages from parent package
     */
    private function getValidityFeatures($parentId, $mainPackages)
    {
        foreach ($mainPackages as $package) {
            if ($package->package_id == $parentId) {
                return $this->getPackageFeatures($package);
            }
        }
        return [];
    }
}
