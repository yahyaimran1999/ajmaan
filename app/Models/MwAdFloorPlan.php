<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdFloorPlan
 *
 * @property int $floor_id
 * @property int $ad_id
 * @property string|null $floor_title
 * @property string|null $floor_file
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 *
 * @package App\Models
 */
class MwAdFloorPlan extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_floor_plan';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int'
	];

	protected $fillable = [
		'ad_id',
		'floor_title',
		'floor_file'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
