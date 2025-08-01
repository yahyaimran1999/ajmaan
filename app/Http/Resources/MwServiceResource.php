<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'service_id' => $this->service_id,
            'service_name' => $this->service_name,
        ];
    }
}
