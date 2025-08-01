<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwTranslationDatum
 *
 * @property int $translation_id
 * @property string $lang
 * @property string $message
 *
 * @property MwTranslate $mw_translate
 *
 * @package App\Models
 */
class MwTranslationDatum extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_translation_data';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'translation_id' => 'int'
	];

	protected $fillable = [
		'message'
	];

	public function mw_translate()
	{
		return $this->belongsTo(MwTranslate::class, 'translation_id');
	}
}
