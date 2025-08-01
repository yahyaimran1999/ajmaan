<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->category_id,
            'category_name' => $this->category_name,
            'category_name_ar' => $this->category_name_ar,
            'category_description' => $this->category_description,
            'category_image' => $this->category_image,
            'date_added' => $this->date_added,
            'last_updated' => $this->last_updated,
            'category_order' => $this->category_order,
            'status' => $this->status,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'image_resize' => $this->image_resize,
            'url' => $this->url,
            'parent' => $this->parent,
            'section' => new MwSectionResource($this->whenLoaded('mw_section')),
        ];
    }
}
