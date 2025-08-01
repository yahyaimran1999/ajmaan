<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdAmenity
 *
 * @property int $ad_id
 * @property int $amenities_id
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 * @property MwAmenity $mw_amenity
 *
 * @package App\Models
 */
class MwAdAmenity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_amenities';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'amenities_id' => 'int'
	];

	protected $fillable = [
		'ad_id',
		'amenities_id'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_amenity()
	{
		return $this->belongsTo(MwAmenity::class, 'amenities_id');
	}

    public function amenities()
    {
        return $this->hasMany(MwAmenity::class, 'id', 'amenities_id');
    }
}
