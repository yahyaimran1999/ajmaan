<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwArticleView
 *
 * @property int $article_id
 * @property string $ip_address
 * @property Carbon $date_added
 *
 * @property MwArticle $mw_article
 *
 * @package App\Models
 */
class MwArticleView extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_article_view';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'article_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'article_id',
		'ip_address',
		'date_added'
	];

	public function mw_article()
	{
		return $this->belongsTo(MwArticle::class, 'article_id');
	}
}
