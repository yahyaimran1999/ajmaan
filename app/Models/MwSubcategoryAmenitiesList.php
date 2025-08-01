<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSubcategoryAmenitiesList
 *
 * @property int $amenities_id
 * @property int $sub_category_id
 *
 * @property MwAmenity $mw_amenity
 * @property MwSubcategory $mw_subcategory
 *
 * @package App\Models
 */
class MwSubcategoryAmenitiesList extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_subcategory_amenities_list';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'amenities_id' => 'int',
		'sub_category_id' => 'int'
	];

	protected $fillable = [
		'amenities_id',
		'sub_category_id'
	];

	public function mw_amenity()
	{
		return $this->belongsTo(MwAmenity::class, 'amenities_id');
	}

	public function mw_subcategory()
	{
		return $this->belongsTo(MwSubcategory::class, 'sub_category_id');
	}
}
