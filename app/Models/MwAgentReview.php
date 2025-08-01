<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwAgentReview
 *
 * @property int $review_id
 * @property int|null $agent_id
 * @property int $rating
 * @property string|null $review
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int|null $property_type
 * @property string $location
 * @property string $when_interact
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property string|null $sect
 * @property string|null $property_link
 * @property string $status
 * @property int|null $user_id
 *
 * @property MwListingUser|null $mw_listing_user
 *
 * @package App\Models
 */
class MwAgentReview extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_agent_review';
	protected $primaryKey = 'review_id';
	public $timestamps = false;

	protected $casts = [
		'agent_id' => 'int',
		'rating' => 'int',
		'property_type' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'user_id' => 'int'
	];

	protected $fillable = [
		'agent_id',
		'rating',
		'review',
		'name',
		'email',
		'phone',
		'property_type',
		'location',
		'when_interact',
		'date_added',
		'last_updated',
		'sect',
		'property_link',
		'status',
		'user_id'
	];

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}
	public function mw_listing_user_agent()
	{
		return $this->belongsTo(MwListingUser::class, 'agent_id');
	}
}
