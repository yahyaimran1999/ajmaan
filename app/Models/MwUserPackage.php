<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class MwUserPackage
 *
 * @property int $id
 * @property int|null $package_id
 * @property int $user_id
 * @property Carbon $date_added
 * @property float $amount
 * @property string $latest
 * @property string $category_id
 * @property int $ads_allowed
 * @property int $used_ad
 * @property int $validity
 * @property int|null $created_by
 * @property string $status
 * @property int|null $deleted_by
 * @property string|null $deleted_status
 * @property int|null $promo_code_id
 * @property int|null $parent_package
 * @property int|null $order_id
 * @property string|null $is_renewed
 * @property Carbon|null $last_notification_sent
 * @property int|null $number_of_agents
 * @property int|null $max_listing_per_day
 * @property int|null $number_of_images
 * @property int|null $number_of_featured_ads
 * @property int|null $number_of_featured_ads_days
 * @property int|null $number_of_hot_ads
 * @property int|null $number_of_hot_ads_days
 * @property int|null $daily_refresh_limit
 * @property string|null $analytics
 * @property string|null $whatsapp
 * @property string|null $email
 * @property int|null $listing_used
 * @property int|null $featured_used
 * @property int|null $hot_used
 * @property int|null $refresh_used
 * @property int|null $agents_used
 * @property int|null $number_of_spnsored_ad
 *
 * @property MwPackageNew|null $mw_package_new
 * @property MwListingUser $mw_listing_user
 * @property MwUser|null $mw_user
 * @property MwUserPackage|null $mw_user_package
 * @property MwPricePlanOrder|null $mw_price_plan_order
 * @property Collection|MwUserPackage[] $mw_user_packages
 * @property Collection|MwUserPackagesUtility[] $mw_user_packages_utilities
 *
 * @package App\Models
 */
class MwUserPackage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_packages';
	public $timestamps = false;

	const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';

	protected $casts = [
		'package_id' => 'int',
		'user_id' => 'int',
		'date_added' => 'datetime',
		'amount' => 'float',
		'ads_allowed' => 'int',
		'used_ad' => 'int',
		'validity' => 'int',
		'created_by' => 'int',
		'deleted_by' => 'int',
		'promo_code_id' => 'int',
		'parent_package' => 'int',
		'order_id' => 'int',
		'last_notification_sent' => 'datetime',
		'number_of_agents' => 'int',
		'max_listing_per_day' => 'int',
		'number_of_images' => 'int',
		'number_of_featured_ads' => 'int',
		'number_of_featured_ads_days' => 'int',
		'number_of_hot_ads' => 'int',
		'number_of_hot_ads_days' => 'int',
		'daily_refresh_limit' => 'int',
		'listing_used' => 'int',
		'featured_used' => 'int',
		'hot_used' => 'int',
		'refresh_used' => 'int',
		'agents_used' => 'int',
		'number_of_spnsored_ad' => 'int'
	];

	protected $fillable = [
		'package_id',
		'user_id',
		'date_added',
		'amount',
		'latest',
		'category_id',
		'ads_allowed',
		'used_ad',
		'validity',
		'created_by',
		'status',
		'deleted_by',
		'deleted_status',
		'promo_code_id',
		'parent_package',
		'order_id',
		'is_renewed',
		'last_notification_sent',
		'number_of_agents',
		'max_listing_per_day',
		'number_of_images',
		'number_of_featured_ads',
		'number_of_featured_ads_days',
		'number_of_hot_ads',
		'number_of_hot_ads_days',
		'daily_refresh_limit',
		'analytics',
		'whatsapp',
		'email',
		'listing_used',
		'featured_used',
		'hot_used',
		'refresh_used',
		'agents_used',
		'number_of_spnsored_ad'
	];

	public function mw_package_new()
	{
		return $this->belongsTo(MwPackageNew::class, 'package_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'deleted_by');
	}

	public function mw_user_package()
	{
		return $this->belongsTo(MwUserPackage::class, 'parent_package');
	}

	public function mw_price_plan_order()
	{
		return $this->belongsTo(MwPricePlanOrder::class, 'order_id');
	}

	public function mw_user_packages()
	{
		return $this->hasMany(MwUserPackage::class, 'parent_package');
	}

	public function mw_user_packages_utilities()
	{
		return $this->hasMany(MwUserPackagesUtility::class, 'package_id');
	}

    /**
     * Find active packages for a specific user
     *
     * @param int $userId
     * @return mixed
     */
    public static function findActivePackages($userId)
    {
        try {
            $package = self::query()
                ->select([
                    'mw_user_packages.*',
                    'pkg_parent.package_name as package_name',
                    DB::raw('DATEDIFF(DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY), CURDATE()) AS remaining_days'),
                    DB::raw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) AS end_date')
                ])
                ->leftJoin('mw_package_new AS pkg', 'pkg.package_id', '=', 'mw_user_packages.package_id')
                ->leftJoin('mw_package_new AS pkg_parent', 'pkg_parent.package_id', '=', 'pkg.parent_id')
                ->where([
                    ['mw_user_packages.status', '=', 'active'],
                    ['mw_user_packages.latest', '=', '1'],
                    ['mw_user_packages.category_id', '=', '1'],
                    ['mw_user_packages.user_id', '=', $userId],
                ])
                ->where(function($query) {
                    $query->whereNull('mw_user_packages.deleted_status')
                          ->orWhere('mw_user_packages.deleted_status', '0');
                })
                ->whereRaw('DATE_ADD(mw_user_packages.date_added, INTERVAL mw_user_packages.validity DAY) >= CURDATE()')
                ->orderBy('mw_user_packages.date_added', 'desc')
                ->first();
            
            if ($package) {
                $endDate = Carbon::parse($package->end_date);
                $daysLeft = Carbon::now()->diffInDays($endDate, false);
                
                $package->ValidityNewTile = ($daysLeft > 0) 
                    ? "Valid for " . ceil($daysLeft) . " days (until " . $endDate->format('Y-m-d') . ")"
                    : "Expired on " . $endDate->format('Y-m-d');
            }
            
            return $package;
        } catch (\Exception $e) {
            Log::error('Error in findActivePackages method: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
}
