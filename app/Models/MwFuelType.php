<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwFuelType
 *
 * @property int $fuel_id
 * @property string $fuel_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwFuelType extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_fuel_type';
	protected $primaryKey = 'fuel_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'fuel_name',
		'status',
		'isTrash',
		'priority'
	];
}
