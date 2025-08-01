<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwContactRead
 *
 * @property int $contact_id
 * @property int $user_id
 * @property string $status
 *
 * @property MwContactU $mw_contact_u
 * @property MwListingUser $mw_listing_user
 *
 * @package App\Models
 */
class MwContactRead extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_contact_read';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'contact_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'status'
	];

	public function mw_contact_u()
	{
		return $this->belongsTo(MwContactU::class, 'contact_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
}
