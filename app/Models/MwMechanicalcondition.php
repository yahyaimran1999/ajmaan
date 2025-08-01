<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMechanicalcondition
 *
 * @property int $mechanicalcondition_id
 * @property string $mechanicalcondition_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwMechanicalcondition extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_mechanicalcondition';
	protected $primaryKey = 'mechanicalcondition_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'mechanicalcondition_name',
		'status',
		'isTrash',
		'priority'
	];
}
