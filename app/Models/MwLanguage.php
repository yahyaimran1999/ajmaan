<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwLanguage
 *
 * @property int $language_id
 * @property string $name
 * @property string $language_code
 * @property string|null $region_code
 * @property string $is_default
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 * @property Collection|MwUser[] $mw_users
 * @property Collection|MwUserLanguage[] $mw_user_languages
 *
 * @package App\Models
 */
class MwLanguage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_language';
	protected $primaryKey = 'language_id';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'name',
		'language_code',
		'region_code',
		'is_default',
		'date_added',
		'last_updated'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'language_id');
	}

	public function mw_users()
	{
		return $this->hasMany(MwUser::class, 'language_id');
	}

	public function mw_user_languages()
	{
		return $this->hasMany(MwUserLanguage::class, 'language_id');
	}
}
