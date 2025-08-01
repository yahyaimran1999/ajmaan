<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSellImage
 *
 * @property int $id
 * @property int $sell_id
 * @property string $image
 *
 * @property MwSellHome $mw_sell_home
 *
 * @package App\Models
 */
class MwSellImage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_sell_images';
	public $timestamps = false;

	protected $casts = [
		'sell_id' => 'int'
	];

	protected $fillable = [
		'sell_id',
		'image'
	];

	public function mw_sell_home()
	{
		return $this->belongsTo(MwSellHome::class, 'sell_id');
	}
}
