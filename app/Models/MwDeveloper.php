<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwDeveloper
 *
 * @property int $developer_id
 * @property string $developer_name
 * @property string $description
 * @property string $logo
 * @property string $status
 * @property string $isTrash
 * @property Carbon $added_date
 * @property string $link_url
 * @property int|null $priority
 * @property string|null $slug
 * @property string $featured
 * @property int|null $state_id
 * @property int|null $city_id
 * @property string $channel
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $show_home
 * @property string|null $website
 *
 * @property MwState|null $mw_state
 * @property MwCity|null $mw_city
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 *
 * @package App\Models
 */
class MwDeveloper extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_developers';
	protected $primaryKey = 'developer_id';
	public $timestamps = false;

	protected $casts = [
		'added_date' => 'datetime',
		'priority' => 'int',
		'state_id' => 'int',
		'city_id' => 'int'
	];

	protected $fillable = [
		'developer_name',
		'description',
		'logo',
		'status',
		'isTrash',
		'added_date',
		'link_url',
		'priority',
		'slug',
		'featured',
		'state_id',
		'city_id',
		'channel',
		'phone',
		'email',
		'address',
		'show_home',
		'website'
	];

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'developer_id');
	}
}
