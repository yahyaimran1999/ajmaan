<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCurrency
 *
 * @property int $currency_id
 * @property string $name
 * @property string $code
 * @property float $value
 * @property string $is_default
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property float $base_rate
 *
 * @property Collection|MwCountry[] $mw_countries
 *
 * @package App\Models
 */
class MwCurrency extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_currency';
	protected $primaryKey = 'currency_id';
	public $timestamps = false;

	protected $casts = [
		'value' => 'float',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'base_rate' => 'float'
	];

	protected $fillable = [
		'name',
		'code',
		'value',
		'is_default',
		'status',
		'date_added',
		'last_updated',
		'base_rate'
	];

	public function mw_countries()
	{
		return $this->hasMany(MwCountry::class, 'default_currency');
	}
}
