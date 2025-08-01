<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwReportListing
 *
 * @property int $id
 * @property int $ad_id
 * @property string $type
 * @property string $details
 * @property int $user_id
 * @property Carbon $added_date
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 *
 * @package App\Models
 */
class MwReportListing extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_report_listing';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'user_id' => 'int',
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'ad_id',
		'type',
		'details',
		'user_id',
		'added_date'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
