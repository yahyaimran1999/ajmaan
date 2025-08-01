<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwPopularCity
 *
 * @property int $id
 * @property string $cities
 * @property string $title
 * @property int|null $priority
 * @property string|null $image
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwPopularCity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_popular_cities';
	public $timestamps = false;

	protected $casts = [
		'priority' => 'int'
	];

	protected $fillable = [
		'cities',
		'title',
		'priority',
		'image'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'popular_id');
	}
}
