<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPropertyValuation
 *
 * @property int $id
 * @property string|null $full_name
 * @property string $email
 * @property string|null $contact_number
 * @property string|null $purpose
 * @property string|null $message
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $m_id2
 * @property int|null $m_id
 * @property string|null $t_loc
 * @property string|null $f_loc
 * @property float|null $f_latitude
 * @property float|null $f_longitude
 * @property float|null $t_latitude
 * @property float|null $t_longitude
 *
 * @package App\Models
 */
class MwPropertyValuation extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_property_valuation';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'm_id2' => 'int',
		'm_id' => 'int',
		'f_latitude' => 'float',
		'f_longitude' => 'float',
		't_latitude' => 'float',
		't_longitude' => 'float'
	];

	protected $fillable = [
		'full_name',
		'email',
		'contact_number',
		'purpose',
		'message',
		'date_added',
		'last_updated',
		'm_id2',
		'm_id',
		't_loc',
		'f_loc',
		'f_latitude',
		'f_longitude',
		't_latitude',
		't_longitude'
	];
}
