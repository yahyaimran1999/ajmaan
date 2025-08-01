<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwArticle
 *
 * @property int $article_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $page_title
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $f_type
 * @property string|null $main_image
 * @property string $can_d
 * @property string|null $featured
 * @property string $channel
 * @property string|null $y_u
 *
 * @property Collection|MwAdvertisementItem[] $mw_advertisement_items
 * @property Collection|MwArticleToCategory[] $mw_article_to_categories
 * @property MwArticleView|null $mw_article_view
 * @property Collection|MwBlogComment[] $mw_blog_comments
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwArticle extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_article';
	protected $primaryKey = 'article_id';
	public $timestamps = false;

	protected $casts = [
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'title',
		'slug',
		'content',
		'status',
		'date_added',
		'last_updated',
		'page_title',
		'meta_title',
		'meta_description',
		'meta_keywords',
		'f_type',
		'main_image',
		'can_d',
		'featured',
		'channel',
		'y_u'
	];

	public function mw_advertisement_items()
	{
		return $this->hasMany(MwAdvertisementItem::class, 'article_id');
	}

	public function mw_article_to_categories()
	{
		return $this->hasMany(MwArticleToCategory::class, 'article_id');
	}

	public function mw_article_view()
	{
		return $this->hasOne(MwArticleView::class, 'article_id');
	}

	public function mw_blog_comments()
	{
		return $this->hasMany(MwBlogComment::class, 'blog_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'article_id');
	}
}
