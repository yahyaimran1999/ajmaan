<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBodycondition
 *
 * @property int $bodycondition_id
 * @property string $bodycondition_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwBodycondition extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_bodycondition';
	protected $primaryKey = 'bodycondition_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'bodycondition_name',
		'status',
		'isTrash',
		'priority'
	];
}
