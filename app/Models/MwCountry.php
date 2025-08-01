<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCountry
 *
 * @property int $country_id
 * @property string $country_name
 * @property string|null $country_name_pol
 * @property string $country_code
 * @property int $isTrash
 * @property string $location_latitude
 * @property string $location_longitude
 * @property string $image
 * @property string|null $cords
 * @property string $show_on_listing
 * @property string $flag
 * @property int|null $priority
 * @property string $enable_all_cities
 * @property int|null $show_region
 * @property int|null $default_currency
 * @property string|null $slug
 *
 * @property MwState|null $mw_state
 * @property MwCurrency|null $mw_currency
 * @property Collection|MwBanner[] $mw_banners
 * @property Collection|MwCity[] $mw_cities
 * @property Collection|MwCommunity[] $mw_communities
 * @property Collection|MwFreebitesInformation[] $mw_freebites_informations
 * @property Collection|MwListingUserMoreCountry[] $mw_listing_user_more_countries
 * @property Collection|MwListingUser[] $mw_listing_users
 * @property Collection|MwListingUsersFeatured[] $mw_listing_users_featureds
 * @property Collection|MwLoanApplication[] $mw_loan_applications
 * @property Collection|MwMaster[] $mw_masters
 * @property Collection|MwNearbyLocation[] $mw_nearby_locations
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwState[] $mw_states
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwCountry extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_countries';
	protected $primaryKey = 'country_id';
	public $timestamps = false;

	protected $casts = [
		'isTrash' => 'int',
		'priority' => 'int',
		'show_region' => 'int',
		'default_currency' => 'int'
	];

	protected $fillable = [
		'country_name',
		'country_name_pol',
		'country_code',
		'isTrash',
		'location_latitude',
		'location_longitude',
		'image',
		'cords',
		'show_on_listing',
		'flag',
		'priority',
		'enable_all_cities',
		'show_region',
		'default_currency',
		'slug'
	];

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'show_region');
	}

	public function mw_currency()
	{
		return $this->belongsTo(MwCurrency::class, 'default_currency');
	}

	public function mw_banners()
	{
		return $this->hasMany(MwBanner::class, 'country_id');
	}

	public function mw_cities()
	{
		return $this->hasMany(MwCity::class, 'country_id');
	}

	public function mw_communities()
	{
		return $this->hasMany(MwCommunity::class, 'country_id');
	}

	public function mw_freebites_informations()
	{
		return $this->hasMany(MwFreebitesInformation::class, 'country_id');
	}

	public function mw_listing_user_more_countries()
	{
		return $this->hasMany(MwListingUserMoreCountry::class, 'country_id');
	}

	public function mw_listing_users()
	{
		return $this->hasMany(MwListingUser::class, 'country_id');
	}

	public function mw_listing_users_featureds()
	{
		return $this->hasMany(MwListingUsersFeatured::class, 'country_id');
	}

	public function mw_loan_applications()
	{
		return $this->hasMany(MwLoanApplication::class, 'country_id');
	}

	public function mw_masters()
	{
		return $this->hasMany(MwMaster::class, 'country_id');
	}

	public function mw_nearby_locations()
	{
		return $this->hasMany(MwNearbyLocation::class, 'country_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'country');
	}

	public function mw_states()
	{
		return $this->hasMany(MwState::class, 'country_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'country_id');
	}
}
