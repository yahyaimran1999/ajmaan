<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingUserMoreCity
 *
 * @property int $user_id
 * @property int $city_id
 *
 * @package App\Models
 */
class MwListingUserMoreCity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_user_more_city';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'city_id' => 'int'
	];
}
