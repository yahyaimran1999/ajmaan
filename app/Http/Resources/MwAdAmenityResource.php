<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdAmenityResource extends JsonResource
{
    public function toArray($request): array
    {
        $amenity = $this->mw_amenity;
        return [
            'id' => $amenity->amenities_id,
            'name' => $amenity->amenities_name,
            'title' => $amenity->Title,
            'status' => $amenity->status,
            'priority' => $amenity->priority,
        ];
    }
}
