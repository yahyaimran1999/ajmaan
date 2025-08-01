<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwDeveloperResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->developer_id,
            'name' => $this->developer_name,
            'name_ar' => $this->developer_name_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'logo' => $this->logo,
            'status' => $this->status,
            'isTrash' => $this->isTrash,
            'sort_order' => $this->sort_order,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'date_added' => $this->date_added,
        ];
    }
}
