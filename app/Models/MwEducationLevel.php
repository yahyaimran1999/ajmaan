<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwEducationLevel
 *
 * @property int $education_id
 * @property string $education_name
 * @property string $isTrash
 * @property int $priority
 * @property string $status
 *
 * @package App\Models
 */
class MwEducationLevel extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_education_level';
	protected $primaryKey = 'education_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'education_name',
		'isTrash',
		'priority',
		'status'
	];
}
