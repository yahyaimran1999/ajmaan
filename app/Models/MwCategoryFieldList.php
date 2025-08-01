<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCategoryFieldList
 *
 * @property int $category_id
 * @property string $field_name
 *
 * @property MwCategory $mw_category
 *
 * @package App\Models
 */
class MwCategoryFieldList extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_category_field_list';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int'
	];

	protected $fillable = [
		'category_id',
		'field_name'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}
}
