<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwExperience
 *
 * @property int $experience_id
 * @property string $experience_name
 * @property string $isTrash
 * @property int $priority
 * @property string $status
 *
 * @package App\Models
 */
class MwExperience extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_experience';
	protected $primaryKey = 'experience_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'experience_name',
		'isTrash',
		'priority',
		'status'
	];
}
