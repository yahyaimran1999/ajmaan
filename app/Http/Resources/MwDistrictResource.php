<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwDistrictResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->district_id,
            'name' => $this->district_name,
            'city' => new MwCityResource($this->whenLoaded('mw_city')),
        ];
    }
}
