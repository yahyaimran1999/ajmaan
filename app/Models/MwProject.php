<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwProject
 *
 * @property int $project_id
 * @property string $project_name
 * @property int $property_id
 * @property string $isTrash
 * @property string $status
 * @property string $slug
 * @property Carbon $added_date
 *
 * @package App\Models
 */
class MwProject extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_project';
	protected $primaryKey = 'project_id';
	public $timestamps = false;

	protected $casts = [
		'property_id' => 'int',
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'project_name',
		'property_id',
		'isTrash',
		'status',
		'slug',
		'added_date'
	];
}
