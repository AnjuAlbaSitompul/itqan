<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoggingTrack extends Model
{
    protected $fillable = [
        'user_id',
        'distance_km',
        'duration_seconds',
        'start_time',
        'end_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
