<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwGuideImage
 *
 * @property int $id
 * @property int|null $guide_id
 * @property string $image_name
 * @property int $priority
 * @property string $isTrash
 * @property string $status
 *
 * @property MwAreaGuide|null $mw_area_guide
 *
 * @package App\Models
 */
class MwGuideImage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_guide_images';
	public $timestamps = false;

	protected $casts = [
		'guide_id' => 'int',
		'priority' => 'int'
	];

	protected $fillable = [
		'guide_id',
		'image_name',
		'priority',
		'isTrash',
		'status'
	];

	public function mw_area_guide()
	{
		return $this->belongsTo(MwAreaGuide::class, 'guide_id');
	}
}
