<?php

namespace App\Http\Resources;

use App\Models\MwPackageNew;
use Illuminate\Http\Resources\Json\JsonResource;

class PricePlanOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_id' => $this->order_id,
            'order_uid' => $this->order_uid,
            'plan_id' => $this->plan_id,
            'currency' => $this->mw_currency ? $this->mw_currency->code : null,
            'subtotal' => $this->subtotal,
            'tax_percent' => (float)$this->tax_percent,
            'tax_value' => $this->tax_value,
            'discount' => $this->discount,
            'total' => $this->total,
            'status' => $this->status,
            'payment_type' => self::paymentArray($this->payment_type),
            'date_added' => $this->date_added?->format('Y-m-d H:i:s'),
            'last_updated' => $this->last_updated?->format('Y-m-d H:i:s'),
            'created_by' => $this->created_by,
            'deleted_by' => $this->deleted_by,
            'deleted_status' => $this->deleted_status,
            'u_contract' => $this->u_contract,
            'p_category' => $this->p_category,
            'free_package' => $this->free_package,

            // 'json_data' => $this->json_data ? json_decode($this->json_data, true) : null,

            'agency' => new MwListingUserAgencyResource($this->whenLoaded('mw_listing_user')),
            'package' => $this->getPackageDetails(),
            'tax' => new MwTaxResource($this->whenLoaded('mw_tax')),
            'promo_code' => new MwPricePlanPromoCodeResource($this->whenLoaded('mw_price_plan_promo_code')),
            'advertisement' => new MwPlaceAnAdResource($this->whenLoaded('mw_place_an_ad')),
        ];
    }

    private function paymentArray($type)
    {
        return match($type) {
            't' => 'Credit Card',
            'b' => 'Bank transfer/Cash',
            default => 'Unknown'
        };
    }

    private function getPackageDetails()
    {
        if (!$this->mw_package_new) {
            return null;
        }

        $package = $this->mw_package_new;
        $parentPackage = null;

        if ($package->parent_id) {
            $parentPackage = MwPackageNew::find($package->parent_id);
        }

        $featurePackage = $parentPackage ?: $package;

        return [
            'package_name' => $parentPackage ? $parentPackage->package_name : $package->package_name,
            'validity_in_days' => (float)$package->validity_in_days,
            'features' => $this->getPackageFeatures($featurePackage),
        ];
    }

    
    private function getPackageFeatures($package)
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
        } else if (!empty($package->analytics)) {
            $features[] = 'Analytics & reporting';
        }

        if (!empty($package->email_phone_text)) {
            $features[] = $package->email_phone_text;
        }

        if (!empty($package->agent_featured_text)) {
            $features[] = $package->agent_featured_text;
        }

        if (!empty($package->boost_text)) {
            $features[] = $package->boost_text;
        }

        if (!empty($package->spnsored_ad_text)) {
            $features[] = $package->spnsored_ad_text;
        }

        return array_values(array_filter($features, function($feature) {
            return !is_null($feature) && trim($feature) !== '';
        }));
    }
}
