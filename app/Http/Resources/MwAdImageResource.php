<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'image_name' => $this->image_name,
            'image_title' => $this->image_title,
            'image_alt' => $this->image_alt,
            'image_order' => $this->image_order,
            'status' => $this->status,
            'date_added' => $this->date_added,
            'last_updated' => $this->last_updated,
            'image_type' => $this->image_type,
            'resize_image' => $this->resize_image,
        ];
    }
}
