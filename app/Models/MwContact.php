<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwContact
 *
 * @property int $contact_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $message
 * @property int $ad_id
 * @property Carbon $added_date
 *
 * @package App\Models
 */
class MwContact extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_contact';
	protected $primaryKey = 'contact_id';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'added_date' => 'datetime'
	];

	protected $fillable = [
		'name',
		'email',
		'phone',
		'message',
		'ad_id',
		'added_date'
	];
}
