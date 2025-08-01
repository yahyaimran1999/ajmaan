<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwListingTypeFileld
 *
 * @property int $category_id
 * @property string $listing_type
 *
 * @property MwCategory $mw_category
 *
 * @package App\Models
 */
class MwListingTypeFileld extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_listing_type_filelds';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'category_id' => 'int'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}
}
