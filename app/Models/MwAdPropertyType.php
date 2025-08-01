<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdPropertyType
 *
 * @property int $id
 * @property int|null $ad_id
 * @property int|null $type_id
 * @property int $bed
 * @property int $bath
 * @property string $title
 * @property float|null $from_price
 * @property float|null $to_price
 * @property float $size
 * @property float $size_to
 * @property Carbon $last_updated
 * @property int|null $area_unit
 * @property int|null $price_unit
 * @property string|null $description
 * @property string|null $image
 *
 * @property MwCategory|null $mw_category
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property Collection|MwContactU[] $mw_contact_us
 *
 * @package App\Models
 */
class MwAdPropertyType extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_property_types';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'type_id' => 'int',
		'bed' => 'int',
		'bath' => 'int',
		'from_price' => 'float',
		'to_price' => 'float',
		'size' => 'float',
		'size_to' => 'float',
		'last_updated' => 'datetime',
		'area_unit' => 'int',
		'price_unit' => 'int'
	];

	protected $fillable = [
		'ad_id',
		'type_id',
		'bed',
		'bath',
		'title',
		'from_price',
		'to_price',
		'size',
		'size_to',
		'last_updated',
		'area_unit',
		'price_unit',
		'description',
		'image'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'type_id');
	}

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'p_type');
	}
}
