<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdPropertyTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ad_id' => $this->ad_id,
            'type_id' => $this->type_id,
            'bed' => $this->bed,
            'bath' => $this->bath,
            'title' => $this->title,
            'from_price' => $this->from_price,
            'to_price' => $this->to_price,
            'size' => $this->size,
            'size_to' => $this->size_to,
            'last_updated' => $this->last_updated,
            'area_unit' => $this->area_unit,
            'price_unit' => $this->price_unit,
            'description' => $this->description,
            'image' => $this->image,
            'category' => new MwCategoryResource($this->whenLoaded('mw_category'))
        ];
    }
}
