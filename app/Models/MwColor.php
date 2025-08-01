<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwColor
 *
 * @property int $color_id
 * @property string $color_name
 * @property string $status
 * @property string $isTrash
 * @property int $priority
 *
 * @package App\Models
 */
class MwColor extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_color';
	protected $primaryKey = 'color_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'color_name',
		'status',
		'isTrash',
		'priority'
	];
}
