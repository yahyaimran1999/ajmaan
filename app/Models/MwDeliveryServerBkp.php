<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDeliveryServerBkp
 *
 * @property int $server_id
 * @property int|null $customer_id
 * @property int|null $bounce_server_id
 * @property string $type
 * @property string $hostname
 * @property string $username
 * @property string|null $password
 * @property int|null $port
 * @property string|null $protocol
 * @property int|null $timeout
 * @property int $probability
 * @property int $hourly_quota
 * @property int $hourly_sent
 * @property string $custom_from_header
 * @property Carbon|null $last_sent
 * @property string|null $meta_data
 * @property string|null $confirmation_key
 * @property string $locked
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwDeliveryServerBkp extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_delivery_server_bkp';
	protected $primaryKey = 'server_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'bounce_server_id' => 'int',
		'port' => 'int',
		'timeout' => 'int',
		'probability' => 'int',
		'hourly_quota' => 'int',
		'hourly_sent' => 'int',
		'last_sent' => 'datetime',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'customer_id',
		'bounce_server_id',
		'type',
		'hostname',
		'username',
		'password',
		'port',
		'protocol',
		'timeout',
		'probability',
		'hourly_quota',
		'hourly_sent',
		'custom_from_header',
		'last_sent',
		'meta_data',
		'confirmation_key',
		'locked',
		'status',
		'date_added',
		'last_updated'
	];
}
