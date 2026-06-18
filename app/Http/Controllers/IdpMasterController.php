<?php

namespace App\Http\Controllers;

use App\Models\PrayerSchedule;
use Illuminate\Http\Request;

class IdpMasterController extends Controller
{
    public function shalatSchedule()
    {
        return view('performance.sholatschedule.index');
    }

    public function shalatScheduleList()
    {
        $schedule = PrayerSchedule::all();

        return response()->json($schedule);
    }

    public function store()
    {
        $data = request()->validate([
            'prayer_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        PrayerSchedule::create($data);

        return response()->json(['message' => 'Jadwal shalat berhasil ditambahkan.'], 201);
    }

    public function update($id)
    {
        $data = request()->validate([
            'prayer_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = PrayerSchedule::findOrFail($id);
        $schedule->update($data);

        return response()->json(['message' => 'Jadwal shalat berhasil diperbarui.']);
    }
}
