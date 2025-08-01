<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUser
 *
 * @property int $user_id
 * @property string $user_uid
 * @property int|null $language_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string $password
 * @property string $timezone
 * @property string $removable
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $group_id
 *
 * @property MwLanguage|null $mw_language
 * @property MwUserGroup|null $mw_user_group
 * @property Collection|MwApplyLoanView[] $mw_apply_loan_views
 * @property Collection|MwContactViewAdmin[] $mw_contact_view_admins
 * @property Collection|MwEmail[] $mw_emails
 * @property Collection|MwPricePlanOrder[] $mw_price_plan_orders
 * @property Collection|MwPricePlanOrderNote[] $mw_price_plan_order_notes
 * @property Collection|MwUserPackage[] $mw_user_packages
 * @property Collection|MwUserPasswordReset[] $mw_user_password_resets
 *
 * @package App\Models
 */
class MwUser extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_user';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	protected $casts = [
		'language_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'group_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'user_uid',
		'language_id',
		'first_name',
		'last_name',
		'email',
		'password',
		'timezone',
		'removable',
		'status',
		'date_added',
		'last_updated',
		'group_id'
	];

	public function mw_language()
	{
		return $this->belongsTo(MwLanguage::class, 'language_id');
	}

	public function mw_user_group()
	{
		return $this->belongsTo(MwUserGroup::class, 'group_id');
	}

	public function mw_apply_loan_views()
	{
		return $this->hasMany(MwApplyLoanView::class, 'user_id');
	}

	public function mw_contact_view_admins()
	{
		return $this->hasMany(MwContactViewAdmin::class, 'user_id');
	}

	public function mw_emails()
	{
		return $this->hasMany(MwEmail::class, 'created_by_admin');
	}

	public function mw_price_plan_orders()
	{
		return $this->hasMany(MwPricePlanOrder::class, 'deleted_by');
	}

	public function mw_price_plan_order_notes()
	{
		return $this->hasMany(MwPricePlanOrderNote::class, 'user_id');
	}

	public function mw_user_packages()
	{
		return $this->hasMany(MwUserPackage::class, 'deleted_by');
	}

	public function mw_user_password_resets()
	{
		return $this->hasMany(MwUserPasswordReset::class, 'user_id');
	}
}
