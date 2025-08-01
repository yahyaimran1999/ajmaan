<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwConnect
 *
 * @property int $id
 * @property int $service_type
 * @property Carbon|null $date
 * @property string $address
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $know
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $uid
 * @property string $master_id
 * @property string $c_type
 *
 * @package App\Models
 */
class MwConnect extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_connect';
	public $timestamps = false;

	protected $casts = [
		'service_type' => 'int',
		'date' => 'datetime',
		'know' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'service_type',
		'date',
		'address',
		'first_name',
		'last_name',
		'email',
		'phone',
		'know',
		'date_added',
		'last_updated',
		'uid',
		'master_id',
		'c_type'
	];
}
