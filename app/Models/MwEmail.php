<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwEmail
 *
 * @property int $id
 * @property string $subject
 * @property string $message
 * @property Carbon|null $sent_on
 * @property string $cc
 * @property string $bcc
 * @property string $attachments
 * @property int|null $created_by
 * @property int|null $created_by_admin
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string $status
 * @property string $isTrash
 * @property string $type
 * @property string $receipeints
 *
 * @property MwListingUser|null $mw_listing_user
 * @property MwUser|null $mw_user
 *
 * @package App\Models
 */
class MwEmail extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_email';
	public $timestamps = false;

	protected $casts = [
		'sent_on' => 'datetime',
		'created_by' => 'int',
		'created_by_admin' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'subject',
		'message',
		'sent_on',
		'cc',
		'bcc',
		'attachments',
		'created_by',
		'created_by_admin',
		'date_added',
		'last_updated',
		'status',
		'isTrash',
		'type',
		'receipeints'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'created_by');
	}

	public function mw_user()
	{
		return $this->belongsTo(MwUser::class, 'created_by_admin');
	}
}
