<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCommentReplay
 *
 * @property int $comment_id
 * @property int|null $user_id
 * @property string|null $replay
 * @property string $status
 * @property Carbon $date_added
 * @property int|null $replay_id
 *
 * @package App\Models
 */
class MwCommentReplay extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_comment_replay';
	protected $primaryKey = 'comment_id';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'date_added' => 'datetime',
		'replay_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'replay',
		'status',
		'date_added',
		'replay_id'
	];
}
