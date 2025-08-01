<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwOtp
 *
 * @property int $id
 * @property string $email
 * @property int $otp_code
 * @property Carbon $date_added
 *
 * @package App\Models
 */
class MwOtp extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_otp';
	public $timestamps = false;

	protected $casts = [
		'otp_code' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'email',
		'otp_code',
		'date_added'
	];
}
