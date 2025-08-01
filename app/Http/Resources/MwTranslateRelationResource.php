<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwTranslateRelationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'relation_id' => $this->relation_id,
            'article_id' => $this->article_id,
            'language_id' => $this->language_id,
            'translated_id' => $this->translated_id,
        ];
    }
}
