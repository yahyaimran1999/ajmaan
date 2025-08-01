<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMblAppUser
 *
 * @property int $id
 * @property string $unique_app_id
 * @property string $user_name
 * @property string $email
 * @property Carbon $date
 *
 * @package App\Models
 */
class MwMblAppUser extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_mbl_app_user';
	public $timestamps = false;

	protected $casts = [
		'date' => 'datetime'
	];

	protected $fillable = [
		'unique_app_id',
		'user_name',
		'email',
		'date'
	];
}
