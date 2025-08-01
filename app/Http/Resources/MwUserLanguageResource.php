<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwUserLanguageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'language_id' => $this->language_id,
            'language_name' => $this->mw_language->name ?? null,
        ];
    }
}
