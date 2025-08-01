<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDynamicLink
 *
 * @property int $master_id
 * @property string $name
 * @property string $f_type
 * @property Carbon $last_updated
 * @property int|null $priority
 * @property string $is_trash
 * @property string $status
 * @property int|null $category_id
 * @property string|null $file_image
 * @property int|null $section_id
 * @property string|null $url
 * @property int|null $country_id
 * @property int|null $city_id
 * @property int|null $location_id
 * @property string $show_all
 * @property string $show_all_c
 * @property string $use_under
 * @property int|null $type_id
 * @property int|null $master_category
 * @property int|null $parent_id
 * @property string|null $user_type
 * @property string $featured
 * @property string|null $direct_link
 * @property string|null $beds
 *
 * @property MwCity|null $mw_city
 * @property MwSection|null $mw_section
 * @property MwCategory|null $mw_category
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwDynamicLink extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_dynamic_links';
	protected $primaryKey = 'master_id';
	public $timestamps = false;

	protected $casts = [
		'last_updated' => 'datetime',
		'priority' => 'int',
		'category_id' => 'int',
		'section_id' => 'int',
		'country_id' => 'int',
		'city_id' => 'int',
		'location_id' => 'int',
		'type_id' => 'int',
		'master_category' => 'int',
		'parent_id' => 'int'
	];

	protected $fillable = [
		'name',
		'f_type',
		'last_updated',
		'priority',
		'is_trash',
		'status',
		'category_id',
		'file_image',
		'section_id',
		'url',
		'country_id',
		'city_id',
		'location_id',
		'show_all',
		'show_all_c',
		'use_under',
		'type_id',
		'master_category',
		'parent_id',
		'user_type',
		'featured',
		'direct_link',
		'beds'
	];

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city_id');
	}

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'type_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'link_id');
	}
}
