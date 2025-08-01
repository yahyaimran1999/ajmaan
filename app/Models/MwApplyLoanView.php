<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwApplyLoanView
 *
 * @property int $contact_id
 * @property int $user_id
 * @property string $status
 *
 * @property MwLoanApplication $mw_loan_application
 * @property MwUser $mw_user
 *
 * @package App\Models
 */
class MwApplyLoanView extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_apply_loan_view';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'contact_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'status'
	];

	public function mw_loan_application()
	{
		return $this->belongsTo(MwLoanApplication::class, 'contact_id');
	}

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'user_id');
	}
}
