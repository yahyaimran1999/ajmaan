<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDeliveryServerDomainPolicy
 *
 * @property int $domain_id
 * @property int $server_id
 * @property string $domain
 * @property string $policy
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @property MwDeliveryServer $mw_delivery_server
 *
 * @package App\Models
 */
class MwDeliveryServerDomainPolicy extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_delivery_server_domain_policy';
	protected $primaryKey = 'domain_id';
	public $timestamps = false;

	protected $casts = [
		'server_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'server_id',
		'domain',
		'policy',
		'date_added',
		'last_updated'
	];

	public function mw_delivery_server()
	{
		return $this->belongsTo(MwDeliveryServer::class, 'server_id');
	}
}
