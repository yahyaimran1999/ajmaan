<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDistrict
 *
 * @property int $district_id
 * @property string $district_name
 * @property int $city_id
 *
 * @property MwCity $mw_city
 * @property Collection|MwCommunity[] $mw_communities
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 *
 * @package App\Models
 */
class MwDistrict extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_district';
	protected $primaryKey = 'district_id';
	public $timestamps = false;

	protected $casts = [
		'city_id' => 'int'
	];

	protected $fillable = [
		'district_name',
		'city_id'
	];

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city_id');
	}

	public function mw_communities()
	{
		return $this->hasMany(MwCommunity::class, 'district_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'district');
	}
}
