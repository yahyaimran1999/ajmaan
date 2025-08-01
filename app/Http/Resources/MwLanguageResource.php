<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwLanguageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'language_id' => $this->language_id,
            'language_name' => $this->name,
        ];
    }
}
