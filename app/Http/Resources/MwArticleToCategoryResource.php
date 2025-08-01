<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwArticleToCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
                'id' => $this->mw_article_category->category_id ?? null,
                'name' => $this->mw_article_category->name ?? null,
                'description' => $this->mw_article_category->description ?? null,
                'slug' => $this->mw_article_category->slug ?? null,
                'status' => $this->mw_article_category->status ?? null,

        ];
    }
}
