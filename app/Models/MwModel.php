<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwModel
 *
 * @property int $model_id
 * @property int $sub_category_id
 * @property string $model_name
 * @property string $isTrash
 * @property string $status
 * @property int $priority
 *
 * @property MwSubcategory $mw_subcategory
 *
 * @package App\Models
 */
class MwModel extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_model';
	protected $primaryKey = 'model_id';
	public $timestamps = false;

	protected $casts = [
		'sub_category_id' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'sub_category_id',
		'model_name',
		'isTrash',
		'status',
		'priority'
	];

	public function mw_subcategory()
	{
		return $this->belongsTo(MwSubcategory::class, 'sub_category_id');
	}
}
