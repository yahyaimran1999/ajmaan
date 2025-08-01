<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwContactU
 *
 * @property int $id
 * @property int $type
 * @property string $email
 * @property string $name
 * @property string $meassage
 * @property string $city
 * @property Carbon $date
 * @property string $phone
 * @property string $url
 * @property Carbon $date_added
 * @property int|null $ad_id
 * @property string $contact_type
 * @property string $w_talk
 * @property int|null $user_id
 * @property string|null $considering
 * @property string $is_read
 * @property string|null $i_am
 * @property int|null $m_id
 * @property int|null $m_id2
 * @property int|null $city_id
 * @property string|null $is_p
 * @property Carbon|null $m_date
 * @property int|null $m_id3
 * @property int|null $guide_id
 * @property string $channel
 * @property int|null $state_id
 * @property int|null $m_id4
 * @property int|null $m_id5
 * @property int|null $p_type
 * @property int|null $m_id6
 * @property int|null $send_by
 *
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property MwListingUser|null $mw_listing_user
 * @property MwMaster|null $mw_master
 * @property MwCity|null $mw_city
 * @property MwContactU|null $mw_contact_u
 * @property MwAreaGuide|null $mw_area_guide
 * @property MwState|null $mw_state
 * @property MwAdPropertyType|null $mw_ad_property_type
 * @property Collection|MwContactRead[] $mw_contact_reads
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwContactViewAdmin[] $mw_contact_view_admins
 *
 * @package App\Models
 */
class MwContactU extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_contact_us';
	public $timestamps = false;

	protected $casts = [
		'type' => 'int',
		'date' => 'datetime',
		'date_added' => 'datetime',
		'ad_id' => 'int',
		'user_id' => 'int',
		'm_id' => 'int',
		'm_id2' => 'int',
		'city_id' => 'int',
		'm_date' => 'datetime',
		'm_id3' => 'int',
		'guide_id' => 'int',
		'state_id' => 'int',
		'm_id4' => 'int',
		'm_id5' => 'int',
		'p_type' => 'int',
		'm_id6' => 'int',
		'send_by' => 'int'
	];

	protected $fillable = [
		'type',
		'email',
		'name',
		'meassage',
		'city',
		'date',
		'phone',
		'url',
		'date_added',
		'ad_id',
		'contact_type',
		'w_talk',
		'user_id',
		'considering',
		'is_read',
		'i_am',
		'm_id',
		'm_id2',
		'city_id',
		'is_p',
		'm_date',
		'm_id3',
		'guide_id',
		'channel',
		'state_id',
		'm_id4',
		'm_id5',
		'p_type',
		'm_id6',
		'send_by'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_master()
	{
		return $this->belongsTo(MwMaster::class, 'm_id2');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city_id');
	}

	public function mw_contact_u()
	{
		return $this->belongsTo(MwContactU::class, 'm_id3');
	}

	public function mw_area_guide()
	{
		return $this->belongsTo(MwAreaGuide::class, 'guide_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_ad_property_type()
	{
		return $this->belongsTo(MwAdPropertyType::class, 'p_type');
	}

	public function mw_contact_reads()
	{
		return $this->hasMany(MwContactRead::class, 'contact_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'm_id3');
	}

	public function mw_contact_view_admins()
	{
		return $this->hasMany(MwContactViewAdmin::class, 'contact_id');
	}
}
