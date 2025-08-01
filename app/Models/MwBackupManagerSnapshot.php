<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBackupManagerSnapshot
 *
 * @property int $snapshot_id
 * @property string $name
 * @property string $path
 * @property int $size
 * @property string|null $meta_data
 * @property Carbon $date_added
 *
 * @package App\Models
 */
class MwBackupManagerSnapshot extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_backup_manager_snapshot';
	protected $primaryKey = 'snapshot_id';
	public $timestamps = false;

	protected $casts = [
		'size' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'name',
		'path',
		'size',
		'meta_data',
		'date_added'
	];
}
