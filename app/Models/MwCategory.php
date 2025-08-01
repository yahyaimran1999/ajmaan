<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCategory
 *
 * @property int $category_id
 * @property int|null $section_id
 * @property string $category_name
 * @property string|null $plural_name
 * @property string $amenities_required
 * @property string $slug
 * @property int|null $priority
 * @property string $isTrash
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $icon
 * @property string $xml_inserted
 * @property string|null $listing_type
 * @property string $f_type
 * @property string|null $s_term
 * @property string $n_development
 * @property int|null $c_priority
 *
 * @property MwSection|null $mw_section
 * @property Collection|MwAdPropertyType[] $mw_ad_property_types
 * @property MwAmenitiesCategoryList|null $mw_amenities_category_list
 * @property Collection|MwAreaGuide[] $mw_area_guides
 * @property MwCategoryFieldList|null $mw_category_field_list
 * @property Collection|MwCategoryFieldListMandatory[] $mw_category_field_list_mandatories
 * @property Collection|MwCategorySection[] $mw_category_sections
 * @property Collection|MwDynamicLink[] $mw_dynamic_links
 * @property Collection|MwListingTypeFileld[] $mw_listing_type_filelds
 * @property Collection|MwMaster[] $mw_masters
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwSellHome[] $mw_sell_homes
 * @property Collection|MwSubcategory[] $mw_subcategories
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 * @property Collection|MwUserMainCategory[] $mw_user_main_categories
 *
 * @package App\Models
 */
class MwCategory extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_category';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'priority' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'c_priority' => 'int'
	];

	protected $fillable = [
		'section_id',
		'category_name',
		'plural_name',
		'amenities_required',
		'slug',
		'priority',
		'isTrash',
		'status',
		'date_added',
		'last_updated',
		'icon',
		'xml_inserted',
		'listing_type',
		'f_type',
		's_term',
		'n_development',
		'c_priority'
	];

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_ad_property_types()
	{
		return $this->hasMany(MwAdPropertyType::class, 'type_id');
	}

	public function mw_amenities_category_list()
	{
		return $this->hasOne(MwAmenitiesCategoryList::class, 'category_id');
	}

	public function mw_area_guides()
	{
		return $this->hasMany(MwAreaGuide::class, 'property_type');
	}

	public function mw_category_field_list()
	{
		return $this->hasOne(MwCategoryFieldList::class, 'category_id');
	}

	public function mw_category_field_list_mandatories()
	{
		return $this->hasMany(MwCategoryFieldListMandatory::class, 'category_id');
	}

	public function mw_category_sections()
	{
		return $this->hasMany(MwCategorySection::class, 'category_id');
	}

	public function mw_dynamic_links()
	{
		return $this->hasMany(MwDynamicLink::class, 'type_id');
	}

	public function mw_listing_type_filelds()
	{
		return $this->hasMany(MwListingTypeFileld::class, 'category_id');
	}

	public function mw_masters()
	{
		return $this->hasMany(MwMaster::class, 'category_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'category_id');
	}

	public function mw_sell_homes()
	{
		return $this->hasMany(MwSellHome::class, 'list_type_main');
	}

	public function mw_subcategories()
	{
		return $this->hasMany(MwSubcategory::class, 'category_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'category_id');
	}

	public function mw_user_main_categories()
	{
		return $this->hasMany(MwUserMainCategory::class, 'category_id');
	}
}
