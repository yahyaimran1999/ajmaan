<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDoor
 *
 * @property int $door_id
 * @property string $door_name
 * @property string $isTrash
 * @property string $status
 * @property int $priority
 *
 * @package App\Models
 */
class MwDoor extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_door';
	protected $primaryKey = 'door_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'door_name',
		'isTrash',
		'status',
		'priority'
	];
}
