<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCity
 *
 * @property int $city_id
 * @property string $city_name
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int $priority
 * @property string $isTrash
 * @property string $status
 * @property string $inc
 * @property string|null $slug
 * @property int|null $parent_id
 *
 * @property MwState|null $mw_state
 * @property MwCountry|null $mw_country
 * @property MwCity|null $mw_city
 * @property Collection|MwAreaGuide[] $mw_area_guides
 * @property Collection|MwCity[] $mw_cities
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwDeveloper[] $mw_developers
 * @property Collection|MwDistrict[] $mw_districts
 * @property Collection|MwDynamicLink[] $mw_dynamic_links
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwSellHome[] $mw_sell_homes
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwCity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_city';
	protected $primaryKey = 'city_id';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'state_id' => 'int',
		'priority' => 'int',
		'parent_id' => 'int'
	];

	protected $fillable = [
		'city_name',
		'country_id',
		'state_id',
		'priority',
		'isTrash',
		'status',
		'inc',
		'slug',
		'parent_id'
	];

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'parent_id');
	}

	public function mw_area_guides()
	{
		return $this->hasMany(MwAreaGuide::class, 'location_id');
	}

	public function mw_cities()
	{
		return $this->hasMany(MwCity::class, 'parent_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'city_id');
	}

	public function mw_developers()
	{
		return $this->hasMany(MwDeveloper::class, 'city_id');
	}

	public function mw_districts()
	{
		return $this->hasMany(MwDistrict::class, 'city_id');
	}

	public function mw_dynamic_links()
	{
		return $this->hasMany(MwDynamicLink::class, 'city_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'city_4');
	}

	public function mw_sell_homes()
	{
		return $this->hasMany(MwSellHome::class, 'state_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'city_id');
	}
}
