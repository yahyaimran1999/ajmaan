<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MwPlaceAnAd;
use App\Models\MwListingUser;

class MwListingUserAgentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'designation' => $this->designation,
            'image' => $this->image,
            'whatsapp' => $this->whatsapp,
            'description' => $this->description,
            'licence_no' => $this->licence_no,
            'avg_r' => $this->avg_r,
            'total_reviews' => $this->total_reviews,
            'total_sale_count' => $this->getTotalSaleCount(),
            'total_rent_count' => $this->getTotalRentCount(),
            'total_commercial_count' => $this->getTotalCommercialCount(),
            'total_sold_rented_count' => $this->getTotalSoldRentedCount(),
        ];
    }

     private function getTotalSaleCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('section_id', 1)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalRentCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('section_id', 2)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalCommercialCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('listing_type', 120)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalSoldRentedCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('s_r', '1')
            ->orWhere('s_r', '2')
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }
}
