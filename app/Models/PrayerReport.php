<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerReport extends Model
{
    protected $fillable = [
        'user_id',
        'prayer_type',
        'reported_at',
        'device_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
