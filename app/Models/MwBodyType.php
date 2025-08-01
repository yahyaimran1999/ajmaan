<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBodyType
 *
 * @property int $body_type_id
 * @property string $body_type_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwBodyType extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_body_type';
	protected $primaryKey = 'body_type_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'body_type_name',
		'status',
		'isTrash',
		'priority'
	];
}
