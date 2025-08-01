<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwContactViewAdmin
 *
 * @property int $contact_id
 * @property int $user_id
 * @property string|null $status
 *
 * @property MwContactU $mw_contact_u
 * @property MwUser $mw_user
 *
 * @package App\Models
 */
class MwContactViewAdmin extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_contact_view_admin';
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

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'user_id');
	}
}
