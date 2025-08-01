<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwEmploymentType
 *
 * @property int $employment_type_id
 * @property string $employment_type_name
 * @property string $isTrash
 * @property int $priority
 * @property string $status
 *
 * @package App\Models
 */
class MwEmploymentType extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_employment_type';
	protected $primaryKey = 'employment_type_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'employment_type_name',
		'isTrash',
		'priority',
		'status'
	];
}
