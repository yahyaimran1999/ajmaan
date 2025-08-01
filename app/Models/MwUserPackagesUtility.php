<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserPackagesUtility
 *
 * @property int $id
 * @property int $ad_id
 * @property int $package_id
 * @property string|null $f_type
 * @property Carbon $date_added
 *
 * @property MwUserPackage $mw_user_package
 * @property MwPlaceAnAd $mw_place_an_ad
 *
 * @package App\Models
 */
class MwUserPackagesUtility extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_packages_utility';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'package_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'ad_id',
		'package_id',
		'f_type',
		'date_added'
	];

	public function mw_user_package()
	{
		return $this->belongsTo(MwUserPackage::class, 'package_id');
	}

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
