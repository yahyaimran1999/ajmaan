<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwTrackingDomain
 *
 * @property int $domain_id
 * @property int|null $customer_id
 * @property string $name
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwTrackingDomain extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_tracking_domain';
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
		'date_added',
		'last_updated'
	];
}
