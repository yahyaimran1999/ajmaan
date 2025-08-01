<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPackage
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
 *
 * @property Collection|MwPricePlanPromoCode[] $mw_price_plan_promo_codes
 *
 * @package App\Models
 */
class MwPackage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_package';
	protected $primaryKey = 'package_id';
	public $timestamps = false;

	protected $casts = [
		'price_per_month' => 'float',
		'validity_in_days' => 'int',
		'max_listing_per_day' => 'int',
		'added_date' => 'datetime'
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
		'status'
	];

	public function mw_price_plan_promo_codes()
	{
		return $this->hasMany(MwPricePlanPromoCode::class, 'featured_package_id');
	}
}
