<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwTax
 *
 * @property int $tax_id
 * @property int|null $country_id
 * @property int|null $zone_id
 * @property string $name
 * @property float $percent
 * @property string $is_global
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwTax extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_tax';
	protected $primaryKey = 'tax_id';
	public $timestamps = false;

	protected $casts = [
		'country_id' => 'int',
		'zone_id' => 'int',
		'percent' => 'float',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'country_id',
		'zone_id',
		'name',
		'percent',
		'is_global',
		'status',
		'date_added',
		'last_updated'
	];
}
