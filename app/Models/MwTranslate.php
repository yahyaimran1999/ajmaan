<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwTranslate
 *
 * @property int $translate_id
 * @property string $source_tag
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 * @property Collection|MwTranslationDatum[] $mw_translation_data
 *
 * @package App\Models
 */
class MwTranslate extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_translate';
	protected $primaryKey = 'translate_id';
	public $timestamps = false;

	protected $fillable = [
		'source_tag'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'translate_id');
	}

	public function mw_translation_data()
	{
		return $this->hasMany(MwTranslationDatum::class, 'translation_id');
	}
}
