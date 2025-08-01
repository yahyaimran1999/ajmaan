<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwStatisticSearchList
 *
 * @property int $search_id
 * @property int $user_id
 * @property Carbon $last_updated
 *
 * @property MwListingUser $mw_listing_user
 * @property MwStatisticSearch $mw_statistic_search
 *
 * @package App\Models
 */
class MwStatisticSearchList extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_statistic_search_list';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'search_id' => 'int',
		'user_id' => 'int',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'last_updated'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_statistic_search()
	{
		return $this->belongsTo(MwStatisticSearch::class, 'search_id');
	}
}
