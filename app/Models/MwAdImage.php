<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdImage
 *
 * @property int $id
 * @property int $ad_id
 * @property string $image_name
 * @property int|null $priority
 * @property string $isTrash
 * @property string $status
 * @property string $xml_image
 * @property string $image_type
 * @property string $Title
 * @property string $IsMarketingImage
 * @property string $ImageRemarks
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 *
 * @package App\Models
 */
class MwAdImage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_image';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'ad_id',
		'image_name',
		'priority',
		'isTrash',
		'status',
		'xml_image',
		'image_type',
		'Title',
		'IsMarketingImage',
		'ImageRemarks'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
