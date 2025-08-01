<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCategoryFieldListMandatory
 *
 * @property int $category_id
 * @property string $field_name
 *
 * @property MwCategory $mw_category
 *
 * @package App\Models
 */
class MwCategoryFieldListMandatory extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_category_field_list_mandatory';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}
}
