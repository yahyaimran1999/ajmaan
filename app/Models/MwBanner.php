<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBanner
 *
 * @property int $banner_id
 * @property int|null $position_id
 * @property string $image
 * @property string $status
 * @property string $isTrash
 * @property int|null $priority
 * @property string $link_url
 * @property string $ad_type
 * @property string $script
 * @property int|null $country_id
 * @property string $f_type
 * @property string|null $title
 * @property string|null $description
 * @property string $channel
 *
 * @property MwBannerPosition|null $mw_banner_position
 * @property MwCountry|null $mw_country
 * @property Collection|MwAdvertisementItem[] $mw_advertisement_items
 *
 * @package App\Models
 */
class MwBanner extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_banner';
	protected $primaryKey = 'banner_id';
	public $timestamps = false;

	protected $casts = [
		'position_id' => 'int',
		'priority' => 'int',
		'country_id' => 'int'
	];

	protected $fillable = [
		'position_id',
		'image',
		'status',
		'isTrash',
		'priority',
		'link_url',
		'ad_type',
		'script',
		'country_id',
		'f_type',
		'title',
		'description',
		'channel'
	];

	public function mw_banner_position()
	{
		return $this->belongsTo(MwBannerPosition::class, 'position_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_advertisement_items()
	{
		return $this->hasMany(MwAdvertisementItem::class, 'banner_id');
	}
}
