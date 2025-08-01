<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAvatar
 *
 * @property int $avtar_id
 * @property string $avatar_name
 * @property string $status
 *
 * @package App\Models
 */
class MwAvatar extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_avatar';
	protected $primaryKey = 'avtar_id';
	public $timestamps = false;

	protected $fillable = [
		'avatar_name',
		'status'
	];
}
