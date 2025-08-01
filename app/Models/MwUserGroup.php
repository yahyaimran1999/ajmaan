<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserGroup
 *
 * @property int $group_id
 * @property string $name
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string|null $default_url
 *
 * @property Collection|MwUser[] $mw_users
 * @property Collection|MwUserGroupRouteAccess[] $mw_user_group_route_accesses
 *
 * @package App\Models
 */
class MwUserGroup extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_group';
	protected $primaryKey = 'group_id';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'name',
		'date_added',
		'last_updated',
		'default_url'
	];

	public function mw_users()
	{
		return $this->hasMany(MwUser::class, 'group_id');
	}

	public function mw_user_group_route_accesses()
	{
		return $this->hasMany(MwUserGroupRouteAccess::class, 'group_id');
	}
}
