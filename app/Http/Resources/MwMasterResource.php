<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MwMasterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'master_id' => $this->master_id,
            'name' => $this->name,
            'f_type' => $this->f_type,
            'last_updated' => Carbon::parse($this->last_updated)->format('Y-m-d'),
            'priority' => $this->priority,
            'is_trash' => $this->is_trash,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'file_image' => $this->file_image,
            'section_id' => $this->section_id,
            'url' => $this->url,
            'country_id' => $this->country_id,
            'show_all' => $this->show_all,
            'show_all_c' => $this->show_all_c,
            'use_under' => $this->use_under,
            'p_master_id' => $this->p_master_id,
            'short_description' => $this->short_description,
            'category' => new MwCategoryResource($this->whenLoaded('mw_category')),
            'section' => new MwSectionResource($this->whenLoaded('mw_section')),
            'country' => new MwCountryResource($this->whenLoaded('mw_country')),
            'parent_master' => new MwMasterResource($this->whenLoaded('mw_master')),
            'area_guides' => MwAreaGuideResource::collection($this->whenLoaded('mw_area_guides')),
            'sub_masters' => MwMasterResource::collection($this->whenLoaded('mw_masters')),
        ];
    }
}
