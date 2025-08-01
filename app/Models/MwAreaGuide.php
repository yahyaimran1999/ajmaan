<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAreaGuide
 *
 * @property int $id
 * @property int|null $state_id
 * @property int|null $location_id
 * @property string $highlight
 * @property string $neighbor
 * @property string $life_style
 * @property string $location
 * @property Carbon $date_added
 * @property string|null $banner
 * @property Carbon $last_updated
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string $f_type
 * @property string|null $title
 * @property string|null $slug
 * @property string|null $contact
 * @property float|null $location_latitude
 * @property float|null $location_longitude
 * @property string|null $area_location
 * @property int|null $category_id
 * @property int|null $property_type
 * @property string|null $heading
 * @property string|null $for_sale
 * @property string $for_rent
 * @property string $channel
 * @property int|null $master_id
 *
 * @property MwState|null $mw_state
 * @property MwCity|null $mw_city
 * @property MwCategory|null $mw_category
 * @property MwMaster|null $mw_master
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwGideFaq[] $mw_gide_faqs
 * @property Collection|MwGuideImage[] $mw_guide_images
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwAreaGuide extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_area_guides';
	public $timestamps = false;

	protected $casts = [
		'state_id' => 'int',
		'location_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'location_latitude' => 'float',
		'location_longitude' => 'float',
		'category_id' => 'int',
		'property_type' => 'int',
		'master_id' => 'int'
	];

	protected $fillable = [
		'state_id',
		'location_id',
		'highlight',
		'neighbor',
		'life_style',
		'location',
		'date_added',
		'banner',
		'last_updated',
		'meta_title',
		'meta_description',
		'f_type',
		'title',
		'slug',
		'contact',
		'location_latitude',
		'location_longitude',
		'area_location',
		'category_id',
		'property_type',
		'heading',
		'for_sale',
		'for_rent',
		'channel',
		'master_id'
	];

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'location_id');
	}

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'property_type');
	}

	public function mw_master()
	{
		return $this->belongsTo(MwMaster::class, 'master_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'guide_id');
	}

	public function mw_gide_faqs()
	{
		return $this->hasMany(MwGideFaq::class, 'guide_id');
	}

	public function mw_guide_images()
	{
		return $this->hasMany(MwGuideImage::class, 'guide_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'guide_id');
	}
}
