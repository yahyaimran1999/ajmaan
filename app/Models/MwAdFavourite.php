<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdFavourite
 *
 * @property int $ad_id
 * @property int $user_id
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 * @property MwListingUser $mw_listing_user
 *
 * @package App\Models
 */
class MwAdFavourite extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_favourite';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'user_id' => 'int'
	];

    protected $fillable = [
        'user_id',
        'ad_id'
    ];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
}
