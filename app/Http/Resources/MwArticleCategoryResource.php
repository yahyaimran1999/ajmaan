<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MwArticleCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'category_id' => $this->category_id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'file_image' => $this->file_image,
            'channel' => $this->channel,
            'o_description' => $this->o_description,
            'date_added' => Carbon::parse($this->date_added)->format('Y-m-d'),
            'sub_categories' => self::collection($this->whenLoaded('mw_article_categories')),
        ];
    }
}
