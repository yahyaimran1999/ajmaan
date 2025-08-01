<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwState
 *
 * @property int $state_id
 * @property int $country_id
 * @property string $state_name
 * @property int $isTrash
 * @property string $location_longitude
 * @property string $location_latitude
 * @property int|null $priority
 * @property string $enable_listing
 * @property string|null $slug
 *
 * @property MwCountry $mw_country
 * @property Collection|MwAreaGuide[] $mw_area_guides
 * @property Collection|MwCity[] $mw_cities
 * @property Collection|MwCommunity[] $mw_communities
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwCountry[] $mw_countries
 * @property Collection|MwDeveloper[] $mw_developers
 * @property Collection|MwListingUserMoreState[] $mw_listing_user_more_states
 * @property Collection|MwListingUser[] $mw_listing_users
 * @property Collection|MwListingUsersStateFeatured[] $mw_listing_users_state_featureds
 * @property Collection|MwNearbyLocation[] $mw_nearby_locations
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwShopNewsletter[] $mw_shop_newsletters
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwState extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_states';
	protected $primaryKey = 'state_id';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'isTrash' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'country_id',
		'state_name',
		'isTrash',
		'location_longitude',
		'location_latitude',
		'priority',
		'enable_listing',
		'slug'
	];

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_area_guides()
	{
		return $this->hasMany(MwAreaGuide::class, 'state_id');
	}

	public function mw_cities()
	{
		return $this->hasMany(MwCity::class, 'state_id');
	}

	public function mw_communities()
	{
		return $this->hasMany(MwCommunity::class, 'region_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'state_id');
	}

	public function mw_countries()
	{
		return $this->hasMany(MwCountry::class, 'show_region');
	}

	public function mw_developers()
	{
		return $this->hasMany(MwDeveloper::class, 'state_id');
	}

	public function mw_listing_user_more_states()
	{
		return $this->hasMany(MwListingUserMoreState::class, 'state_id');
	}

	public function mw_listing_users()
	{
		return $this->hasMany(MwListingUser::class, 'state_id');
	}

	public function mw_listing_users_state_featureds()
	{
		return $this->hasMany(MwListingUsersStateFeatured::class, 'state_id');
	}

	public function mw_nearby_locations()
	{
		return $this->hasMany(MwNearbyLocation::class, 'state_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'state');
	}

	public function mw_shop_newsletters()
	{
		return $this->hasMany(MwShopNewsletter::class, 'state');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'state_id');
	}
}
