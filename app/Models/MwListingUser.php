<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
// use Laravel\Cashier\Billable;

/**
 * Class MwListingUser
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $city
 * @property string $state
 * @property int|null $country_id
 * @property int|null $state_id
 * @property string $zip
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $password
 * @property int $isTrash
 * @property string $status
 * @property Carbon $date_added
 * @property string $send_me
 * @property string $verification_code
 * @property string $reset_key
 * @property Carbon|null $dob
 * @property string $calls_me
 * @property int $country
 * @property int $education_level
 * @property int $position_level
 * @property string $updates
 * @property string $advertisement
 * @property string $cover_letter
 * @property string $image
 * @property string $xml_inserted
 * @property string $xml_image
 * @property string $mobile
 * @property string $user_type
 * @property string $featured
 * @property int|null $priority
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $licence_no
 * @property Carbon|null $licence_no_expiry
 * @property string|null $broker_no
 * @property string $email_verified
 * @property string|null $timezone
 * @property string $admin_approved
 * @property string|null $website
 * @property string $filled_info
 * @property string|null $registered_via
 * @property string|null $contact_person
 * @property string|null $contact_email
 * @property string|null $facebook
 * @property string|null $twiter
 * @property string|null $google
 * @property string|null $company_name
 * @property int|null $designation_id
 * @property string|null $company_logo
 * @property int|null $max_no_users
 * @property int|null $parent_user
 * @property string|null $user_status
 * @property string|null $rera
 * @property string|null $a_description
 * @property string|null $a_r_n
 * @property string|null $r_a
 * @property string|null $r_n
 * @property string|null $o_r_a
 * @property string|null $o_r_n
 * @property float|null $avg_r
 * @property string $total_reviews
 * @property string|null $whatsapp
 * @property string|null $passport
 * @property string|null $visa
 * @property string|null $signature
 * @property string|null $s_code
 * @property Carbon|null $o_send_at
 * @property Carbon|null $v_send_at
 * @property string|null $otp_login
 * @property string $verified
 * @property string|null $slug_name
 * @property float|null $amount
 * @property string|null $upload_arra_id
 * @property string|null $eid_number
 * @property Carbon|null $eid_expiry_date
 * @property string|null $upload_eid
 * @property string|null $social_urls
 * @property string $documents_submitted
 * @property string|null $trade_license_number
 * @property Carbon|null $trade_license_expiry
 * @property int|null $no_of_employees
 * @property string|null $arra_number
 * @property Carbon|null $arra_date
 * @property string|null $arra_doc
 * @property string|null $company_email
 * @property string|null $prime_user
 * @property int|null $otp_attempts
 * @property Carbon|null $otp_cooldown_at
 * @property string|null $login_token
 * @property int|null $otp_forget_pass
 *
 * @property MwCountry|null $mw_country
 * @property MwState|null $mw_state
 * @property MwService|null $mw_service
 * @property MwListingUser|null $mw_listing_user
 * @property Collection|MwAdFavourite[] $mw_ad_favourites
 * @property Collection|MwAgentReview[] $mw_agent_reviews
 * @property Collection|MwBlogComment[] $mw_blog_comments
 * @property Collection|MwContactRead[] $mw_contact_reads
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwCustomerActionLog[] $mw_customer_action_logs
 * @property Collection|MwEmail[] $mw_emails
 * @property Collection|MwFreebitesInformation[] $mw_freebites_informations
 * @property Collection|MwListingUserMoreCountry[] $mw_listing_user_more_countries
 * @property Collection|MwListingUserMoreState[] $mw_listing_user_more_states
 * @property Collection|MwListingUser[] $mw_listing_users
 * @property Collection|MwListingUsersFeatured[] $mw_listing_users_featureds
 * @property Collection|MwListingUsersStateFeatured[] $mw_listing_users_state_featureds
 * @property Collection|MwLoanApplication[] $mw_loan_applications
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwPricePlanOrder[] $mw_price_plan_orders
 * @property Collection|MwPricePlanOrderNote[] $mw_price_plan_order_notes
 * @property Collection|MwSaveSearch[] $mw_save_searches
 * @property Collection|MwStatisticSearchList[] $mw_statistic_search_lists
 * @property Collection|MwStatisticsPage[] $mw_statistics_pages
 * @property Collection|MwUserLanguage[] $mw_user_languages
 * @property Collection|MwUserMainCategory[] $mw_user_main_categories
 * @property Collection|MwUserPackage[] $mw_user_packages
 * @property Collection|MwUserSearch[] $mw_user_searches
 *
 * @package App\Models
 */
class MwListingUser extends Model
{

	// use Billable;
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_users';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'state_id' => 'int',
		'isTrash' => 'int',
		'date_added' => 'datetime',
		'dob' => 'datetime',
		'country' => 'int',
		'education_level' => 'int',
		'position_level' => 'int',
		'priority' => 'int',
		'licence_no_expiry' => 'datetime',
		'designation_id' => 'int',
		'max_no_users' => 'int',
		'parent_user' => 'int',
		'avg_r' => 'float',
		'o_send_at' => 'datetime',
		'v_send_at' => 'datetime',
		'amount' => 'float',
		'eid_expiry_date' => 'datetime',
		'trade_license_expiry' => 'datetime',
		'no_of_employees' => 'int',
		'arra_date' => 'datetime',
		'otp_attempts' => 'int',
		'otp_cooldown_at' => 'datetime',
		'otp_forget_pass' => 'int'
	];

	protected $hidden = [
		'password',
		'login_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'address',
		'city',
		'state',
		'country_id',
		'state_id',
		'zip',
		'phone',
		'fax',
		'email',
		'password',
		'isTrash',
		'status',
		'date_added',
		'send_me',
		'verification_code',
		'reset_key',
		'dob',
		'calls_me',
		'country',
		'education_level',
		'position_level',
		'updates',
		'advertisement',
		'cover_letter',
		'image',
		'xml_inserted',
		'xml_image',
		'mobile',
		'user_type',
		'featured',
		'priority',
		'slug',
		'description',
		'licence_no',
		'licence_no_expiry',
		'broker_no',
		'email_verified',
		'timezone',
		'admin_approved',
		'website',
		'filled_info',
		'registered_via',
		'contact_person',
		'contact_email',
		'facebook',
		'twiter',
		'google',
		'company_name',
		'designation_id',
		'company_logo',
		'max_no_users',
		'parent_user',
		'user_status',
		'rera',
		'a_description',
		'a_r_n',
		'r_a',
		'r_n',
		'o_r_a',
		'o_r_n',
		'avg_r',
		'total_reviews',
		'whatsapp',
		'passport',
		'visa',
		'signature',
		's_code',
		'o_send_at',
		'v_send_at',
		'otp_login',
		'verified',
		'slug_name',
		'amount',
		'upload_arra_id',
		'eid_number',
		'eid_expiry_date',
		'upload_eid',
		'social_urls',
		'documents_submitted',
		'trade_license_number',
		'trade_license_expiry',
		'no_of_employees',
		'arra_number',
		'arra_date',
		'arra_doc',
		'company_email',
		'prime_user',
		'otp_attempts',
		'otp_cooldown_at',
		'login_token',
		'otp_forget_pass'
	];

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_service()
	{
		return $this->belongsTo(MwService::class, 'designation_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'parent_user');
	}

	public function mw_ad_favourites()
	{
		return $this->hasMany(MwAdFavourite::class, 'user_id');
	}

	public function mw_agent_reviews()
	{
		return $this->hasMany(MwAgentReview::class, 'user_id');
	}

	public function mw_blog_comments()
	{
		return $this->hasMany(MwBlogComment::class, 'user_id');
	}

	public function mw_contact_reads()
	{
		return $this->hasMany(MwContactRead::class, 'user_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'user_id');
	}

	public function mw_customer_action_logs()
	{
		return $this->hasMany(MwCustomerActionLog::class, 'customer_id');
	}

	public function mw_emails()
	{
		return $this->hasMany(MwEmail::class, 'created_by');
	}

	public function mw_freebites_informations()
	{
		return $this->hasMany(MwFreebitesInformation::class, 'user_id');
	}

	public function mw_listing_user_more_countries()
	{
		return $this->hasMany(MwListingUserMoreCountry::class, 'user_id');
	}

	public function mw_listing_user_more_states()
	{
		return $this->hasMany(MwListingUserMoreState::class, 'user_id');
	}

	public function mw_listing_users()
	{
		return $this->hasMany(MwListingUser::class, 'parent_user');
	}

	public function mw_listing_users_featureds()
	{
		return $this->hasMany(MwListingUsersFeatured::class, 'user_id');
	}

	public function mw_listing_users_state_featureds()
	{
		return $this->hasMany(MwListingUsersStateFeatured::class, 'user_id');
	}

	public function mw_loan_applications()
	{
		return $this->hasMany(MwLoanApplication::class, 'user_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'user_id');
	}

	public function mw_price_plan_orders()
	{
		return $this->hasMany(MwPricePlanOrder::class, 'customer_id');
	}

	public function mw_price_plan_order_notes()
	{
		return $this->hasMany(MwPricePlanOrderNote::class, 'customer_id');
	}

	public function mw_save_searches()
	{
		return $this->hasMany(MwSaveSearch::class, 'user_id');
	}

	public function mw_statistic_search_lists()
	{
		return $this->hasMany(MwStatisticSearchList::class, 'user_id');
	}

	public function mw_statistics_pages()
	{
		return $this->hasMany(MwStatisticsPage::class, 'user_id');
	}

	public function mw_user_languages()
	{
		return $this->hasMany(MwUserLanguage::class, 'user_id');
	}

	public function mw_user_main_categories()
	{
		return $this->hasMany(MwUserMainCategory::class, 'user_id');
	}

	public function mw_user_packages()
	{
		return $this->hasMany(MwUserPackage::class, 'user_id');
	}

	public function mw_user_searches()
	{
		return $this->hasMany(MwUserSearch::class, 'user_id');
	}
}
