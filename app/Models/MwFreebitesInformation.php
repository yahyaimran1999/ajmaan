<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwFreebitesInformation
 *
 * @property int $user_id
 * @property string $f_type
 * @property string $full_name
 * @property string $id_number
 * @property string $address
 * @property int $zip_code
 * @property string $city
 * @property string|null $phone_number
 * @property string $payment_method
 * @property string $email_payment
 * @property string $id_doc
 * @property int|null $country_id
 * @property string|null $shutterstock_id
 * @property string|null $istockphoto_id
 * @property string|null $stock_adobe
 * @property string|null $facebook
 * @property string|null $dribbble
 * @property string|null $pinterest
 * @property string|null $twitter
 *
 * @property MwListingUser $mw_listing_user
 * @property MwCountry|null $mw_country
 *
 * @package App\Models
 */
class MwFreebitesInformation extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_freebites_information';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'zip_code' => 'int',
		'country_id' => 'int'
	];

	protected $fillable = [
		'full_name',
		'id_number',
		'address',
		'zip_code',
		'city',
		'phone_number',
		'payment_method',
		'email_payment',
		'id_doc',
		'country_id',
		'shutterstock_id',
		'istockphoto_id',
		'stock_adobe',
		'facebook',
		'dribbble',
		'pinterest',
		'twitter'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}
}
