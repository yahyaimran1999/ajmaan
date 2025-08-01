<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwAdFavouriteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id' => $this->user_id,
            'ad_id' => $this->ad_id,
            'post' => new MwListPlaceAnAdResource(
                $this->whenLoaded('mw_place_an_ad', function () {
                    return $this->mw_place_an_ad->load('mw_ad_images');
                })
            ),
        ];
    }
}
