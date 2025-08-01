<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPaymentOption
 *
 * @property int $id
 * @property string $name
 * @property string $isTrash
 * @property string $status
 * @property Carbon $added_date
 * @property string $show_on_order_form
 * @property string $force_one_time_payments
 * @property string $paypal_email
 * @property string $force_subscriptions
 * @property string $require_shipping_address
 * @property string $client_addres_matching
 * @property string $api_username
 * @property string $api_password
 * @property string $api_signature
 * @property string $bank_transfer_instructions
 *
 * @package App\Models
 */
class MwPaymentOption extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_payment_option';
	public $timestamps = false;

	protected $casts = [
		'added_date' => 'datetime'
	];

	protected $hidden = [
		'api_password'
	];

	protected $fillable = [
		'name',
		'isTrash',
		'status',
		'added_date',
		'show_on_order_form',
		'force_one_time_payments',
		'paypal_email',
		'force_subscriptions',
		'require_shipping_address',
		'client_addres_matching',
		'api_username',
		'api_password',
		'api_signature',
		'bank_transfer_instructions'
	];
}
