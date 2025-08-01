<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwGuideFaqResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->faq_id,
            'title' => $this->title,
            'file' => $this->file,
        ];
    }
}
