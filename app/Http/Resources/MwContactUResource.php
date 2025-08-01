<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MwContactUResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'email' => $this->email,
            'name' => $this->name,
            'message' => $this->meassage,
            'city' => $this->city,
            'phone' => $this->phone,
            'date' => $this->date,
            'url' => $this->url,
            'contact_type' => $this->contact_type,
            'w_talk' => $this->w_talk,
            'considering' => $this->considering,
            'is_read' => $this->is_read,
            'i_am' => $this->i_am,
            'channel' => $this->channel,
            'created_at' => $this->date_added,
            
            'ad' => $this->when($this->mw_place_an_ad, [
                'id' => $this->mw_place_an_ad?->id,
                'title' => $this->mw_place_an_ad?->ad_title,
                'price' => $this->mw_place_an_ad?->price,
                'image' => $this->mw_place_an_ad?->mw_ad_images?->where('isTrash', '0')->where('status', 'A')->first()?->image_name,
                
                'user' => $this->when($this->mw_place_an_ad?->mw_listing_user, [
                    'id' => $this->mw_place_an_ad?->mw_listing_user?->user_id,
                    'name' => trim(($this->mw_place_an_ad?->mw_listing_user?->first_name ?? '') . ' ' . ($this->mw_place_an_ad?->mw_listing_user?->last_name ?? '')),
                    'image' => $this->mw_place_an_ad?->mw_listing_user?->image,
                ])
            ])
        ];
    }
}
