<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\KpiPeriod;
use App\Models\OrganizationalUnit;
use App\Models\Role;
use App\Models\UserKpiRealization;
use Illuminate\Http\Request;

class KpiReportController extends Controller
{
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