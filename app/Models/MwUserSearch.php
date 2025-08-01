<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserSearch
 *
 * @property int $user_id
 * @property string $url
 * @property Carbon $date_added
 *
 * @property MwListingUser $mw_listing_user
 *
 * @package App\Models
 */
class MwUserSearch extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user_searches';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'url'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
}
