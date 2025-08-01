<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdNearestSchool
 *
 * @property int $id
 * @property int $ad_id
 * @property string $name
 * @property string $distance
 * @property string $vicinity
 * @property float $rating
 * @property int $user_ratings_total
 * @property string $status
 * @property string $f_type
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 *
 * @package App\Models
 */
class MwAdNearestSchool extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_nearest_school';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'rating' => 'float',
		'user_ratings_total' => 'int'
	];

	protected $fillable = [
		'ad_id',
		'name',
		'distance',
		'vicinity',
		'rating',
		'user_ratings_total',
		'status',
		'f_type'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
