<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSubCategoryFieldList
 *
 * @property int $sub_category_id
 * @property string $field_name
 *
 * @property MwSubcategory $mw_subcategory
 *
 * @package App\Models
 */
class MwSubCategoryFieldList extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_sub_category_field_list';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'sub_category_id' => 'int'
	];

	protected $fillable = [
		'sub_category_id',
		'field_name'
	];

	public function mw_subcategory()
	{
		return $this->belongsTo(MwSubcategory::class, 'sub_category_id');
	}
}
