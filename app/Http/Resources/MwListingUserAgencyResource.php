<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MwPlaceAnAd;
use App\Models\MwListingUser;

class MwListingUserAgencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'address' => $this->address,
            'designation' => $this->mw_service->service_name ?? '',
            'company_logo' => $this->company_logo,
            'max_no_users' => $this->max_no_users,
            'a_description' => $this->a_description,
            'total_reviews' => $this->total_reviews,
            'total_sale_count' => $this->getTotalSaleCount(),
            'total_rent_count' => $this->getTotalRentCount(),
            'total_agents_count' => $this->getTotalAgentsCount(),
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

    private function getTotalAgentsCount(): int
    {
        return MwListingUser::where('parent_user', $this->user_id)
            ->where('user_type', 'A')
            ->where('isTrash', 0)
            ->count();
    }
}


