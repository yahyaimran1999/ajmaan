<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdvertisementLayout
 *
 * @property int $advertisemen_id
 * @property string $advertising_title
 * @property string $layout
 * @property string $isTrash
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $priority
 * @property int $max_items
 * @property string $header_text
 * @property string $position_banner
 * @property string $removable
 *
 * @property Collection|MwAdvertisementItem[] $mw_advertisement_items
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwAdvertisementLayout extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_advertisement_layout';
	protected $primaryKey = 'advertisemen_id';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'priority' => 'int',
		'max_items' => 'int'
	];

	protected $fillable = [
		'advertising_title',
		'layout',
		'isTrash',
		'status',
		'date_added',
		'last_updated',
		'priority',
		'max_items',
		'header_text',
		'position_banner',
		'removable'
	];

	public function mw_advertisement_items()
	{
		return $this->hasMany(MwAdvertisementItem::class, 'layout_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'advertisemen_id');
	}
}
