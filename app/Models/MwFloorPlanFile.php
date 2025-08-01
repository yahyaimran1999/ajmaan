<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwFloorPlanFile
 *
 * @property int $id
 * @property int $property
 * @property int $project_id
 * @property int $floor_plan_id
 * @property string $title
 * @property string $file
 * @property string $slug
 * @property string $file_caption
 *
 * @package App\Models
 */
class MwFloorPlanFile extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_floor_plan_file';
	public $timestamps = false;

	protected $casts = [
		'property' => 'int',
		'project_id' => 'int',
		'floor_plan_id' => 'int'
	];

	protected $fillable = [
		'property',
		'project_id',
		'floor_plan_id',
		'title',
		'file',
		'slug',
		'file_caption'
	];
}
