<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Http\Resources\MwStateResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Class MwStatisticsPage
 *
 * @property int $pid
 * @property string $ip
 * @property Carbon $date
 * @property int $count
 * @property int|null $user_id
 *
 * @property MwPlaceAnAd $mw_place_an_ad
 * @property MwListingUser|null $mw_listing_user
 *
 * @package App\Models
 */
class MwStatisticsPage extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_statistics_page';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'pid' => 'int',
		'ip' => 'binary',
		'date' => 'datetime',
		'count' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'count',
		'user_id'
	];

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'pid');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

    /**
     * Get page view count for a specific time period
     *
     * @param string $period ('30day', '7day', 'today')
     * @param int|null $pid
     * @return int
     */
     public static function pageCount($duration = '', $pid = null)
    {
        try {
            $userId = Auth::id();
            
            $query = self::query()
                ->selectRaw('SUM(mw_statistics_page.count) as s_count')
                ->join('mw_place_an_ad as ad', 'mw_statistics_page.pid', '=', 'ad.id')
                ->join('mw_listing_users as usr', 'usr.user_id', '=', 'ad.user_id')
                ->where('ad.status', 'A')
                ->where('ad.isTrash', '0')
                ->where(function($query) use ($userId) {
                    $query->whereRaw('CASE WHEN usr.parent_user IS NOT NULL 
                        THEN (usr.parent_user = ? OR ad.user_id = ?) 
                        ELSE ad.user_id = ? END', [$userId, $userId, $userId]);
                });
            
            if ($duration === 'today') {
                $today = Carbon::now()
                    ->setTimezone('Asia/Karachi')
                    ->format('Y-m-d');
                    
                $query->where('mw_statistics_page.date', $today);
            } elseif ($duration === '30day') {
                $today = Carbon::now()
                    ->setTimezone('Asia/Karachi')
                    ->format('Y-m-d');
                    
                $fromDate = Carbon::now()
                    ->setTimezone('Asia/Karachi')
                    ->subDays(30)
                    ->format('Y-m-d');
                    
                $query->where('mw_statistics_page.date', '<=', $today)
                      ->where('mw_statistics_page.date', '>=', $fromDate);
            }
            
            if (!empty($pid)) {
                $query->where('mw_statistics_page.pid', $pid);
            }
            
            // $result = 
            return $query->first();
        } catch (\Exception $e) {
            Log::error('Error in pageCount method: ' . $e->getMessage(), [
                'duration' => $duration,
                'pid' => $pid,
                'exception' => $e
            ]);
            
            return (object) ['s_count' => 0];
        }
    }
}
