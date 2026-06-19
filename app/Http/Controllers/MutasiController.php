<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    /**
     * Menampilkan halaman daftar mutasi.
     */
    public function index()
    {
        // Jika Anda me-render langsung di Blade (Server-side rendering), 
        // Anda bisa mengambil data di sini dan mengirimnya menggunakan compact().
        // Tapi jika Anda menggunakan format JSON/AJAX, cukup kembalikan view.

        return view('report.mutasi.index'); // Sesuaikan dengan path file blade Anda
    }

    /**
     * Mengambil data mutasi untuk dikembalikan dalam format JSON.
     * Dapat digunakan untuk me-render list HTML via jQuery/JavaScript.
     */

    public function storeDirectApproved(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_jabatan_id' => 'required|exists:jabatans,id',
            'new_org_unit_id' => 'required|exists:organizational_units,id',
            'new_role_id' => 'required',
            'golongan_select' => 'required|string',
            'effective_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // 2. Ambil data user yang sedang dimutasi untuk mendapatkan unit asal (from_id)
            $targetUser = User::findOrFail($request->user_id);

            // 3. Simpan data Mutasi
            $mutasi = Mutasi::create([
                'user_id' => $targetUser->id,
                'from_id' => $targetUser->organizational_unit_id, // Sesuaikan dengan kolom relasi unit di tabel users
                'to_id' => $request->new_org_unit_id,
                'jabatan_id' => $request->new_jabatan_id,
                'golongan' => $request->golongan_select,
                'effective_date' => $request->effective_date,
                'reason' => 'Mutasi/Promosi langsung dari Laporan Performa KPI',
                'status' => 'approved',
                'superior_approval_status' => 'approved',
                'requested_by' => Auth::id(),
                'approved_by' => Auth::id(),
                'superior_approved_by' => Auth::id(),
            ]);

            // 4. (Opsional) Langsung update data User jika effective_date adalah hari ini atau masa lalu
            // Jika Anda menggunakan Job/Cron Scheduler untuk mengeksekusi mutasi, lewati bagian ini
            if (Carbon::parse($request->effective_date)->startOfDay()->lte(Carbon::now()->startOfDay())) {
                $targetUser->update([
                    'organizational_unit_id' => $request->new_org_unit_id,
                    'jabatan_id' => $request->new_jabatan_id,
                    // 'role_id'             => $request->new_role_id, // Buka komen jika relasi role langsung ada di tabel users
                    // 'golongan'            => $request->golongan_select, // Buka komen jika kolom ini ada di users
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data mutasi berhasil disimpan dan disetujui.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses mutasi: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getData(Request $request)
    {
        try {
            $mutasis = Mutasi::with([
                'user.profile.organizationalUnit',
                'user.profile.jabatan',
                'fromUnit',
                'toUnit'
            ])
                ->latest()
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'status' => $item->status, // 'pending', 'approved', 'rejected'
                        'user_name' => optional($item->user)->name ?? 'Unknown',
                        'jabatan' => optional($item->user->profile->jabatan)->name ?? '-',
                        'from_unit_name' => optional($item->fromUnit)->name ?? '-',
                        'to_unit_name' => optional($item->toUnit)->name ?? '-',
                        'reason' => $item->reason ?? 'Tidak ada alasan yang dicantumkan.',
                        'created_at_fmt' => $item->created_at->translatedFormat('d F Y'),
                        'effective_date' => $item->effective_date ? \Carbon\Carbon::parse($item->effective_date)->translatedFormat('d F Y') : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $mutasis
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memproses Persetujuan (Approve/Reject) Mutasi dari sisi HR.
     */
    public function processHrApproval(Request $request)
    {
        $request->validate([
            'mutasi_id' => 'required|exists:mutasis,id',
            'action' => 'required|in:approve,reject',
            'effective_date' => 'required_if:action,approve|date|nullable',
            'approval_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $mutasi = Mutasi::findOrFail($request->mutasi_id);
            $statusAkhir = $request->action === 'approve' ? 'approved' : 'rejected';

            $mutasi->status = $statusAkhir;
            $mutasi->approved_by = auth()->id();

            if ($statusAkhir === 'approved') {
                $mutasi->effective_date = $request->effective_date;
            }

            $mutasi->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Pengajuan mutasi berhasil di-{$statusAkhir}.",
                'data' => $mutasi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses persetujuan.',
            ], 500);
        }
    }
}