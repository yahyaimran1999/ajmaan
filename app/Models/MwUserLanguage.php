<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserLanguage
 *
 * @property int $user_id
 * @property int $language_id
 *
 * @property MwListingUser $mw_listing_user
 * @property MwLanguage $mw_language
 *
 * @package App\Models
 */
class MwUserLanguage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_languages';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'language_id' => 'int'
	];

	protected $fillable = [
        'user_id',
        'language_id'
    ];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_language()
	{
		return $this->belongsTo(MwLanguage::class, 'language_id');
	}
}
