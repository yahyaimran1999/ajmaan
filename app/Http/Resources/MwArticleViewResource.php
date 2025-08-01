<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwArticleViewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'article_id' => $this->article_id,
            'ip_address' => $this->ip_address,
            'date_added' => $this->date_added,
        ];
    }
}
