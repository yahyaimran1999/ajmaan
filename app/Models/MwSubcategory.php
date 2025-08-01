<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSubcategory
 *
 * @property int $sub_category_id
 * @property int|null $section_id
 * @property int $category_id
 * @property string $sub_category_name
 * @property string $amenities_required
 * @property int $priority
 * @property string $isTrash
 * @property string $status
 * @property string $slug
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $change_parent_fields
 * @property string $xml_inserted
 *
 * @property MwSection|null $mw_section
 * @property MwCategory $mw_category
 * @property Collection|MwModel[] $mw_models
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property MwSubCategoryFieldList|null $mw_sub_category_field_list
 * @property MwSubcategoryAmenitiesList|null $mw_subcategory_amenities_list
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwSubcategory extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_subcategory';
	protected $primaryKey = 'sub_category_id';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'category_id' => 'int',
		'priority' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'section_id',
		'category_id',
		'sub_category_name',
		'amenities_required',
		'priority',
		'isTrash',
		'status',
		'slug',
		'date_added',
		'last_updated',
		'change_parent_fields',
		'xml_inserted'
	];

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}

	public function mw_models()
	{
		return $this->hasMany(MwModel::class, 'sub_category_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'sub_category_id');
	}

	public function mw_sub_category_field_list()
	{
		return $this->hasOne(MwSubCategoryFieldList::class, 'sub_category_id');
	}

	public function mw_subcategory_amenities_list()
	{
		return $this->hasOne(MwSubcategoryAmenitiesList::class, 'sub_category_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'sub_category_id');
	}
}
