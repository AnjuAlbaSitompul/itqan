<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerSchedule extends Model
{
    protected $fillable = ['prayer_name', 'start_time', 'end_time'];
}
