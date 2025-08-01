<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class MwStatistic
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon $date
 * @property int $count
 * @property string $type
 *
 * @package App\Models
 */
class MwStatistic extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_statistics';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'user_id' => 'int',
		'date' => 'datetime',
		'count' => 'int'
	];

	protected $fillable = [
		'count'
	];

	/**
	 * Get the place an ad that this statistic belongs to
	 */
	public function placeAnAd()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'id', 'id');
	}

    /**
     * Get call count statistics for a specific time period and optional property ID
     *
     * @param string $duration ('30day', 'today', '')
     * @param int|null $pid Property ID to filter by
     * @return object|null
     */
    public static function callCount($duration = '', $pid = null)
    {
        return self::getStatistics($duration, 'C', $pid);
    }

    /**
     * Get mail count statistics for a specific time period and optional property ID
     *
     * @param string $duration ('30day', 'today', '')
     * @param int|null $pid Property ID to filter by
     * @return object|null
     */
    public static function mailCount($duration = null, $pid = null)
    {
        return self::getStatistics($duration, 'E', $pid);
    }

    /**
     * Get statistics for a specific type, period and property ID
     * 
     * @param string $duration
     * @param string $type
     * @param int|null $pid
     * @return object|null
     */
    private static function getStatistics($duration, $type, $pid = null)
    {
        try {
            $userId = Auth::id();
            
            $query = self::where('type', $type);
        
            if (!empty($pid)) {
                $query->where('id', $pid);
            } else {
                $query->whereHas('placeAnAd', function($subquery) use ($userId) {
                    $subquery->where('user_id', $userId)
                            ->where('status', 'A')
                            ->where('isTrash', '0');
                });
            }
            
            $currentDate = Carbon::now()
                ->setTimezone('Asia/Karachi')
                ->format('Y-m-d');
            
            switch($duration) {
                case 'today':
                    $query->where('date', $currentDate);
                    break;
                
                case '30day':
                    $fromDate = Carbon::now()
                        ->setTimezone('Asia/Karachi')
                        ->subDays(30)
                        ->format('Y-m-d');
                    
                    $query->where('date', '<=', $currentDate)
                          ->where('date', '>=', $fromDate);
                    break;
                
                case 'current_month':
                    $startOfMonth = Carbon::now()
                        ->setTimezone('Asia/Karachi')
                        ->startOfMonth()
                        ->format('Y-m-d');
                    
                    $endOfMonth = Carbon::now()
                        ->setTimezone('Asia/Karachi')
                        ->endOfMonth()
                        ->format('Y-m-d');
                    
                    $query->where('date', '>=', $startOfMonth)
                          ->where('date', '<=', $endOfMonth);
                    break;
            }
            
            return $query->sum('count');
        } catch (\Exception $e) {
            Log::error('Error in getStatistics method: ' . $e->getMessage(), [
                'type' => $type,
                'duration' => $duration,
                'pid' => $pid,
                'user_id' => Auth::id()
            ]);
            return null;
        }
    }
}
