<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwWatchlist
 *
 * @property int $id
 * @property int $user_id
 * @property int $ad_id
 * @property int $category_id
 * @property Carbon $added_date
 *
 * @package App\Models
 */
class MwWatchlist extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_watchlist';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'ad_id' => 'int',
		'category_id' => 'int',
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'category_id',
		'added_date'
	];
}
