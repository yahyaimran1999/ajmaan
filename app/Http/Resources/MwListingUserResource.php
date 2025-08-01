<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MwPlaceAnAd;
use App\Models\MwListingUser;

class MwListingUserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'zip' => $this->zip,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            // 'password' => $this->when(false, $this->password), // Hidden for security
            'isTrash' => $this->isTrash,
            'status' => $this->status,
            'date_added' => $this->date_added,
            'send_me' => $this->send_me,
            'verification_code' => $this->verification_code,
            'reset_key' => $this->reset_key,
            'dob' => $this->dob,
            'calls_me' => $this->calls_me,
            'country' => $this->country,
            'education_level' => $this->education_level,
            'position_level' => $this->position_level,
            'updates' => $this->updates,
            'advertisement' => $this->advertisement,
            'cover_letter' => $this->cover_letter,
            'image' => $this->image,
            'xml_inserted' => $this->xml_inserted,
            'xml_image' => $this->xml_image,
            'mobile' => $this->mobile,
            'user_type' => $this->user_type,
            'featured' => $this->featured,
            'priority' => $this->priority,
            'slug' => $this->slug,
            'description' => $this->description,
            'licence_no' => $this->licence_no,
            'licence_no_expiry' => $this->licence_no_expiry,
            'broker_no' => $this->broker_no,
            'email_verified' => $this->email_verified,
            'timezone' => $this->timezone,
            'admin_approved' => $this->admin_approved,
            'website' => $this->website,
            'filled_info' => $this->filled_info,
            'registered_via' => $this->registered_via,
            'contact_person' => $this->contact_person,
            'contact_email' => $this->contact_email,
            'facebook' => $this->facebook,
            'twiter' => $this->twiter,
            'google' => $this->google,
            'company_name' => $this->company_name,
            'designation_id' => $this->designation_id,
            'company_logo' => $this->company_logo,
            'max_no_users' => $this->max_no_users,
            'parent_user' => $this->parent_user,
            'user_status' => $this->user_status,
            'rera' => $this->rera,
            'a_description' => $this->a_description,
            'a_r_n' => $this->a_r_n,
            'r_a' => $this->r_a,
            'r_n' => $this->r_n,
            'o_r_a' => $this->o_r_a,
            'o_r_n' => $this->o_r_n,
            'avg_r' => $this->avg_r,
            'total_reviews' => $this->total_reviews,
            'whatsapp' => $this->whatsapp,
            'passport' => $this->passport,
            'visa' => $this->visa,
            'signature' => $this->signature,
            's_code' => $this->s_code,
            'o_send_at' => $this->o_send_at,
            'v_send_at' => $this->v_send_at,
            'otp_login' => $this->otp_login,
            'verified' => $this->verified,
            'slug_name' => $this->slug_name,
            'amount' => $this->amount,
            'upload_arra_id' => $this->upload_arra_id,
            'eid_number' => $this->eid_number,
            'eid_expiry_date' => $this->eid_expiry_date,
            'upload_eid' => $this->upload_eid,
            'social_urls' => $this->social_urls,
            'documents_submitted' => $this->documents_submitted,
            'trade_license_number' => $this->trade_license_number,
            'trade_license_expiry' => $this->trade_license_expiry,
            'no_of_employees' => $this->no_of_employees,
            'arra_number' => $this->arra_number,
            'arra_date' => $this->arra_date,
            'arra_doc' => $this->arra_doc,
            'company_email' => $this->company_email,
            'prime_user' => $this->prime_user,
            'otp_attempts' => $this->otp_attempts,
            'otp_cooldown_at' => $this->otp_cooldown_at,
            'login_token' => $this->when(false, $this->login_token), // Hidden for security
            'otp_forget_pass' => $this->otp_forget_pass,
            'total_sale_count' => $this->getTotalSaleCount(),
            'total_rent_count' => $this->getTotalRentCount(),
            'total_commercial_count' => $this->getTotalCommercialCount(),
            'total_sold_rented_count' => $this->getTotalSoldRentedCount(),
            'total_agent_count' => $this->getTotalAgentsCount(),
            'agency' => new MwListingUserAgencyResource($this->whenLoaded('mw_listing_user')),
            'service' => new MwServiceResource(
                $this->whenLoaded('mw_service')
            ),
            'language' => MwUserLanguageResource::collection(
                $this->whenLoaded('mw_user_languages')
            ),
            'specialization' => MwUserMainCategoryResource::collection(
                $this->whenLoaded('mw_user_main_categories')
            ),
            // 'agent' => MwListingUserAgentResource::collection($this->whenLoaded('mw_listing_users')),
            // 'post' => MwListPlaceAnAdResource::collection(
            //     $this->whenLoaded('mw_place_an_ads', function () {
            //         return $this->mw_place_an_ads->map(function ($post) {
            //             $post->load('mw_ad_images');
            //             return $post;
            //         });
            //     })
            // ),
        ];
    }

    private function getTotalSaleCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('section_id', 1)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalRentCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('section_id', 2)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalCommercialCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('listing_type', 120)
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }


    private function getTotalSoldRentedCount(): int
    {
        return MwPlaceAnAd::where('user_id', $this->user_id)
            ->where('s_r', '1')
            ->orWhere('s_r', '2')
            ->where('status', 'A')
            ->where('isTrash', '0')
            ->count();
    }

    private function getTotalAgentsCount(): int
    {
        return MwListingUser::where('parent_user', $this->user_id)
            ->where('user_type', 'A')
            ->where('isTrash', 0)
            ->count();
    }

    private function getUserTypeLabel(): string
    {
        return match($this->user_type) {
            'K' => 'Agency',
            'A' => 'Agent',
            'D' => 'Developer',
            'U' => 'User',
            default => 'Unknown'
        };
    }
}
