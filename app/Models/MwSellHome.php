<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSellHome
 *
 * @property int $sell_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $address_field
 * @property string|null $location_latitude
 * @property string|null $location_longitude
 * @property string $status
 * @property string $isTrash
 * @property Carbon|null $date_added
 * @property Carbon|null $last_updated
 * @property string|null $details
 * @property string $is_read
 * @property string|null $user_type
 * @property string|null $doc
 * @property int|null $state_id
 * @property int|null $section_id
 * @property int|null $condition_id
 * @property int|null $listing_type
 * @property float|null $from_price
 * @property float|null $to_price
 * @property string $f_type
 * @property int|null $list_type_main
 * @property string|null $s1
 * @property string|null $s2
 * @property int|null $s3
 * @property string|null $s4
 * @property string|null $s5
 *
 * @property MwCategory|null $mw_category
 * @property MwCity|null $mw_city
 * @property Collection|MwSellImage[] $mw_sell_images
 *
 * @package App\Models
 */
class MwSellHome extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_sell_home';
	protected $primaryKey = 'sell_id';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'state_id' => 'int',
		'section_id' => 'int',
		'condition_id' => 'int',
		'listing_type' => 'int',
		'from_price' => 'float',
		'to_price' => 'float',
		'list_type_main' => 'int',
		's3' => 'int'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'phone_number',
		'address_field',
		'location_latitude',
		'location_longitude',
		'status',
		'isTrash',
		'date_added',
		'last_updated',
		'details',
		'is_read',
		'user_type',
		'doc',
		'state_id',
		'section_id',
		'condition_id',
		'listing_type',
		'from_price',
		'to_price',
		'f_type',
		'list_type_main',
		's1',
		's2',
		's3',
		's4',
		's5'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'list_type_main');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'state_id');
	}

	public function mw_sell_images()
	{
		return $this->hasMany(MwSellImage::class, 'sell_id');
	}
}
