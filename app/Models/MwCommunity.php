<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCommunity
 *
 * @property int $community_id
 * @property string|null $community_name
 * @property int|null $district_id
 * @property string $HaveSubComm
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 *
 * @property MwDistrict|null $mw_district
 * @property MwCountry|null $mw_country
 * @property MwState|null $mw_state
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwSubCommunity[] $mw_sub_communities
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwCommunity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_community';
	protected $primaryKey = 'community_id';
	public $timestamps = false;

	protected $casts = [
		'district_id' => 'int',
		'country_id' => 'int',
		'region_id' => 'int',
		'city_id' => 'int'
	];

	protected $fillable = [
		'community_name',
		'district_id',
		'HaveSubComm',
		'country_id',
		'region_id',
		'city_id'
	];

	public function mw_district()
	{
		return $this->belongsTo(MwDistrict::class, 'district_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'region_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'community_id');
	}

	public function mw_sub_communities()
	{
		return $this->hasMany(MwSubCommunity::class, 'community_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'community_id');
	}
}
