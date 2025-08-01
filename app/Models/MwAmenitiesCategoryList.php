<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAmenitiesCategoryList
 *
 * @property int $category_id
 * @property int $amenities_id
 *
 * @property MwAmenity $mw_amenity
 * @property MwCategory $mw_category
 *
 * @package App\Models
 */
class MwAmenitiesCategoryList extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_amenities_category_list';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int',
		'amenities_id' => 'int'
	];

	protected $fillable = [
		'category_id',
		'amenities_id'
	];

	public function mw_amenity()
	{
		return $this->belongsTo(MwAmenity::class, 'amenities_id');
	}

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}
}
