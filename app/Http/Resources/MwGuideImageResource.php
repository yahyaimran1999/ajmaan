<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwGuideImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'guide_id' => $this->guide_id,
            'image' => $this->image_name,
            'priority' => $this->priority,
            'status' => $this->status,
        ];
    }
}
