<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCustomerActionLog
 *
 * @property int $log_id
 * @property int $customer_id
 * @property string $category
 * @property int $reference_id
 * @property int $reference_relation_id
 * @property string $message
 * @property Carbon $date_added
 *
 * @property MwListingUser $mw_listing_user
 *
 * @package App\Models
 */
class MwCustomerActionLog extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_customer_action_log';
	protected $primaryKey = 'log_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'reference_id' => 'int',
		'reference_relation_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'customer_id',
		'category',
		'reference_id',
		'reference_relation_id',
		'message',
		'date_added'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'customer_id');
	}
}
