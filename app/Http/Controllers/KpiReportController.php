<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\KpiPeriod;
use App\Models\ManPowerRequest;
use App\Models\Mutasi;
use App\Models\OrganizationalUnit;
use App\Models\Outlet;
use App\Models\Peringatan;
use App\Models\Role;
use App\Models\User;
use App\Models\UserKpiRealization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiReportController extends Controller
{
    public function approvalRequest()
    {
        // Data untuk Filter Offcanvas
        $organizationalUnits = \App\Models\OrganizationalUnit::where('is_active', true)->get(['id', 'name']);

        return view('approval.report.index', compact('organizationalUnits'));
    }

    public function processApproval(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:mutasi,peringatan,manpower',
            'action' => 'required|in:approve,reject',
        ]);

        $hrUserId = auth()->id();
        $status = $request->action === 'approve' ? 'approved' : 'rejected';

        try {
            DB::beginTransaction();

            switch ($request->type) {
                case 'mutasi':
                    $mutasi = Mutasi::findOrFail($request->id);
                    $mutasi->status = $status;
                    $mutasi->approved_by = $hrUserId;

                    if ($status === 'approved') {
                        $request->validate(['effective_date' => 'required|date']);
                        $mutasi->effective_date = $request->effective_date;
                    }
                    $mutasi->save();
                    break;

                case 'peringatan':
                    $peringatan = Peringatan::findOrFail($request->id);
                    $peringatan->status = $status;
                    $peringatan->approved_by = $hrUserId;

                    if ($status === 'approved') {
                        $request->validate(['due_date' => 'required|date']);
                        $peringatan->due_date = $request->due_date;
                    }
                    $peringatan->save();
                    break;

                case 'manpower':
                    $manpower = ManPowerRequest::findOrFail($request->id);
                    $manpower->status = $status;
                    $manpower->approved_by = $hrUserId;
                    $manpower->save();

                    // Logika ekstra: Jika Man Power disetujui & ada kandidat mutasi yang dipilih HR
                    if ($status === 'approved' && $request->filled('assigned_user_id')) {
                        $request->validate(['effective_date_mp' => 'required|date']);

                        $karyawan = User::with('profile')->findOrFail($request->assigned_user_id);

                        // Buat pengajuan Mutasi baru yang statusnya langsung "Approved"
                        Mutasi::create([
                            'user_id' => $karyawan->id,
                            'from_id' => $karyawan->profile->organizational_unit_id,
                            'to_id' => $manpower->organizational_unit_id,
                            'jabatan_id' => $karyawan->profile->jabatan_id,
                            'is_head' => $karyawan->profile->is_head,
                            'reason' => "Mutasi silang pemenuhan Man Power Request #{$manpower->id}. " . ($request->approval_notes ?? ''),
                            'effective_date' => $request->effective_date_mp,
                            'status' => 'approved',
                            'superior_approval_status' => 'approved',
                            'approved_by' => $hrUserId,
                            'superior_approved_by' => $hrUserId,
                            'requested_by' => $hrUserId,
                        ]);

                        // Opsional: Update profil karyawan langsung ke unit yang baru
                        // $karyawan->profile->update(['organizational_unit_id' => $manpower->organizational_unit_id]);
                    }
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Request {$request->type} berhasil di-{$status}.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function approvalData(Request $request)
    {
        $status = $request->input('status', 'pending');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Ambil Data Mutasi
        $mutasi = Mutasi::query()
            ->with([
                'user.profile.jabatan',
                'user.profile.organizationalUnit',
                'fromUnit',
                'toUnit',
                'jabatan',
                'requestedBy',
                'superiorApprovedBy'
            ])
            ->where('superior_approval_status', 'approved') // Hanya yang sudah di-acc atasan langsung
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->get();

        // 2. Ambil Data Peringatan
        $peringatan = Peringatan::query()
            ->with([
                'user.profile.jabatan',
                'user.profile.organizationalUnit',
                'requestedBy',
                'superiorApprovedBy'
            ])
            ->where('superior_approval_status', 'approved')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->get();

        // 3. Ambil Data Man Power & Proses Kandidat
        $manPowerQuery = ManPowerRequest::query()
            ->with([
                'requestedBy',
                'organizationalUnit.employees.user.profile',
                'superiorApprovedBy'
            ])
            ->where('superior_approval_status', 'approved')
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->latest()
            ->get();

        $manPower = $manPowerQuery->map(function ($mp) {
            $unit = $mp->organizationalUnit;

            // Hitung jumlah karyawan saat ini di unit tersebut
            $currentHeadcount = $unit ? $unit->employees->count() : 0;

            $candidates = [];

            // Cari kandidat dari unit ber-tipe sama untuk dipindahkan
            if ($unit && $unit->type) {
                $candidates = User::whereHas('profile.organizationalUnit', function ($q) use ($unit) {
                    $q->where('type', $unit->type)
                        ->where('id', '!=', $unit->id);
                })
                    ->where('is_active', true)
                    ->with(['profile.organizationalUnit', 'profile.jabatan'])
                    ->get()
                    ->map(function ($u) {
                        return [
                            'id' => $u->id,
                            'name' => $u->name,
                            'unit_name' => $u->profile->organizationalUnit->name ?? '-',
                            'jabatan' => $u->profile->jabatan->name ?? '-',
                        ];
                    });
            }

            return [
                'id' => $mp->id,
                'requested_by' => $mp->requestedBy->name ?? '-',
                'unit_id' => $unit->id ?? null,
                'unit_name' => $unit->name ?? '-',
                'jumlah_manpower' => $mp->jumlah_manpower,
                'current_headcount' => $currentHeadcount,
                'reason' => $mp->reason,
                'status' => $mp->status,
                'created_at' => $mp->created_at->format('Y-m-d H:i'),
                'candidates' => $candidates,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'mutasi' => $mutasi,
                'peringatan' => $peringatan,
                'man_power' => $manPower
            ]
        ]);
    }
    public function kpiReport()
    {
        $kpiPeriods = KpiPeriod::orderBy('period_start', 'desc')->get();

        // Ambil unit organisasi teratas (parent_id = null) untuk dropdown
        $orgUnits = OrganizationalUnit::whereNull('parent_id')
            ->with('childrenRecursive')
            ->get();
        $jabatan = Jabatan::where('is_active', true)->get()->pluck('name', 'id');
        $role = Role::get()->pluck('name', 'id');

        return view('performance.kpireport.index', compact('kpiPeriods', 'orgUnits', 'jabatan', 'role'));
    }

    /**
     * Mengambil Data Statistik & Top Performer via AJAX
     */
    public function getData(Request $request)
    {
        // 1. Build Base Query menggunakan Join untuk performa maksimal
        $query = UserKpiRealization::query()
            ->join('user_kpis', 'user_kpis.id', '=', 'user_kpi_realizations.user_kpi_id')
            ->join('user_kpi_approvals', 'user_kpi_approvals.id', '=', 'user_kpis.kpi_approval_id')
            ->join('users', 'users.id', '=', 'user_kpis.user_id')
            ->leftJoin('employee_profiles', 'users.id', '=', 'employee_profiles.user_id')
            ->leftJoin('organizational_units', 'employee_profiles.organizational_unit_id', '=', 'organizational_units.id')
            ->leftJoin('jabatans', 'employee_profiles.jabatan_id', '=', 'jabatans.id')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id'); // <-- JOIN KE TABEL PROFILES

        // 2. Terapkan Filter: Periode KPI
        if ($request->filled('kpi_periods')) {
            $query->whereIn('user_kpi_approvals.kpi_period_id', $request->kpi_periods);
        }

        // 3. Terapkan Filter: Rentang Tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('user_kpi_realizations.created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }

        // 4. Terapkan Filter: Scope Unit Organisasi (Rekursif)
        if ($request->filled('org_unit_id') && $request->org_unit_id !== 'all') {
            $unitIds = $this->getUnitAndChildrenIds($request->org_unit_id);
            $query->whereIn('employee_profiles.organizational_unit_id', $unitIds);
        }

        // 5. Hitung Agregasi Global (Statistik Atas)
        $baseStatQuery = clone $query;
        $globalStats = $baseStatQuery->selectRaw('
            AVG(user_kpi_realizations.nilai) as global_avg,
            COUNT(DISTINCT users.id) as total_users
        ')->first();

        // 6. Tarik Data Top Performer (Dikelompokkan per User)
        $topUsers = $query->selectRaw('
                users.id as user_id,
                users.name as user_name,
                users.username as username,
                profiles.nip as nip,
                profiles.tanggal_masuk as tanggal_masuk,
                organizational_units.name as unit_name,
                jabatans.name as jabatan_name,
                roles.name as role_name,
                COUNT(user_kpi_realizations.id) as total_kpi,
                AVG(user_kpi_realizations.nilai) as avg_nilai
            ')
            ->groupBy(
                'users.id',
                'users.name',
                'users.username',
                'profiles.nip',
                'profiles.tanggal_masuk',
                'organizational_units.name',
                'jabatans.name',
                'roles.name'
            )
            ->orderBy('avg_nilai', 'desc')
            ->take(100)
            ->get();

        // Cari Nilai Tertinggi dari List Top User
        $highestScore = $topUsers->max('avg_nilai') ?? 0;

        // 7. Kembalikan Response JSON
        return response()->json([
            'stats' => [
                'avg_score' => number_format((float) $globalStats->global_avg, 2, '.', ''),
                'total_users' => $globalStats->total_users ?? 0,
                'highest_score' => number_format((float) $highestScore, 2, '.', '')
            ],
            'users' => $topUsers
        ]);
    }

    /**
     * Fungsi Bantuan: Mengambil ID Unit & Seluruh Sub-Unit di bawahnya
     */
    private function getUnitAndChildrenIds($unitId)
    {
        $unit = OrganizationalUnit::with('childrenRecursive')->find($unitId);
        if (!$unit)
            return [];

        $ids = [$unit->id];

        $extractIds = function ($children) use (&$extractIds, &$ids) {
            foreach ($children as $child) {
                $ids[] = $child->id;
                if ($child->childrenRecursive->isNotEmpty()) {
                    $extractIds($child->childrenRecursive);
                }
            }
        };

        $extractIds($unit->childrenRecursive);

        return $ids;
    }
}