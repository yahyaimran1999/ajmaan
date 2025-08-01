<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCommonTag
 *
 * @property int $id
 * @property string $conversion_tag
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwCommonTag extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_common_tags';
	public $timestamps = false;

	protected $fillable = [
		'conversion_tag'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'tag_id');
	}
}
