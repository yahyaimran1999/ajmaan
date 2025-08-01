<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwNearbyLocation
 *
 * @property int $id
 * @property int $country_id
 * @property int $state_id
 * @property string $location_name
 * @property string $location_latitude
 * @property string $location_longitude
 * @property string $isTrash
 *
 * @property MwCountry $mw_country
 * @property MwState $mw_state
 *
 * @package App\Models
 */
class MwNearbyLocation extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_nearby_location';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'state_id' => 'int'
	];

	protected $fillable = [
		'country_id',
		'state_id',
		'location_name',
		'location_latitude',
		'location_longitude',
		'isTrash'
	];

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}
}
