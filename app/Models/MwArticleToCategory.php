<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwArticleToCategory
 *
 * @property int $article_id
 * @property int $category_id
 *
 * @property MwArticle $mw_article
 * @property MwArticleCategory $mw_article_category
 *
 * @package App\Models
 */
class MwArticleToCategory extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_article_to_category';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'article_id' => 'int',
		'category_id' => 'int'
	];

	public function mw_article()
	{
		return $this->belongsTo(MwArticle::class, 'article_id');
	}

	public function mw_article_category()
	{
		return $this->belongsTo(MwArticleCategory::class, 'category_id');
	}
}
