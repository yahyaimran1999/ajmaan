<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\MwPackageController;

class MwPackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'package_id' => $this->package_id,
            'package_name' => $this->package_name,
            'price_per_month' => (float)$this->price_per_month,
            'price_display' => 'AED ' . number_format($this->price_per_month, 2),
            'validity_in_days' => (int)$this->validity_in_days,
            'validity_display' => $this->getValidityDisplay(),
            'max_listing_per_day' => $this->max_listing_per_day ?: 'Unlimited',
            'number_of_agents' => $this->number_of_agents ?: 'Unlimited',
            'number_of_images' => $this->number_of_images,
            'category' => $this->category,
            'category_title' => $this->getCategoryTitle(),
            'package_class' => $this->package_class,
            'package_class_name' => $this->getPackageClassName(),
            'features' => (new MwPackageController())->getPackageFeatures($this->resource),
            'parent_id' => $this->parent_id ?? null,
            'f_type' => $this->f_type,
            'status' => $this->status
        ];
    }

    /**
     * Get validity display text
     */
    private function getValidityDisplay()
    {
        $validityOptions = [
            // '0' => 'Unlimited',
            '30' => '1 Month',
            // '90' => '3 Months',
            '180' => '6 Months',
            '365' => '12 Months',
        ];

        return $validityOptions[(string)$this->validity_in_days] ?? $this->validity_in_days . ' days';
    }

    /**
     * Get category title
     */
    private function getCategoryTitle()
    {
        $categories = [
            '1' => 'Normal Listing',
            '3' => 'Featured Ad Package',
            '4' => 'Refresh Quota',
        ];

        return $categories[$this->category] ?? '';
    }

    /**
     * Get package class name
     */
    private function getPackageClassName()
    {
        return $this->package_class == '1' ? 'Premium' : 'Standard';
    }
}
