<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MwAgentReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'review_id' => $this->review_id,
            'agent_id' => $this->agent_id,
            'rating' => $this->rating,
            'review' => $this->review,
            'reviewer_name' => $this->name,
            'reviewer_email' => $this->email,
            'reviewer_phone' => $this->phone,
            'property_type' => $this->property_type,
            'location' => $this->location,
            'when_interact' => $this->getWhenInteractText(),
            'date_added' => $this->date_added?->format('Y-m-d H:i:s'),
            'last_updated' => $this->last_updated?->format('Y-m-d H:i:s'),
            'sect' => $this->getSectText(),
            'property_link' => $this->property_link,
            'status' => $this->status,
            'status' => $this->getStatusText(),
            'user_id' => $this->user_id,
            'agent' => $this->whenLoaded('mw_listing_user_agent', function () {
                return [
                    'id' => $this->mw_listing_user_agent->user_id,
                    'name' => $this->mw_listing_user_agent->first_name . ' ' . $this->mw_listing_user_agent->last_name,
                    'email' => $this->mw_listing_user_agent->email,
                    'phone' => $this->mw_listing_user_agent->phone,
                    'image' => $this->mw_listing_user_agent->image,
                    'company_name' => $this->mw_listing_user_agent->company_name,
                ];
            }),
            'reviewer' => $this->whenLoaded('mw_listing_user', function () {
                return [
                    'id' => $this->mw_listing_user->user_id,
                    'name' => $this->mw_listing_user->first_name . ' ' . $this->mw_listing_user->last_name,
                    'email' => $this->mw_listing_user->email,
                ];
            }),
        ];
    }

    private function getStatusText(): string
    {
        return match($this->status) {
            'A' => 'Approved',
            'R' => 'Rejected',
            'W' => 'Waiting',
            default => 'Unknown'
        };
    }
    private function getWhenInteractText(): string
    {
        return match($this->when_interact) {
            '1' => 'Last Week',
		    '2' => 'Last 2 Weeks',
		    '3' =>  'Last Month',
            default => 'Unknown'
        };
    }
    private function getSectText(): string
    {
        return match($this->sect) {
            '1' => 'Buying',
		    '2' => 'Renting',
            default => 'Unknown'
        };
    }
}
