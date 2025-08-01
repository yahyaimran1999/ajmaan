<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwOccupation
 *
 * @property int $occupation_id
 * @property string $occupation_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwOccupation extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_occupation';
	protected $primaryKey = 'occupation_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'occupation_name',
		'status',
		'isTrash',
		'priority'
	];
}
