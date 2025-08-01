<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwStatisticSearch
 *
 * @property int $id
 * @property string $url
 * @property string|null $title
 *
 * @property Collection|MwStatisticSearchList[] $mw_statistic_search_lists
 *
 * @package App\Models
 */
class MwStatisticSearch extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_statistic_search';
	public $timestamps = false;

	protected $fillable = [
		'url',
		'title'
	];

	public function mw_statistic_search_lists()
	{
		return $this->hasMany(MwStatisticSearchList::class, 'search_id');
	}
}
