<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrayerSchedule;

class PrayerScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan updateOrCreate agar jika seeder dijalankan berulang kali, 
        // data tidak menjadi ganda (duplicate), melainkan di-update jika sudah ada.

        PrayerSchedule::updateOrCreate(
            ['prayer_name' => 'Tahajjud'],
            [
                'start_time' => '02:00:00',
                'end_time' => '04:30:00'
            ]
        );

        PrayerSchedule::updateOrCreate(
            ['prayer_name' => 'Subuh'],
            [
                'start_time' => '04:30:00',
                'end_time' => '06:00:00'
            ]
        );
    }
}