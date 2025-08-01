<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwCityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'city_id' => $this->city_id,
            'city_name' => $this->city_name,
            'city_name_ar' => $this->city_name_ar,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
            'status' => $this->status,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'date_added' => $this->date_added,
            'last_updated' => $this->last_updated,
            'state' => new MwStateResource($this->whenLoaded('mw_state')),
            'country' => new MwCountryResource($this->whenLoaded('mw_country'))
        ];
    }
}
