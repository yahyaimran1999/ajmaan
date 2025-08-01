<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwProperty
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property Carbon $added_date
 * @property string $isTrash
 * @property string $slug
 *
 * @package App\Models
 */
class MwProperty extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_property';
	public $timestamps = false;

	protected $casts = [
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'name',
		'description',
		'status',
		'added_date',
		'isTrash',
		'slug'
	];
}
