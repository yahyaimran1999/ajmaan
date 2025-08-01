<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBank
 *
 * @property int $bank_id
 * @property string $bank_name
 * @property int|null $interest_rate
 * @property int|null $down_payment
 * @property string|null $logo
 * @property string|null $terms
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $is_trash
 * @property string $status
 * @property string $show_all
 * @property string $f_type
 * @property string|null $slug
 * @property int|null $priority
 *
 * @property Collection|MwLoanApplication[] $mw_loan_applications
 *
 * @package App\Models
 */
class MwBank extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_bank';
	protected $primaryKey = 'bank_id';
	public $timestamps = false;

	protected $casts = [
		'interest_rate' => 'int',
		'down_payment' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'priority' => 'int'
	];

	protected $fillable = [
		'bank_name',
		'interest_rate',
		'down_payment',
		'logo',
		'terms',
		'date_added',
		'last_updated',
		'is_trash',
		'status',
		'show_all',
		'f_type',
		'slug',
		'priority'
	];

	public function mw_loan_applications()
	{
		return $this->hasMany(MwLoanApplication::class, 'bank_id');
	}
}
