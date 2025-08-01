<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAdFaq
 *
 * @property int $faq_id
 * @property int|null $ad_id
 * @property string|null $title
 * @property string|null $file
 * @property Carbon $last_updated
 *
 * @property MwPlaceAnAd|null $mw_place_an_ad
 *
 * @package App\Models
 */
class MwAdFaq extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_ad_faq';
	protected $primaryKey = 'faq_id';
	public $timestamps = false;

	protected $casts = [
		'ad_id' => 'int',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'ad_id',
		'title',
		'file',
		'last_updated'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}
}
