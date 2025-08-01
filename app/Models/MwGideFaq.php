<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwGideFaq
 *
 * @property int $faq_id
 * @property int|null $guide_id
 * @property string|null $title
 * @property string|null $file
 * @property Carbon $last_updated
 *
 * @property MwAreaGuide|null $mw_area_guide
 *
 * @package App\Models
 */
class MwGideFaq extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_gide_faq';
	protected $primaryKey = 'faq_id';
	public $timestamps = false;

	protected $casts = [
		'guide_id' => 'int',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'guide_id',
		'title',
		'file',
		'last_updated'
	];

	public function mw_area_guide()
	{
		return $this->belongsTo(MwAreaGuide::class, 'guide_id');
	}
}
