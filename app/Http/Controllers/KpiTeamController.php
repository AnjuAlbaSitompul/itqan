<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\UserKpi;
use App\Models\UserKpiApproval;
use App\Models\UserKpiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KpiTeamController extends Controller
{
    public function index()
    {

        $subordinate = Auth::user()->subordinates()->get();
        $superior = Auth::user()->superior()->get();

        $kpiperiod = KpiPeriod::where('status', 'open')->first();

        $isTeamSet = false;
        $isExists = false;

        // Pastikan kpiperiod ada (tidak null) sebelum cek ke database lain
        if ($kpiperiod) {
            $isTeamSet = UserKpiApproval::where('kpi_period_id', $kpiperiod->id)->exists();
            $isExists = $isTeamSet; // Sesuai dengan kode Anda sebelumnya
        }

        return view('team.kpi.index', compact('kpiperiod', 'isTeamSet', 'isExists', 'subordinate', 'superior'));
    }

    public function assignKpi(Request $request)
    {
        // 1. Validasi input dari form (berupa array)
        $request->validate([
            'team_members' => 'required|array|min:1',
            'team_members.*' => 'exists:users,id',
            'kpi_masters' => 'required|array|min:1',
            'kpi_masters.*' => 'exists:kpi_masters,id',
            'approver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        // 2. Dapatkan periode KPI yang sedang aktif
        $activePeriod = KpiPeriod::where('status', 'open')->first();

        if (!$activePeriod) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode KPI yang sedang aktif (Open).'
            ], 400);
        }

        // 3. Cek apakah SPV ini sudah pernah submit untuk periode ini
        // (Mencegah submit ganda)
        $exists = UserKpiApproval::where('created_by', Auth::id())
            ->where('kpi_period_id', $activePeriod->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan penugasan KPI untuk tim Anda di periode ini.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // 4. Buat Header Approval
            $approval = UserKpiApproval::create([
                'level' => 1,
                'kpi_period_id' => $activePeriod->id,
                'approver_id' => $request->approver_id,
                'created_by' => Auth::id(),
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // 5. Masukkan anggota tim yang ditugaskan (Tabel user_kpis)
            $userKpisData = [];
            foreach ($request->team_members as $userId) {
                $userKpisData[] = [
                    'kpi_approval_id' => $approval->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            UserKpi::insert($userKpisData);

            // 6. Masukkan detail KPI Master yang digunakan (Tabel user_kpi_details)
            $kpiDetailsData = [];
            foreach ($request->kpi_masters as $masterId) {
                $kpiDetailsData[] = [
                    'kpi_approval_id' => $approval->id,
                    'kpi_master_id' => $masterId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            UserKpiDetail::insert($kpiDetailsData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penugasan KPI berhasil disimpan dan dikirim ke atasan untuk Approval.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
