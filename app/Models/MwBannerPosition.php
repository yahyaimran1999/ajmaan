<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBannerPosition
 *
 * @property int $position_id
 * @property string $position_name
 * @property string $status
 * @property string $isTrash
 * @property string $banner_width
 * @property string $banner_height
 * @property string $slider
 *
 * @property Collection|MwBanner[] $mw_banners
 *
 * @package App\Models
 */
class MwBannerPosition extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_banner_position';
	protected $primaryKey = 'position_id';
	public $timestamps = false;

	protected $fillable = [
		'position_name',
		'status',
		'isTrash',
		'banner_width',
		'banner_height',
		'slider'
	];

	public function mw_banners()
	{
		return $this->hasMany(MwBanner::class, 'position_id');
	}
}
