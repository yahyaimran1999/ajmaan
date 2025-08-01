<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSchoolsInfo
 *
 * @property int $id
 * @property float $base_lat
 * @property float $base_lng
 * @property string $name
 * @property float $lat
 * @property float $lng
 * @property string $address
 * @property string|null $is_school
 * @property string|null $is_primary
 * @property string|null $is_secondary
 * @property float $distance
 *
 * @package App\Models
 */
class MwSchoolsInfo extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_schools_info';
	public $timestamps = false;

	protected $casts = [
		'base_lat' => 'float',
		'base_lng' => 'float',
		'lat' => 'float',
		'lng' => 'float',
		'distance' => 'float'
	];

	protected $fillable = [
		'base_lat',
		'base_lng',
		'name',
		'lat',
		'lng',
		'address',
		'is_school',
		'is_primary',
		'is_secondary',
		'distance'
	];
}
