<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPricePlanPromoCode
 *
 * @property int $promo_code_id
 * @property string $code
 * @property string $type
 * @property float $discount
 * @property float $total_amount
 * @property int $total_usage
 * @property int $customer_usage
 * @property Carbon $date_start
 * @property Carbon $date_end
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $assigned_to
 * @property string|null $offer_title
 * @property int|null $listing_package_id
 * @property int|null $featured_package_id
 *
 * @property MwPricePlanPromoCode|null $mw_price_plan_promo_code
 * @property MwPackage|null $mw_package
 * @property Collection|MwPricePlanOrder[] $mw_price_plan_orders
 * @property Collection|MwPricePlanPromoCode[] $mw_price_plan_promo_codes
 *
 * @package App\Models
 */
class MwPricePlanPromoCode extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_price_plan_promo_code';
	protected $primaryKey = 'promo_code_id';
	public $timestamps = false;

	protected $casts = [
		'discount' => 'float',
		'total_amount' => 'float',
		'total_usage' => 'int',
		'customer_usage' => 'int',
		'date_start' => 'datetime',
		'date_end' => 'datetime',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'assigned_to' => 'int',
		'listing_package_id' => 'int',
		'featured_package_id' => 'int'
	];

	protected $fillable = [
		'code',
		'type',
		'discount',
		'total_amount',
		'total_usage',
		'customer_usage',
		'date_start',
		'date_end',
		'status',
		'date_added',
		'last_updated',
		'assigned_to',
		'offer_title',
		'listing_package_id',
		'featured_package_id'
	];

	public function mw_price_plan_promo_code()
	{
		return $this->belongsTo(MwPricePlanPromoCode::class, 'assigned_to');
	}

	public function mw_package()
	{
		return $this->belongsTo(MwPackage::class, 'featured_package_id');
	}

	public function mw_price_plan_orders()
	{
		return $this->hasMany(MwPricePlanOrder::class, 'promo_code_id');
	}

	public function mw_price_plan_promo_codes()
	{
		return $this->hasMany(MwPricePlanPromoCode::class, 'assigned_to');
	}
}
