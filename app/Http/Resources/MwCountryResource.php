<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwCountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'country_id' => $this->country_id,
            'country_name' => $this->country_name,
            'country_name_ar' => $this->country_name_ar,
            'iso' => $this->iso,
            'iso3' => $this->iso3,
            'phone_code' => $this->phone_code,
            'currency' => $this->currency,
            'currency_symbol' => $this->currency_symbol,
            'status' => $this->status,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'date_added' => $this->date_added,
            'last_updated' => $this->last_updated,
        ];
    }
}
