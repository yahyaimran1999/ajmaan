<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwEngineSize
 *
 * @property int $engine_size_id
 * @property string $engine_size_name
 * @property string $isTrash
 * @property string $status
 * @property int $priority
 *
 * @package App\Models
 */
class MwEngineSize extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_engine_size';
	protected $primaryKey = 'engine_size_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'engine_size_name',
		'isTrash',
		'status',
		'priority'
	];
}
