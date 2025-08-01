<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSearchlist
 *
 * @property int $id
 * @property int $user_id
 * @property string $referal
 * @property string $alert
 * @property int $category_id
 * @property int $sub_category_id
 * @property int $country_id
 * @property int $state_id
 * @property int $title
 * @property Carbon $date
 *
 * @package App\Models
 */
class MwSearchlist extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_searchlist';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'category_id' => 'int',
		'sub_category_id' => 'int',
		'country_id' => 'int',
		'state_id' => 'int',
		'title' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
		'user_id',
		'referal',
		'alert',
		'category_id',
		'sub_category_id',
		'country_id',
		'state_id',
		'title',
		'date'
	];
}
