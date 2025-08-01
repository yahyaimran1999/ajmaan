<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAmenity
 *
 * @property int $amenities_id
 * @property string $amenities_name
 * @property string $isTrash
 * @property string $status
 * @property int $priority
 * @property string $Title
 * @property string $userd_all
 *
 * @property MwAdAmenity|null $mw_ad_amenity
 * @property MwAmenitiesCategoryList|null $mw_amenities_category_list
 * @property MwSubcategoryAmenitiesList|null $mw_subcategory_amenities_list
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwAmenity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_amenities';
	protected $primaryKey = 'amenities_id';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'amenities_name',
		'isTrash',
		'status',
		'priority',
		'Title',
		'userd_all'
	];

	public function mw_ad_amenity()
	{
		return $this->hasOne(MwAdAmenity::class, 'amenities_id');
	}

	public function mw_amenities_category_list()
	{
		return $this->hasOne(MwAmenitiesCategoryList::class, 'amenities_id');
	}

	public function mw_subcategory_amenities_list()
	{
		return $this->hasOne(MwSubcategoryAmenitiesList::class, 'amenities_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'amenities_id');
	}
}
