<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwExternalLink
 *
 * @property int $id
 * @property string $title
 * @property string $link
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $priority
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwExternalLink extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_external_link';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'priority' => 'int'
	];

	protected $fillable = [
		'title',
		'link',
		'status',
		'date_added',
		'last_updated',
		'priority'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'external_id');
	}
}
