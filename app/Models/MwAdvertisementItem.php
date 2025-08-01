<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdvertisementItem
 *
 * @property int $row_id
 * @property int $layout_id
 * @property string $section
 * @property int|null $ad_id
 * @property int|null $banner_id
 * @property int|null $article_id
 * @property int $row_number
 *
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property MwBanner|null $mw_banner
 * @property MwArticle|null $mw_article
 * @property MwAdvertisementLayout $mw_advertisement_layout
 *
 * @package App\Models
 */
class MwAdvertisementItem extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_advertisement_items';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'row_id' => 'int',
		'layout_id' => 'int',
		'ad_id' => 'int',
		'banner_id' => 'int',
		'article_id' => 'int',
		'row_number' => 'int'
	];

	protected $fillable = [
		'section',
		'ad_id',
		'banner_id',
		'article_id',
		'row_number'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_banner()
	{
		return $this->belongsTo(MwBanner::class, 'banner_id');
	}

	public function mw_article()
	{
		return $this->belongsTo(MwArticle::class, 'article_id');
	}

	public function mw_advertisement_layout()
	{
		return $this->belongsTo(MwAdvertisementLayout::class, 'layout_id');
	}
}
