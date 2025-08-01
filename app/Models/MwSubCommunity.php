<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwSubCommunity
 *
 * @property int $sub_community_id
 * @property string $sub_community_name
 * @property int|null $community_id
 *
 * @property MwCommunity|null $mw_community
 * @property Collection|MwPlaceAnAd[] $mw_place_an_ads
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwSubCommunity extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_sub_community';
	protected $primaryKey = 'sub_community_id';
	public $timestamps = false;

	protected $casts = [
		'community_id' => 'int'
	];

	protected $fillable = [
		'sub_community_name',
		'community_id'
	];

	public function mw_community()
	{
		return $this->belongsTo(MwCommunity::class, 'community_id');
	}

	public function mw_place_an_ads()
	{
		return $this->hasMany(MwPlaceAnAd::class, 'sub_community_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'sub_community_id');
	}
}
