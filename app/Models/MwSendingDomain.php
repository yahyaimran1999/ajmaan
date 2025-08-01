<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSendingDomain
 *
 * @property int $domain_id
 * @property int|null $customer_id
 * @property string $name
 * @property string $dkim_private_key
 * @property string $dkim_public_key
 * @property string $locked
 * @property string $verified
 * @property string $signing_enabled
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwSendingDomain extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_sending_domain';
	protected $primaryKey = 'domain_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'customer_id',
		'name',
		'dkim_private_key',
		'dkim_public_key',
		'locked',
		'verified',
		'signing_enabled',
		'date_added',
		'last_updated'
	];
}
