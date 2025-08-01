<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserGroupRouteAccess
 *
 * @property int $route_id
 * @property int $group_id
 * @property string $route
 * @property string $access
 * @property Carbon|null $date_added
 *
 * @property MwUserGroup $mw_user_group
 *
 * @package App\Models
 */
class MwUserGroupRouteAccess extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_group_route_access';
	protected $primaryKey = 'route_id';
	public $timestamps = false;

	protected $casts = [
		'group_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'group_id',
		'route',
		'access',
		'date_added'
	];

	public function mw_user_group()
	{
		return $this->belongsTo(MwUserGroup::class, 'group_id');
	}
}
