<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwReligion
 *
 * @property int $religion_id
 * @property string $religion_name
 * @property string $status
 * @property int $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwReligion extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_religion';
	protected $primaryKey = 'religion_id';
	public $timestamps = false;

	protected $casts = [
		'isTrash' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'religion_name',
		'status',
		'isTrash',
		'priority'
	];
}
