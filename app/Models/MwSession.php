<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSession
 *
 * @property string $id
 * @property int|null $expire
 * @property string|null $data
 *
 * @package App\Models
 */
class MwSession extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_session';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'expire' => 'int'
	];

	protected $fillable = [
		'expire',
		'data'
	];
}
