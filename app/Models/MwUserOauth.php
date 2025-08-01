<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserOauth
 *
 * @property int $user_id
 * @property string $provider
 * @property string $identifier
 * @property string|null $profile_cache
 * @property string|null $session_data
 *
 * @package App\Models
 */
class MwUserOauth extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_oauth';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'profile_cache',
		'session_data'
	];
}
