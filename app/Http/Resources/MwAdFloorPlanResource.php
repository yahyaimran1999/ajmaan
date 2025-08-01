<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdFloorPlanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'floor_id' => $this->floor_id,
            'ad_id' => $this->ad_id,
            'floor_title' => $this->floor_title,
            'floor_file' => $this->floor_file,
        ];
    }
}
