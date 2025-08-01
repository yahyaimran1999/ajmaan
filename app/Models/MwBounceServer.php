<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBounceServer
 *
 * @property int $server_id
 * @property int|null $customer_id
 * @property string $hostname
 * @property string $username
 * @property string $password
 * @property string $service
 * @property int $port
 * @property string $protocol
 * @property string $validate_ssl
 * @property string $locked
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwBounceServer extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_bounce_server';
	protected $primaryKey = 'server_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'port' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'customer_id',
		'hostname',
		'username',
		'password',
		'service',
		'port',
		'protocol',
		'validate_ssl',
		'locked',
		'status',
		'date_added',
		'last_updated'
	];
}
