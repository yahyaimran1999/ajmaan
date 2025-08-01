<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwSectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->section_id,
            'section_name' => $this->section_name,
            'section_name_ar' => $this->section_name_ar,
            'section_description' => $this->section_description,
            'section_image' => $this->section_image,
            'date_added' => $this->date_added,
            'last_updated' => $this->last_updated,
            'section_order' => $this->section_order,
            'status' => $this->status,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
        ];
    }
}
