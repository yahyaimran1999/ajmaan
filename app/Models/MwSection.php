<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSection
 *
 * @property int $section_id
 * @property string $section_name
 * @property string|null $for_name
 * @property string $isTrash
 * @property string $status
 * @property int $priority
 * @property Carbon $added_date
 * @property Carbon $modified_date
 * @property string $slug
 * @property string $disable_frotend
 * @property string|null $for_color
 * @property string|null $for_icon
 *
 * @property Collection|MwCategory[] $mw_categories
 * @property Collection|MwCategorySection[] $mw_category_sections
 * @property Collection|MwDynamicLink[] $mw_dynamic_links
 * @property Collection|MwMaster[] $mw_masters
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwSubcategory[] $mw_subcategories
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwSection extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_section';
	protected $primaryKey = 'section_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int',
		'added_date' => 'datetime',
		'modified_date' => 'datetime'
	];

	protected $fillable = [
		'section_name',
		'for_name',
		'isTrash',
		'status',
		'priority',
		'added_date',
		'modified_date',
		'slug',
		'disable_frotend',
		'for_color',
		'for_icon'
	];

	public function mw_categories()
	{
		return $this->hasMany(MwCategory::class, 'section_id');
	}

	public function mw_category_sections()
	{
		return $this->hasMany(MwCategorySection::class, 'section_id');
	}

	public function mw_dynamic_links()
	{
		return $this->hasMany(MwDynamicLink::class, 'section_id');
	}

	public function mw_masters()
	{
		return $this->hasMany(MwMaster::class, 'section_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'section_id');
	}

	public function mw_subcategories()
	{
		return $this->hasMany(MwSubcategory::class, 'section_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'section_id');
	}
}
