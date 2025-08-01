<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingUserPasswordReset
 *
 * @property int $request_id
 * @property int $user_id
 * @property string $reset_key
 * @property string|null $ip_address
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $email
 *
 * @package App\Models
 */
class MwListingUserPasswordReset extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_user_password_reset';
	protected $primaryKey = 'request_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'reset_key',
		'ip_address',
		'status',
		'date_added',
		'last_updated',
		'email'
	];
}
