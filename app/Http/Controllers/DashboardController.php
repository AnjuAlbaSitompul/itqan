<?php

namespace App\Http\Controllers;

use App\Models\BookReadingLog;
use App\Models\JoggingTrack;
use App\Models\KpiPeriod;
use App\Models\ManPowerRequest;
use App\Models\Mutasi;
use App\Models\Peringatan;
use App\Models\PrayerReport;
use App\Models\User;
use App\Models\UserKpiRealization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return view('dashboard.pegawai');
        }

        $role = $user->role?->name ?? 'pegawai';
        // Cek jika view spesifik ada, jika tidak gunakan default index
        $dashboardData = $this->buildDashboardData($user, $role);

        return view('dashboard.index', $dashboardData);
    }

    private function buildDashboardData(User $user, string $role): array
    {
        // Tentukan apakah user punya hak manajerial
        $isManagerGroup = in_array($role, ['manager', 'spv_hc', 'manager_hc', 'spv', 'admin', 'admin_hc'], true);

        // Ambil bawahan (jika pegawai biasa, isinya hanya ID dia sendiri)
        $subordinateIds = $user->getAllUsersInOrgStructure()->pluck('id')->values()->all();
        $subordinateIds = array_values(array_unique(array_merge([$user->id], $subordinateIds)));

        $currentPeriod = KpiPeriod::where('status', 'open')
            ->orderByDesc('period_start')
            ->first() ?? KpiPeriod::orderByDesc('period_start')->first();

        $kpiYearStart = Carbon::now()->subYear()->startOfDay();
        $today = Carbon::today();

        $currentKpiSummary = [
            'period_name' => $currentPeriod?->name ?? 'No active period',
            'avg_nilai' => 0,
            'total_nilai' => 0,
            'total_realization' => 0,
            'total_users' => 0,
        ];

        if ($currentPeriod) {
            $currentKpi = UserKpiRealization::query()
                ->join('user_kpis', 'user_kpis.id', '=', 'user_kpi_realizations.user_kpi_id')
                ->join('user_kpi_approvals', 'user_kpi_approvals.id', '=', 'user_kpis.kpi_approval_id')
                ->where('user_kpi_approvals.kpi_period_id', $currentPeriod->id)
                ->whereIn('user_kpis.user_id', $subordinateIds)
                ->selectRaw('AVG(user_kpi_realizations.nilai) as avg_nilai, SUM(user_kpi_realizations.nilai) as total_nilai, SUM(user_kpi_realizations.realization) as total_realization, COUNT(DISTINCT user_kpis.user_id) as total_users')
                ->first();

            $currentKpiSummary = [
                'period_name' => $currentPeriod->name,
                'avg_nilai' => (float) ($currentKpi?->avg_nilai ?? 0),
                'total_nilai' => (float) ($currentKpi?->total_nilai ?? 0),
                'total_realization' => (float) ($currentKpi?->total_realization ?? 0),
                'total_users' => (int) ($currentKpi?->total_users ?? 0),
            ];
        }

        $yearKpiSummary = UserKpiRealization::query()
            ->join('user_kpis', 'user_kpis.id', '=', 'user_kpi_realizations.user_kpi_id')
            ->join('user_kpi_approvals', 'user_kpi_approvals.id', '=', 'user_kpis.kpi_approval_id')
            ->where('user_kpi_realizations.created_at', '>=', $kpiYearStart)
            ->whereIn('user_kpis.user_id', $subordinateIds)
            ->selectRaw('COALESCE(SUM(user_kpi_realizations.nilai), 0) as total_nilai, COALESCE(SUM(user_kpi_realizations.realization), 0) as total_realization, COUNT(*) as total_records')
            ->first();

        $yearKpiMonthly = UserKpiRealization::query()
            ->join('user_kpis', 'user_kpis.id', '=', 'user_kpi_realizations.user_kpi_id')
            ->join('user_kpi_approvals', 'user_kpi_approvals.id', '=', 'user_kpis.kpi_approval_id')
            ->where('user_kpi_realizations.created_at', '>=', $kpiYearStart)
            ->whereIn('user_kpis.user_id', $subordinateIds)
            ->selectRaw('DATE_FORMAT(user_kpi_realizations.created_at, "%Y-%m") as month_key, SUM(user_kpi_realizations.nilai) as total_nilai')
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get();

        $joggingSummary = JoggingTrack::query()
            ->whereIn('user_id', $subordinateIds)
            ->selectRaw('COALESCE(SUM(distance_km), 0) as total_distance, COALESCE(SUM(duration_seconds), 0) as total_duration, COUNT(*) as total_sessions')
            ->first();

        $bookReadingSummary = BookReadingLog::query()
            ->whereHas('bookProposal', function ($query) use ($subordinateIds) {
                $query->whereIn('user_id', $subordinateIds)
                    ->where('status', 'approved');
            })
            ->selectRaw('COALESCE(COUNT(*), 0) as total_logs, COALESCE(SUM(page_to - page_from + 1), 0) as total_pages')
            ->first();

        $prayerSummary = PrayerReport::query()
            ->whereIn('user_id', $subordinateIds)
            ->selectRaw('COUNT(*) as total_reports, COUNT(DISTINCT DATE(reported_at)) as total_days')
            ->first();

        // UBAH PENCARIAN BERDASARKAN user_id (target) DAN status HR (approved)
        $warningSummary = [
            'peringatan' => Peringatan::query()
                ->whereIn('user_id', $subordinateIds)
                ->where('status', 'approved')
                ->count(),
            'mutasi' => Mutasi::query()
                ->whereIn('user_id', $subordinateIds)
                ->where('status', 'approved')
                ->count(),
            'manpower_pending' => ManPowerRequest::query()
                ->whereIn('requested_by', $subordinateIds)
                ->where('superior_approval_status', 'pending')
                ->count(),
            'mutasi_today' => Mutasi::query()
                ->whereDate('created_at', $today)
                ->whereIn('user_id', $subordinateIds)
                ->count(),
            'users_to_move' => is_array($subordinateIds) ? count($subordinateIds) - 1 : 0,
        ];

        $announcements = $this->getAnnouncementCards($user);
        $latestWarnings = $this->getLatestApprovedWarnings($subordinateIds);

        return [
            'role' => $role,
            'isManagerGroup' => $isManagerGroup,
            'currentPeriod' => $currentPeriod,
            'currentKpiSummary' => $currentKpiSummary,
            'yearKpiSummary' => [
                'total_nilai' => (float) ($yearKpiSummary?->total_nilai ?? 0),
                'total_realization' => (float) ($yearKpiSummary?->total_realization ?? 0),
                'total_records' => (int) ($yearKpiSummary?->total_records ?? 0),
                'monthly' => $yearKpiMonthly,
            ],
            'joggingSummary' => [
                'total_distance' => (float) ($joggingSummary?->total_distance ?? 0),
                'total_duration' => (int) ($joggingSummary?->total_duration ?? 0),
                'total_sessions' => (int) ($joggingSummary?->total_sessions ?? 0),
            ],
            'bookReadingSummary' => [
                'total_logs' => (int) ($bookReadingSummary?->total_logs ?? 0),
                'total_pages' => (int) ($bookReadingSummary?->total_pages ?? 0),
            ],
            'prayerSummary' => [
                'total_reports' => (int) ($prayerSummary?->total_reports ?? 0),
                'total_days' => (int) ($prayerSummary?->total_days ?? 0),
            ],
            'warningSummary' => $warningSummary,
            'latestWarnings' => $latestWarnings,
            'announcements' => $announcements,
        ];
    }

    private function getLatestApprovedWarnings(array $subordinateIds)
    {
        // UBAH: Cari berdasarkan target `user_id` dan `status` (HR approval)
        $peringatan = Peringatan::with(['user.profile.jabatan', 'user.profile.organizationalUnit'])
            ->whereIn('user_id', $subordinateIds)
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get()
            ->map(function (Peringatan $item) {
                return [
                    'type' => 'peringatan',
                    'label' => 'Surat Peringatan',
                    'name' => $item->user?->name ?? '-',
                    'unit' => $item->user?->profile?->organizationalUnit?->name ?? '-',
                    'title' => $item->type,
                    'date' => $item->updated_at?->format('d M Y, H:i'),
                    'timestamp' => $item->updated_at // Ditambahkan untuk sorting gabungan
                ];
            });

        $mutasi = Mutasi::with(['user.profile.jabatan', 'fromUnit', 'toUnit'])
            ->whereIn('user_id', $subordinateIds)
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get()
            ->map(function (Mutasi $item) {
                return [
                    'type' => 'mutasi',
                    'label' => 'Mutasi Disetujui',
                    'name' => $item->user?->name ?? '-',
                    'unit' => ($item->fromUnit?->name ?? '-') . ' → ' . ($item->toUnit?->name ?? '-'),
                    'title' => $item->reason ?? 'Mutasi',
                    'date' => $item->updated_at?->format('d M Y, H:i'),
                    'timestamp' => $item->updated_at // Ditambahkan untuk sorting gabungan
                ];
            });

        // Gabungkan dan urutkan kembali berdasarkan tanggal terupdate
        return collect()
            ->merge($peringatan)
            ->merge($mutasi)
            ->sortByDesc('timestamp')
            ->take(8)
            ->values();
    }

    private function getAnnouncementCards(User $user)
    {
        $notifications = $user->unreadNotifications()
            ->latest()
            ->take(8)
            ->get();

        return $notifications->map(function ($notification) {
            return [
                'title' => $notification->data['title'] ?? 'Pengumuman',
                'message' => $notification->data['message'] ?? '-',
                'url' => $notification->data['url'] ?? '#',
                'created_at' => $notification->created_at?->format('d M Y, H:i'),
            ];
        });
    }
}