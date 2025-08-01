<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwPricePlanPromoCodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'promo_code_id' => $this->promo_code_id,
            'code' => $this->code,
            'promo_code' => $this->code,
            'type' => $this->type,
            'discount' => (float)$this->discount,
            'total_amount' => (float)$this->total_amount,
            'total_usage' => $this->total_usage,
            'customer_usage' => $this->customer_usage,
            'date_start' => $this->date_start?->format('Y-m-d H:i:s'),
            'date_end' => $this->date_end?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'date_added' => $this->date_added?->format('Y-m-d H:i:s'),
            'last_updated' => $this->last_updated?->format('Y-m-d H:i:s'),
            'assigned_to' => $this->assigned_to,
            'offer_title' => $this->offer_title,
            'listing_package_id' => $this->listing_package_id,
            'featured_package_id' => $this->featured_package_id,
            'is_active' => $this->isActive(),
            'is_expired' => $this->isExpired(),
        ];
    }
    
    /**
     * Get human-readable type display
     */
    private function getTypeDisplay()
    {
        $types = [
            'P' => 'Percentage',
            'F' => 'Fixed Amount',
        ];
        
        return $types[$this->type] ?? $this->type;
    }
    
    /**
     * Get formatted discount display
     */
    private function getDiscountDisplay()
    {
        if ($this->type === 'P') {
            return $this->discount . '%';
        } else {
            return 'AED ' . number_format($this->discount, 2);
        }
    }
    
    /**
     * Get human-readable status display
     */
    private function getStatusDisplay()
    {
        $statuses = [
            'A' => 'Active',
            'I' => 'Inactive',
            'D' => 'Deleted',
            'E' => 'Expired',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
    
    /**
     * Check if promo code is currently active
     */
    private function isActive()
    {
        $now = now();
        return $this->status === 'A' && 
               $this->date_start <= $now && 
               $this->date_end >= $now;
    }
    
    /**
     * Check if promo code is expired
     */
    private function isExpired()
    {
        return $this->date_end < now();
    }
}
