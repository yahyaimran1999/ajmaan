<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwMetaTag
 *
 * @property int $id
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $url
 *
 * @package App\Models
 */
class MwMetaTag extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_meta_tags';
	public $timestamps = false;

	protected $fillable = [
		'meta_title',
		'meta_keyword',
		'meta_description',
		'url'
	];
}
