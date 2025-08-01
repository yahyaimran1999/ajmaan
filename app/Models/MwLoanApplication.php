<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwLoanApplication
 *
 * @property int $id
 * @property int $type
 * @property string $email
 * @property string $name
 * @property string $meassage
 * @property string $city
 * @property Carbon $date
 * @property string $phone
 * @property string $url
 * @property Carbon $date_added
 * @property int|null $ad_id
 * @property int|null $country_id
 * @property string|null $ip_address
 * @property string $is_read
 * @property int|null $bank_id
 * @property string|null $down_payment
 * @property string|null $total_loan
 * @property string|null $loan_period
 * @property string|null $interest_rate
 * @property string|null $monthly_income
 * @property string $reference
 * @property int|null $user_id
 * @property string|null $category
 *
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property MwCountry|null $mw_country
 * @property MwBank|null $mw_bank
 * @property MwListingUser|null $mw_listing_user
 * @property Collection|MwApplyLoanView[] $mw_apply_loan_views
 *
 * @package App\Models
 */
class MwLoanApplication extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_loan_application';
	public $timestamps = false;

	protected $casts = [
		'type' => 'int',
		'date' => 'datetime',
		'date_added' => 'datetime',
		'ad_id' => 'int',
		'country_id' => 'int',
		'bank_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'type',
		'email',
		'name',
		'meassage',
		'city',
		'date',
		'phone',
		'url',
		'date_added',
		'ad_id',
		'country_id',
		'ip_address',
		'is_read',
		'bank_id',
		'down_payment',
		'total_loan',
		'loan_period',
		'interest_rate',
		'monthly_income',
		'reference',
		'user_id',
		'category'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_bank()
	{
		return $this->belongsTo(MwBank::class, 'bank_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_apply_loan_views()
	{
		return $this->hasMany(MwApplyLoanView::class, 'contact_id');
	}
}
