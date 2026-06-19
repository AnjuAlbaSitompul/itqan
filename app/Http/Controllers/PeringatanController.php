<?php

namespace App\Http\Controllers;

use App\Models\Peringatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeringatanController extends Controller
{
    /**
     * Menampilkan halaman daftar peringatan.
     */
    public function index()
    {
        $karyawans = User::with('profile', 'ownProfile', 'profile.organizationalUnit')->get();
        return view('report.peringatan.index', compact('karyawans')); // Ganti path view sesuai struktur folder Anda
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'sp_type' => 'required|in:peringatan_1,peringatan_2,peringatan_3',
            'reason' => 'required|string|max:500',
            'due_date' => 'required|date|after:today',
        ]);

        $usersWithActiveSP = [];

        DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                // Cek apakah user punya SP aktif (Status approved & due_date belum lewat)
                $hasActiveSP = Peringatan::where('user_id', $userId)
                    ->where('status', 'approved')
                    ->where('due_date', '>=', now())
                    ->first();

                if ($hasActiveSP && $hasActiveSP->type === $request->sp_type) {
                    $userName = User::find($userId)->name;
                    $usersWithActiveSP[] = $userName;
                    continue; // Lewati user ini
                }

                // Simpan SP
                $peringatan = new Peringatan();
                $peringatan->user_id = $userId;
                $peringatan->type = $request->sp_type; // Sesuaikan dengan field DB Anda
                $peringatan->reason = $request->reason;
                $peringatan->issued_date = now();
                $peringatan->due_date = $request->due_date;
                $peringatan->status = 'approved'; // Atau 'pending' jika perlu persetujuan
                $peringatan->requested_by = auth()->id();
                $peringatan->save();
            }


            if (count($usersWithActiveSP) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal! Karyawan berikut sudah memiliki SP aktif: ' . implode(', ', $usersWithActiveSP)
                ], 422);
            }
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Surat Peringatan berhasil dibuat.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data peringatan untuk di-render melalui AJAX.
     */
    public function getData(Request $request)
    {
        try {
            $peringatans = Peringatan::with([
                'user.profile.organizationalUnit',
                'user.profile.jabatan',
                'requestedBy'
            ])
                ->latest()
                ->get()
                ->map(function ($item) {
                    // Pemetaan label tipe peringatan
                    $typeLabels = [
                        'peringatan_1' => 'SP 1',
                        'peringatan_2' => 'SP 2',
                        'peringatan_3' => 'SP 3'
                    ];

                    return [
                        'id' => $item->id,
                        'status' => $item->status, // 'pending', 'approved', 'rejected'
                        'type_label' => $typeLabels[$item->type] ?? $item->type,
                        'user_name' => optional($item->user)->name ?? 'Unknown',
                        'unit_name' => optional(optional(optional($item->user)->profile)->organizationalUnit)->name ?? '-',
                        'reason' => $item->reason ?? '-',
                        'issued_date' => $item->issued_date ? Carbon::parse($item->issued_date)->translatedFormat('d F Y') : '-',
                        'due_date' => $item->due_date ? Carbon::parse($item->due_date)->translatedFormat('d F Y') : null,
                        'requested_by' => optional($item->requestedBy)->name ?? 'Sistem',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $peringatans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memproses Persetujuan (Approve/Reject) Peringatan dari HR.
     */
    public function processHrApproval(Request $request)
    {
        $request->validate([
            'peringatan_id' => 'required|exists:peringatans,id',
            'action' => 'required|in:approve,reject',
            // Tenggat waktu (due_date) wajib diisi jika HR menyetujui SP
            'due_date' => 'required_if:action,approve|date|nullable',
            'approval_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $peringatan = Peringatan::findOrFail($request->peringatan_id);
            $statusAkhir = $request->action === 'approve' ? 'approved' : 'rejected';

            $peringatan->status = $statusAkhir;
            $peringatan->approved_by = auth()->id();

            if ($statusAkhir === 'approved') {
                $peringatan->due_date = $request->due_date;
            }

            // Catatan: Jika ada field approval_notes di tabel peringatans, aktifkan baris di bawah
            // $peringatan->approval_notes = $request->approval_notes;

            $peringatan->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Surat Peringatan berhasil di-{$statusAkhir}."
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