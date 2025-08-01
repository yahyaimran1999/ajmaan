<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMaster
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
 * @property string $show_all
 * @property string $show_all_c
 * @property string $use_under
 * @property int|null $p_master_id
 * @property string|null $short_description
 *
 * @property MwCategory|null $mw_category
 * @property MwSection|null $mw_section
 * @property MwCountry|null $mw_country
 * @property MwMaster|null $mw_master
 * @property Collection|MwAreaGuide[] $mw_area_guides
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwMaster[] $mw_masters
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwMaster extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_master';
	protected $primaryKey = 'master_id';
	public $timestamps = false;

	protected $casts = [
		'last_updated' => 'datetime',
		'priority' => 'int',
		'category_id' => 'int',
		'section_id' => 'int',
		'country_id' => 'int',
		'p_master_id' => 'int'
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
		'show_all',
		'show_all_c',
		'use_under',
		'p_master_id',
		'short_description'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_master()
	{
		return $this->belongsTo(MwMaster::class, 'p_master_id');
	}

	public function mw_area_guides()
	{
		return $this->hasMany(MwAreaGuide::class, 'master_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'm_id2');
	}

	public function mw_masters()
	{
		return $this->hasMany(MwMaster::class, 'p_master_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'master_id');
	}
}
