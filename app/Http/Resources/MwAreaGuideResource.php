<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MwAreaGuideResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'state_id' => $this->state_id,
            'location_id' => $this->location_id,
            'title' => $this->title,
            'highlight' => $this->highlight,
            'neighbor' => $this->neighbor,
            'life_style' => $this->life_style,
            'location' => $this->location,
            'banner' => $this->banner,
            'slug' => $this->slug,
            'contact' => $this->contact,
            'heading' => $this->heading,
            'latitude' => $this->location_latitude,
            'longitude' => $this->location_longitude,
            'area_location' => $this->area_location,
            'category_id' => $this->category_id,
            'property_type' => $this->property_type,
            'for_sale' => $this->for_sale,
            'for_rent' => $this->for_rent,
            'channel' => $this->channel,
            'title' => $this->meta_title,
            'description' => $this->meta_description,
            'date_added' => Carbon::parse($this->date_added)->format('Y-m-d'),
            'last_updated' => Carbon::parse($this->last_updated)->format('Y-m-d'),
            'state' => new MwStateResource($this->whenLoaded('mw_state')),
            'city' => new MwCityResource($this->whenLoaded('mw_city')),
            'category' => new MwCategoryResource($this->whenLoaded('mw_category')),
            'master' => new MwMasterResource($this->whenLoaded('mw_master')),
            'images' => MwGuideImageResource::collection($this->whenLoaded('mw_guide_images')),
            'faqs' => MwGuideFaqResource::collection($this->whenLoaded('mw_gide_faqs')),
            'contacts' => MwContactUResource::collection($this->whenLoaded('mw_contact_us')),
        ];
    }
}
