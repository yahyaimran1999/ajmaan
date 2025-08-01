<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwBlogComment
 *
 * @property int $comment_id
 * @property int $blog_id
 * @property int $user_id
 * @property string|null $comment
 * @property Carbon $date_added
 * @property string $status
 *
 * @property MwArticle $mw_article
 * @property MwListingUser $mw_listing_user
 *
 * @package App\Models
 */
class MwBlogComment extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_blog_comments';
	protected $primaryKey = 'comment_id';
	public $timestamps = false;

	protected $casts = [
		'blog_id' => 'int',
		'user_id' => 'int',
		'date_added' => 'datetime'
	];

	protected $fillable = [
		'blog_id',
		'user_id',
		'comment',
		'date_added',
		'status'
	];

	public function mw_article()
	{
		return $this->belongsTo(MwArticle::class, 'blog_id');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
}
