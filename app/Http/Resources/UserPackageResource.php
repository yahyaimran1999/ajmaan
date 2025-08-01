<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\MwPackageNew;

class UserPackageResource extends JsonResource
{
    public function toArray($request)
    {
        $packageName = $this->getPackageName();
        $planType = $this->getPlanType();
        $expiryDate = $this->expiry_date ? Carbon::parse($this->expiry_date)->format('M d, Y') : 'N/A';
        $startDate = $this->date_added ? Carbon::parse($this->date_added)->format('d/m/Y') : 'N/A';

        $data = [
            'id' => $this->id,
            'date' => $startDate,
            'plan' => $packageName,
            'plan_type' => $planType,
            'plan_description' => $this->getValidityDescription(),
            'expiry_date' => $expiryDate,
            'status' => $this->package_status ?? ($this->status === 'active' ? 'Active' : 'Expired'),
            'is_current' => isset($this->latest) && $this->latest ? true : false,
            'usage_summary' => [
                'total_listings' => [
                    'used' => $this->used_ad ?? 0,
                    'allowed' => $this->ads_allowed ?? 0,
                    'remaining' => max(0, ($this->ads_allowed ?? 0) - ($this->used_ad ?? 0)),
                    'emoji' => '📌'
                ],
                'featured_listings' => [
                    'used' => $this->featured_used ?? 0,
                    'allowed' => $this->number_of_featured_ads ?? 0,
                    'remaining' => max(0, ($this->number_of_featured_ads ?? 0) - ($this->featured_used ?? 0)),
                    'emoji' => '⭐'
                ],
                'hot_listings' => [
                    'used' => $this->hot_used ?? 0,
                    'allowed' => $this->number_of_hot_ads ?? 0,
                    'remaining' => max(0, ($this->number_of_hot_ads ?? 0) - ($this->hot_used ?? 0)),
                    'emoji' => '🔥'
                ]
            ]
        ];

        if ($this->detailed) {
            $data['detailed_features'] = $this->getDetailedFeatures();
            $data['payment_info'] = [
                'amount_paid' => $this->amount ?? 0,
                'currency' => 'AED'
            ];
        }

        return $data;
    }

    /**
     * Get package name (prioritize parent package name)
     */
    private function getPackageName()
    {
        if ($this->mw_package_new && $this->mw_package_new->parent_id) {
            $parentPackage = MwPackageNew::find($this->mw_package_new->parent_id);
            return $parentPackage ? $parentPackage->package_name : ($this->mw_package_new->package_name ?? 'Unknown Plan');
        }
        
        return $this->mw_package_new->package_name ?? 'Unknown Plan';
    }

    /**
     * Get plan type classification
     */
    private function getPlanType()
    {
        if (!$this->mw_package_new) {
            return 'Basic';
        }

        $pkg = $this->mw_package_new;
        $parentPkg = null;
        
        if ($pkg->parent_id) {
            $parentPkg = MwPackageNew::find($pkg->parent_id);
        }
        
        $checkPackage = $parentPkg ?: $pkg;
        
        // Count premium features
        $premiumFeatures = 0;
        
        if (!empty($checkPackage->verified_badge)) $premiumFeatures += 2;
        if (!empty($checkPackage->analytics)) $premiumFeatures += 2;
        if (!empty($checkPackage->agent_featured)) $premiumFeatures += 3;
        if (!empty($checkPackage->top_agency)) $premiumFeatures += 4;
        
        if (!empty($checkPackage->number_of_featured_ads)) {
            if ($checkPackage->number_of_featured_ads >= 10) $premiumFeatures += 3;
            elseif ($checkPackage->number_of_featured_ads >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }
        
        if (!empty($checkPackage->number_of_hot_ads)) {
            if ($checkPackage->number_of_hot_ads >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }
        
        if (!empty($checkPackage->daily_refresh_limit)) {
            if ($checkPackage->daily_refresh_limit >= 5) $premiumFeatures += 2;
            else $premiumFeatures += 1;
        }
        
        if (!empty($checkPackage->video_link)) $premiumFeatures += 1;
        if (!empty($checkPackage->whatsapp)) $premiumFeatures += 1;
        if (!empty($checkPackage->email)) $premiumFeatures += 1;
        
        if ($premiumFeatures >= 12) {
            return 'MAX Plan';
        } elseif ($premiumFeatures >= 7) {
            return 'Pro Plan';
        } else {
            return 'Basic Plan';
        }
    }

    /**
     * Get validity description
     */
    private function getValidityDescription()
    {
        if ($this->validity == 0) {
            return 'Unlimited Validity';
        }
        
        $validityOptions = [
            30 => '1 Month Validity',
            90 => '3 Months Validity',
            180 => '6 Months Validity',
            365 => '12 Months Validity',
        ];
        
        return $validityOptions[$this->validity] ?? $this->validity . ' Days Validity';
    }

    /**
     * Get detailed features for show method
     */
    private function getDetailedFeatures()
    {
        $features = [];
        
        if ($this->number_of_images) {
            $features[] = $this->number_of_images . ' Images per listing';
        }
        
        if ($this->number_of_agents) {
            $features[] = $this->number_of_agents . ' Agent profiles';
        }
        
        if ($this->daily_refresh_limit) {
            $features[] = $this->daily_refresh_limit . ' Daily refreshes';
        }
        
        if ($this->analytics) {
            $features[] = 'Analytics & Reporting';
        }
        
        if ($this->whatsapp) {
            $features[] = 'WhatsApp Integration';
        }
        
        if ($this->email) {
            $features[] = 'Email Integration';
        }
        
        return $features;
    }
}