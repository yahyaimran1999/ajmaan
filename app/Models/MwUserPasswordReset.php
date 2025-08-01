<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserPasswordReset
 *
 * @property int $request_id
 * @property int $user_id
 * @property string $reset_key
 * @property string|null $ip_address
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @property MwUser $mw_user
 *
 * @package App\Models
 */
class MwUserPasswordReset extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_password_reset';
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
		'last_updated'
	];

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'user_id');
	}
}
