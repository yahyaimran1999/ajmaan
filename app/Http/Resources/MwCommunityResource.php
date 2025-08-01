<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwCommunityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'slug' => $this->slug,
            'status' => $this->status,
            'city_id' => $this->city_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
