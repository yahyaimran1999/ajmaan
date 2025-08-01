<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPricePlanOrder
 *
 * @property int $order_id
 * @property string $order_uid
 * @property int|null $customer_id
 * @property int $plan_id
 * @property int|null $promo_code_id
 * @property int|null $tax_id
 * @property int $currency_id
 * @property int|null $subtotal
 * @property float $tax_percent
 * @property int|null $tax_value
 * @property int|null $discount
 * @property int $total
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string|null $payment_type
 * @property int|null $created_by
 * @property int|null $deleted_by
 * @property string|null $deleted_status
 * @property string|null $u_contract
 * @property int|null $feature_id
 * @property int|null $ad_id
 * @property int|null $p_category
 * @property string|null $free_package
 * @property string|null $json_data
 *
 * @property MwListingUser|null $mw_listing_user
 * @property MwUser|null $mw_user
 * @property MwPricePlanPromoCode|null $mw_price_plan_promo_code
 * @property MwPackageNew|null $mw_package_new
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property Collection|MwUserPackage[] $mw_user_packages
 *
 * @package App\Models
 */
class MwPricePlanOrder extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_price_plan_order';
	protected $primaryKey = 'order_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'plan_id' => 'int',
		'promo_code_id' => 'int',
		'tax_id' => 'int',
		'currency_id' => 'int',
		'subtotal' => 'int',
		'tax_percent' => 'float',
		'tax_value' => 'int',
		'discount' => 'int',
		'total' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'created_by' => 'int',
		'deleted_by' => 'int',
		'feature_id' => 'int',
		'ad_id' => 'int',
		'p_category' => 'int'
	];

	protected $fillable = [
		'order_uid',
		'customer_id',
		'plan_id',
		'promo_code_id',
		'tax_id',
		'currency_id',
		'subtotal',
		'tax_percent',
		'tax_value',
		'discount',
		'total',
		'status',
		'date_added',
		'last_updated',
		'payment_type',
		'created_by',
		'deleted_by',
		'deleted_status',
		'u_contract',
		'feature_id',
		'ad_id',
		'p_category',
		'free_package',
		'json_data'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'customer_id');
	}

	public function mw_currency()
	{
		return $this->belongsTo(MwCurrency::class, 'currency_id');
	}

	public function mw_tax()
	{
		return $this->belongsTo(MwTax::class, 'tax_id');
	}

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'deleted_by');
	}

	public function mw_price_plan_promo_code()
	{
		return $this->belongsTo(MwPricePlanPromoCode::class, 'promo_code_id');
	}

	public function mw_package_new()
	{
		return $this->belongsTo(MwPackageNew::class, 'feature_id');
	}

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_user_packages()
	{
		return $this->hasMany(MwUserPackage::class, 'order_id');
	}
}
