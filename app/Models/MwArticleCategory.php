<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwArticleCategory
 *
 * @property int $category_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $status
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $f_type
 * @property int|null $priority
 * @property string|null $file_image
 * @property string $channel
 * @property string|null $o_description
 *
 * @property MwArticleCategory|null $mw_article_category
 * @property Collection|MwArticleCategory[] $mw_article_categories
 * @property Collection|MwArticleToCategory[] $mw_article_to_categories
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwArticleCategory extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_article_category';
	protected $primaryKey = 'category_id';
	public $timestamps = false;

	protected $casts = [
		'parent_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'priority' => 'int'
	];

	protected $fillable = [
		'parent_id',
		'name',
		'slug',
		'description',
		'status',
		'date_added',
		'last_updated',
		'f_type',
		'priority',
		'file_image',
		'channel',
		'o_description'
	];

	public function mw_article_category()
	{
		return $this->belongsTo(MwArticleCategory::class, 'parent_id');
	}

	public function mw_article_categories()
	{
		return $this->hasMany(MwArticleCategory::class, 'parent_id');
	}

	public function mw_article_to_categories()
	{
		return $this->hasMany(MwArticleToCategory::class, 'category_id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'article_category_id');
	}
}
