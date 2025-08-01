<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\MwSection;
use Illuminate\Http\Resources\Json\JsonResource;

class MwListPlaceAnAdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ref_number' => 'AP-'. $this->id,
            'category_id' => $this->category_id,
            'ad_title' => $this->ad_title,
            'ad_title_ar' => $this->ad_title_ar,
            'price' => $this->price,
            'bathrooms' => $this->bathrooms,
            'bedrooms' => $this->bedrooms,
            'car_parking' => $this->car_parking,
            'balconies' => $this->balconies,
            'builtup_area' => $this->builtup_area,
            'plot_area' => $this->plot_area,
            'date_added' => Carbon::parse($this->date_added)->format('Y-m-d'),
            'section' => $this->mw_section->section_name ?? null,
            'country' => $this->mw_country->country_name ?? null,
            'state' => $this->mw_state->state_name ?? null,
            'city' => $this->mw_city->city_name ?? null,
            'district' => $this->mw_district->district_name ?? null,
            'favourite' => false,
            // 'developer' => $this->mw_developer->developer_name ?? null,
            'agency' => new MwListingUserAgencyResource($this->whenLoaded('mw_listing_user')),
            'images' => MwAdImageResource::collection($this->whenLoaded('mw_ad_images')),

        ];
    }
}
