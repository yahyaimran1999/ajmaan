<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwUserMainCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'category_id' => $this->category_id,
            'category_name' => $this->category->category_name ?? null,
        ];
    }
}
