<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCategorySection
 *
 * @property int $category_id
 * @property int $section_id
 *
 * @property MwCategory $mw_category
 * @property MwSection $mw_section
 *
 * @package App\Models
 */
class MwCategorySection extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_category_section';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int',
		'section_id' => 'int'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}
}
