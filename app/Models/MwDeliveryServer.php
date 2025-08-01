<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDeliveryServer
 *
 * @property int $server_id
 * @property int|null $customer_id
 * @property int|null $bounce_server_id
 * @property int|null $tracking_domain_id
 * @property string $type
 * @property string|null $name
 * @property string $hostname
 * @property string $username
 * @property string|null $password
 * @property int|null $port
 * @property string|null $protocol
 * @property int|null $timeout
 * @property string $from_email
 * @property string $from_name
 * @property string|null $reply_to_email
 * @property int $probability
 * @property int $hourly_quota
 * @property string|null $meta_data
 * @property string|null $confirmation_key
 * @property string $locked
 * @property string $use_for
 * @property string $use_queue
 * @property string $signing_enabled
 * @property string $force_from
 * @property string $force_reply_to
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string|null $monthly_quota
 * @property string|null $pause_after_send
 * @property string|null $force_sender
 * @property string|null $max_connection_messages
 *
 * @property Collection|MwDeliveryServerDomainPolicy[] $mw_delivery_server_domain_policies
 *
 * @package App\Models
 */
class MwDeliveryServer extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_delivery_server';
	protected $primaryKey = 'server_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'bounce_server_id' => 'int',
		'tracking_domain_id' => 'int',
		'port' => 'int',
		'timeout' => 'int',
		'probability' => 'int',
		'hourly_quota' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'customer_id',
		'bounce_server_id',
		'tracking_domain_id',
		'type',
		'name',
		'hostname',
		'username',
		'password',
		'port',
		'protocol',
		'timeout',
		'from_email',
		'from_name',
		'reply_to_email',
		'probability',
		'hourly_quota',
		'meta_data',
		'confirmation_key',
		'locked',
		'use_for',
		'use_queue',
		'signing_enabled',
		'force_from',
		'force_reply_to',
		'status',
		'date_added',
		'last_updated',
		'monthly_quota',
		'pause_after_send',
		'force_sender',
		'max_connection_messages'
	];

	public function mw_delivery_server_domain_policies()
	{
		return $this->hasMany(MwDeliveryServerDomainPolicy::class, 'server_id');
	}
}
