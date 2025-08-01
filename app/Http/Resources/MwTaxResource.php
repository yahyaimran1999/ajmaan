<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwTaxResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'tax_id' => $this->tax_id,
            'country_id' => $this->country_id,
            'zone_id' => $this->zone_id,
            'name' => $this->name,
            'percent' => (float)$this->percent,
            'is_global' => $this->is_global === 'yes' ? 'Yes' : 'No',
            'status' => $this->status,
            'date_added' => $this->date_added?->format('Y-m-d H:i:s'),
            'last_updated' => $this->last_updated?->format('Y-m-d H:i:s'),
        ];
    }
}
