<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwShopNewsletter
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $profess
 * @property int|null $state
 * @property Carbon $date_added
 *
 * @property MwState|null $mw_state
 *
 * @package App\Models
 */
class MwShopNewsletter extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_shop_newsletter';
	public $timestamps = false;

	protected $casts = [
		'state' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'email',
		'profess',
		'state',
		'date_added'
	];

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state');
	}
}
