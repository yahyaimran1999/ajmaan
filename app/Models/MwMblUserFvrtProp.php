<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMblUserFvrtProp
 *
 * @property int $id
 * @property int $mw_mbl_app_user_id
 * @property string $unique_app_id
 * @property int $ad_id
 * @property Carbon $date
 *
 * @package App\Models
 */
class MwMblUserFvrtProp extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_mbl_user_fvrt_prop';
	public $timestamps = false;

	protected $casts = [
		'mw_mbl_app_user_id' => 'int',
		'ad_id' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
		'mw_mbl_app_user_id',
		'unique_app_id',
		'ad_id',
		'date'
	];
}
