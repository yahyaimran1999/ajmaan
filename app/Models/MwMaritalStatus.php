<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMaritalStatus
 *
 * @property int $marital_id
 * @property string $marital_name
 * @property int $priority
 * @property string $status
 * @property int $isTrash
 *
 * @package App\Models
 */
class MwMaritalStatus extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_marital_status';
	protected $primaryKey = 'marital_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int',
		'isTrash' => 'int'
	];

	protected $fillable = [
		'marital_name',
		'priority',
		'status',
		'isTrash'
	];
}
