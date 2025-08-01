<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwFloorPlan
 *
 * @property int $floor_plan_id
 * @property string $floor_plan_name
 * @property int $property
 * @property int $project_id
 * @property string $description
 * @property string $isTrash
 * @property string $status
 * @property string $slug
 * @property Carbon $added_date
 *
 * @package App\Models
 */
class MwFloorPlan extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_floor_plan';
	protected $primaryKey = 'floor_plan_id';
	public $timestamps = false;

	protected $casts = [
		'property' => 'int',
		'project_id' => 'int',
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'floor_plan_name',
		'property',
		'project_id',
		'description',
		'isTrash',
		'status',
		'slug',
		'added_date'
	];
}
