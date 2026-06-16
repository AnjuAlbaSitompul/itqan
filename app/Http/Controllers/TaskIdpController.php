<?php

namespace App\Http\Controllers;

use App\Models\BookReadingLog;
use App\Models\PrayerSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskIdpController extends Controller
{
    /**
     * Render halaman Task IDP.
     */
    public function index()
    {
        $user = auth()->user();

        $prayerReports = $user->prayerReports()->orderBy('reported_at', 'desc')->get();
        $activeBook = $user->bookProposals()->whereIn('status', ['pending', 'approved'])->latest()->first();

        // 1. Ambil jadwal dari database
        $prayerSchedules = PrayerSchedule::all()->keyBy('prayer_name');

        // 2. Ambil data shalat apa saja yang SUDAH disubmit hari ini
        $todaySubmissions = $user->prayerReports()
            ->whereDate('reported_at', Carbon::today())
            ->pluck('prayer_type')
            ->toArray();

        return view('task.idp.index', compact('prayerReports', 'activeBook', 'prayerSchedules', 'todaySubmissions'));
    }

    public function getServerTime()
    {
        return response()->json([
            'server_time' => now()->toIso8601String(),
            'formatted' => now()->format('H:i:s')
        ]);
    }

    /**
     * Simpan data Jogging GPS
     */
    public function storeJogging(Request $request)
    {
        $request->validate([
            'distance_km' => 'required|numeric|min:0',
            'duration_seconds' => 'required|integer|min:0',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);

        $track = auth()->user()->joggingTracks()->create([
            'distance_km' => $request->distance_km,
            'duration_seconds' => $request->duration_seconds,
            'start_time' => $request->start_time ? Carbon::parse($request->start_time) : now()->subSeconds($request->duration_seconds),
            'end_time' => $request->end_time ? Carbon::parse($request->end_time) : now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aktivitas jogging berhasil disimpan!',
            'data' => $track
        ]);
    }

    /**
     * Simpan Laporan Shalat (Tahajjud / Subuh) dengan Validasi Waktu
     */
    public function storePrayer(Request $request)
    {
        $request->validate([
            'prayer_type' => 'required|in:Tahajjud,Subuh',
            'device_time' => 'required|date',
        ]);

        $user = auth()->user();
        $serverTime = now();
        $prayerType = $request->prayer_type;

        // Validasi 1: Apakah sudah submit hari ini?
        $alreadySubmitted = $user->prayerReports()
            ->where('prayer_type', $prayerType)
            ->whereDate('reported_at', Carbon::today())
            ->exists();

        if ($alreadySubmitted) {
            return response()->json(['success' => false, 'message' => "Anda sudah melaporkan shalat {$prayerType} untuk hari ini."], 422);
        }

        // Validasi 2: Cek Jadwal dari Database
        $schedule = PrayerSchedule::where('prayer_name', $prayerType)->first();
        if ($schedule) {
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);

            // Cek apakah waktu server saat ini berada di dalam rentang waktu jadwal
            if (!$serverTime->between($startTime, $endTime)) {
                return response()->json(['success' => false, 'message' => "Saat ini bukan waktu untuk melaporkan shalat {$prayerType}. Waktu: {$schedule->start_time} - {$schedule->end_time}"], 422);
            }
        } else {
            return response()->json(['success' => false, 'message' => "Jadwal untuk {$prayerType} belum diatur di database."], 422);
        }

        // Validasi 3: Cek sinkronisasi waktu device & server (Toleransi 60 detik)
        $deviceTime = Carbon::parse($request->device_time);
        if ($serverTime->diffInSeconds($deviceTime) > 60) {
            return response()->json(['success' => false, 'message' => 'Gagal submit. Waktu perangkat Anda tidak sinkron dengan server.'], 422);
        }

        // Simpan menggunakan waktu SERVER (now)
        $prayer = $user->prayerReports()->create([
            'prayer_type' => $prayerType,
            'reported_at' => $serverTime,
            'device_time' => $deviceTime,
        ]);

        return response()->json(['success' => true, 'message' => "Laporan {$prayerType} berhasil disubmit.", 'data' => $prayer]);
    }

    /**
     * Simpan Pengajuan Proposal Buku ke Atasan
     */
    public function storeBookProposal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        // Mencegah user submit proposal baru jika masih ada yang aktif
        $hasActive = $user->bookProposals()->whereIn('status', ['pending', 'approved'])->exists();
        if ($hasActive) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih memiliki buku yang berstatus aktif atau menunggu persetujuan.'
            ], 422);
        }

        // Gunakan relasi superior() yang ada di model User untuk mencari atasan
        $superior = $user->superior;

        $proposal = $user->bookProposals()->create([
            'title' => $request->title,
            'author' => $request->author,
            'status' => 'pending',
            'superior_id' => $superior ? $superior->id : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proposal buku berhasil diajukan ke atasan.',
            'data' => $proposal
        ]);
    }

    /**
     * Simpan Log Harian Baca Buku (Summary per halaman)
     */
    public function storeBookLog(Request $request)
    {
        $request->validate([
            'book_proposal_id' => 'required|exists:book_proposals,id',
            'page_from' => 'required|integer|min:1',
            'page_to' => 'required|integer|gte:page_from',
            'summary' => 'required|string|min:10',
        ]);

        // Pastikan proposal adalah milik user dan statusnya approved
        $proposal = auth()->user()->bookProposals()
            ->where('id', $request->book_proposal_id)
            ->where('status', 'approved')
            ->firstOrFail();

        // Karena relasi logs belum ada di model User, kita panggil dari model Model\BookReadingLog
        $log = BookReadingLog::create([
            'book_proposal_id' => $proposal->id,
            'page_from' => $request->page_from,
            'page_to' => $request->page_to,
            'summary' => $request->summary,
            'log_date' => now()->toDateString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log harian berhasil disimpan!',
            'data' => $log
        ]);
    }
}