<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingUsersStateFeatured
 *
 * @property int $user_id
 * @property int $state_id
 *
 * @property MwListingUser $mw_listing_user
 * @property MwState $mw_state
 *
 * @package App\Models
 */
class MwListingUsersStateFeatured extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_users_state_featured';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'state_id' => 'int'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}
}
