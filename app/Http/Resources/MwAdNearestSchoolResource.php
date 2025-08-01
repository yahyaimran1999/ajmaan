<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdNearestSchoolResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'school_name' => $this->name,
            'distance' => $this->distance,
            'vicinity' => $this->vicinity,
            'rating' => $this->rating,
            'user_ratings_total' => $this->user_ratings_total,
            'f_type' => $this->f_type,
            'status' => $this->status,
        ];
    }
}
