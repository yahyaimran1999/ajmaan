<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSaveSearch
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property int|null $user_id
 * @property Carbon $date_added
 *
 * @property MwListingUser|null $mw_listing_user
 *
 * @package App\Models
 */
class MwSaveSearch extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_save_search';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'title',
		'url',
		'user_id',
		'date_added'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
}
