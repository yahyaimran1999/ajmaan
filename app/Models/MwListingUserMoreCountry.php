<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingUserMoreCountry
 *
 * @property int $user_id
 * @property int $country_id
 *
 * @property MwListingUser $mw_listing_user
 * @property MwCountry $mw_country
 *
 * @package App\Models
 */
class MwListingUserMoreCountry extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_user_more_country';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'country_id' => 'int'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}
}
