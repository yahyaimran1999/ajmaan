<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingUserAutoLoginToken
 *
 * @property int $token_id
 * @property int $user_id
 * @property string $token
 *
 * @package App\Models
 */
class MwListingUserAutoLoginToken extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_user_auto_login_token';
	protected $primaryKey = 'token_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'user_id',
		'token'
	];
}
