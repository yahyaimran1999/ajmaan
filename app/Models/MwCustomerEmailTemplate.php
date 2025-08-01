<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCustomerEmailTemplate
 *
 * @property int $template_id
 * @property string $template_uid
 * @property int $customer_id
 * @property string $name
 * @property string $content
 * @property string $content_hash
 * @property string $create_screenshot
 * @property string|null $screenshot
 * @property string $inline_css
 * @property string $minify
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string|null $subject
 * @property string|null $receiver_list
 * @property string $channel
 *
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 *
 * @package App\Models
 */
class MwCustomerEmailTemplate extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_customer_email_template';
	protected $primaryKey = 'template_id';
	public $timestamps = false;

	protected $casts = [
		'customer_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'template_uid',
		'customer_id',
		'name',
		'content',
		'content_hash',
		'create_screenshot',
		'screenshot',
		'inline_css',
		'minify',
		'date_added',
		'last_updated',
		'subject',
		'receiver_list',
		'channel'
	];

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'template_id');
	}
}
