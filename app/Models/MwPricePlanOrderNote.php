<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPricePlanOrderNote
 *
 * @property int $note_id
 * @property int|null $order_id
 * @property int|null $customer_id
 * @property int|null $user_id
 * @property string $note
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @property MwListingUser|null $mw_listing_user
 * @property MwUser|null $mw_user
 *
 * @package App\Models
 */
class MwPricePlanOrderNote extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_price_plan_order_note';
	protected $primaryKey = 'note_id';
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'customer_id' => 'int',
		'user_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'order_id',
		'customer_id',
		'user_id',
		'note',
		'date_added',
		'last_updated'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'customer_id');
	}

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'user_id');
	}
}
