<?php

namespace App\Http\Resources;

use App\Models\MwPackageNew;
use Illuminate\Http\Resources\Json\JsonResource;

class PricePlanOrderListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_id' => $this->order_id,
            'order_uid' => $this->order_uid,
            'package_name' => $this->getPackageDisplayName(),
            'total' =>  number_format($this->total, 2),
            'status' => $this->status,
            'currency' => $this->mw_currency ? $this->mw_currency->code : null,
            'date_added' => $this->date_added?->format('Y-m-d H:i:s'),
            
        ];
    }

    private function getPackageDisplayName()
    {
        if (!$this->mw_package_new) {
            return null;
        }

        if ($this->mw_package_new->parent_id) {
            $parentPackage = MwPackageNew::find($this->mw_package_new->parent_id);
            return $parentPackage ? $parentPackage->package_name : $this->mw_package_new->package_name;
        }

        return $this->mw_package_new->package_name;
    }
    
}
