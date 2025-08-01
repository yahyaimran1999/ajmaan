<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MwPackageNew
 *
 * @property int $package_id
 * @property string $package_name
 * @property float $price_per_month
 * @property int $validity_in_days
 * @property int $max_listing_per_day
 * @property string $visitors_can_directly
 * @property string $create_profile_picture
 * @property string $statistics
 * @property string $logo
 * @property string $featured
 * @property Carbon $added_date
 * @property string $isTrash
 * @property string $status
 * @property string|null $category
 * @property int|null $listing_package_id
 * @property int|null $featured_package_id
 * @property string|null $offer_title
 * @property string $bundle_only
 * @property string $f_type
 * @property int|null $cat_id1
 * @property int|null $cat_id2
 * @property int|null $cat_id3
 * @property int|null $cat_id4
 * @property int|null $cat_id5
 * @property int|null $cat_id6
 * @property string|null $is_read
 * @property string $backend_only
 * @property string $is_new
 * @property int|null $number_of_agents
 * @property int|null $number_of_images
 * @property int|null $number_of_featured_ads
 * @property int|null $number_of_featured_ads_days
 * @property int|null $number_of_hot_ads
 * @property int|null $number_of_hot_ads_days
 * @property int|null $daily_refresh_limit
 * @property int|null $verified_badge
 * @property string|null $analytics
 * @property string|null $email
 * @property string|null $whatsapp
 * @property string|null $video_link
 * @property string|null $agent_featured
 * @property string|null $package_class
 * @property string|null $recomanded_title
 * @property int|null $parent_id
 * @property string|null $email_phone_text
 * @property string|null $agent_featured_text
 * @property string|null $priority_text
 * @property string|null $lead_text
 * @property string|null $boost_text
 * @property string $top_agency
 * @property string $top_agency_text
 * @property string|null $analytics_text
 * @property int|null $number_of_spnsored_ad
 * @property string|null $spnsored_ad_text
 *
 * @property MwPackageNew|null $mw_package_new
 * @property Collection|MwPackageNew[] $mw_package_news
 * @property Collection|MwPricePlanOrder[] $mw_price_plan_orders
 * @property Collection|MwUserPackage[] $mw_user_packages
 *
 * @package App\Models
 */
class MwPackageNew extends Model
{
    use HasFactory;

    protected $connection = 'mysql_legacy';
	protected $table = 'mw_package_new';
	protected $primaryKey = 'package_id';
	public $timestamps = false;

	protected $casts = [
		'price_per_month' => 'float',
		'validity_in_days' => 'int',
		'max_listing_per_day' => 'int',
		'added_date' => 'datetime',
		'listing_package_id' => 'int',
		'featured_package_id' => 'int',
		'cat_id1' => 'int',
		'cat_id2' => 'int',
		'cat_id3' => 'int',
		'cat_id4' => 'int',
		'cat_id5' => 'int',
		'cat_id6' => 'int',
		'number_of_agents' => 'int',
		'number_of_images' => 'int',
		'number_of_featured_ads' => 'int',
		'number_of_featured_ads_days' => 'int',
		'number_of_hot_ads' => 'int',
		'number_of_hot_ads_days' => 'int',
		'daily_refresh_limit' => 'int',
		'verified_badge' => 'int',
		'parent_id' => 'int',
		'number_of_spnsored_ad' => 'int'
	];

	protected $fillable = [
		'package_name',
		'price_per_month',
		'validity_in_days',
		'max_listing_per_day',
		'visitors_can_directly',
		'create_profile_picture',
		'statistics',
		'logo',
		'featured',
		'added_date',
		'isTrash',
		'status',
		'category',
		'listing_package_id',
		'featured_package_id',
		'offer_title',
		'bundle_only',
		'f_type',
		'cat_id1',
		'cat_id2',
		'cat_id3',
		'cat_id4',
		'cat_id5',
		'cat_id6',
		'is_read',
		'backend_only',
		'is_new',
		'number_of_agents',
		'number_of_images',
		'number_of_featured_ads',
		'number_of_featured_ads_days',
		'number_of_hot_ads',
		'number_of_hot_ads_days',
		'daily_refresh_limit',
		'verified_badge',
		'analytics',
		'email',
		'whatsapp',
		'video_link',
		'agent_featured',
		'package_class',
		'recomanded_title',
		'parent_id',
		'email_phone_text',
		'agent_featured_text',
		'priority_text',
		'lead_text',
		'boost_text',
		'top_agency',
		'top_agency_text',
		'analytics_text',
		'number_of_spnsored_ad',
		'spnsored_ad_text',
		'stripe_price_id',
		'stripe_plan_id'
	];

	public function mw_package_new()
	{
		return $this->belongsTo(MwPackageNew::class, 'parent_id');
	}

	public function mw_package_news()
	{
		return $this->hasMany(MwPackageNew::class, 'parent_id');
	}

	public function mw_price_plan_orders()
	{
		return $this->hasMany(MwPricePlanOrder::class, 'feature_id');
	}

	public function mw_user_packages()
	{
		return $this->hasMany(MwUserPackage::class, 'package_id');
	}
}
