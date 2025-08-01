<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwListingUserCompanyResource extends JsonResource
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
        ];
    }
}
