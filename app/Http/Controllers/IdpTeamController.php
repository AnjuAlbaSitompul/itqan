<?php

namespace App\Http\Controllers;

use App\Models\BookProposal;
use Illuminate\Http\Request;

class IdpTeamController extends Controller
{
    /**
     * Menampilkan daftar IDP milik bawahan
     */
    public function teamIdp(Request $request)
    {
        $user = auth()->user();

        // Ambil semua bawahan
        $subordinates = $user->getAllSubordinates();

        $selectedUser = null;

        // Jika atasan memilih bawahan dari dropdown filter
        if ($request->filled('user_id')) {
            $selectedUser = $subordinates->firstWhere('id', $request->user_id);

            // Eager load semua relasi IDP agar query lebih optimal
            if ($selectedUser) {
                $selectedUser->load([
                    'joggingTracks' => fn($query) => $query->latest('created_at'),
                    'prayerReports' => fn($query) => $query->latest('reported_at'),
                    'bookProposals.readingLogs' => fn($query) => $query->latest('log_date')
                ]);
            }
        }

        return view('team.idp.index', compact('subordinates', 'selectedUser'));
    }

    /**
     * Memproses Persetujuan / Penolakan IDP via AJAX
     */
    public function approveBookProposal(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'status' => 'required|in:approved,rejected',
            // due_date wajib diisi HANYA jika status = approved
            'due_date' => 'required_if:status,approved|nullable|date|after_or_equal:today',
        ], [
            'due_date.required_if' => 'Tenggat waktu membaca wajib diisi jika proposal disetujui.',
            'due_date.after_or_equal' => 'Tenggat waktu minimal adalah hari ini.'
        ]);

        $proposal = BookProposal::findOrFail($id);

        // Keamanan ekstra: Pastikan bawahan adalah tim dari atasan yang login
        $subordinateIds = auth()->user()->getAllSubordinates()->pluck('id')->toArray();
        if (!in_array($proposal->user_id, $subordinateIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda bukan atasan dari karyawan ini.'
            ], 403);
        }

        // Update Data
        $proposal->status = $request->status;
        $proposal->approved_by = auth()->id(); // Kolom sudah diperbarui

        // Jika disetujui, simpan due_date. Jika ditolak, kosongkan saja.
        if ($request->status === 'approved') {
            $proposal->due_date = $request->due_date;
        }

        $proposal->save();

        return response()->json([
            'success' => true,
            'message' => 'Proposal buku berhasil di' . ($request->status == 'approved' ? 'setujui' : 'tolak') . '.',
            'new_status' => ucfirst($proposal->status),
            'due_date_formatted' => $proposal->due_date ? \Carbon\Carbon::parse($proposal->due_date)->format('d M Y') : null
        ]);
    }

    public function getUserData(Request $request)
    {
        if (!$request->filled('user_id')) {
            return response()->json(['html' => '']);
        }

        $user = auth()->user();
        $selectedUser = $user->getAllSubordinates()->firstWhere('id', $request->user_id);

        if ($selectedUser) {
            $selectedUser->load([
                'joggingTracks' => fn($query) => $query->latest('created_at'),
                'prayerReports' => fn($query) => $query->latest('reported_at'),
                // Ubah bagian ini:
                'bookProposals.readingLogs' => fn($query) => $query->latest('log_date')
            ]);
        }

        // Render file blade partial dan kirim sebagai string HTML
        $html = view('team.idp.partial.data', compact('selectedUser'))->render();

        return response()->json(['html' => $html]);
    }

}
