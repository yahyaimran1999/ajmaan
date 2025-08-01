<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwService
 *
 * @property int $service_id
 * @property string $service_name
 * @property string $status
 * @property int $isTrash
 * @property int|null $priority
 *
 * @property Collection|MwListingUser[] $mw_listing_users
 *
 * @package App\Models
 */
class MwService extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_services';
	protected $primaryKey = 'service_id';
	public $timestamps = false;

	protected $casts = [
		'isTrash' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'service_name',
		'status',
		'isTrash',
		'priority'
	];

	public function mw_listing_users()
	{
		return $this->hasMany(MwListingUser::class, 'designation_id');
	}
}
