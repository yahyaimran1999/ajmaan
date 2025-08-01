<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $connection = 'mysql_legacy';
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'quantity',
        'trial_ends_at',
        'ends_at'
    ];

    protected $dates = [
        'trial_ends_at',
        'ends_at',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->on('mysql_legacy');
    }

    public function onTrial()
    {
        if (! $this->trial_ends_at) {
            return false;
        }

        return Carbon::now()->lt($this->trial_ends_at);
    }

    public function cancelled()
    {
        return ! is_null($this->ends_at);
    }

    public function onGracePeriod()
    {
        if (! $this->ends_at) {
            return false;
        }

        return Carbon::now()->lt($this->ends_at);
    }

    public function recurring()
    {
        return ! $this->cancelled() || $this->onGracePeriod();
    }

    public function ended()
    {
        return $this->cancelled() && ! $this->onGracePeriod();
    }
}
