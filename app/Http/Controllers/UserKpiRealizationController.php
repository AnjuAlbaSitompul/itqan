<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserKpi;
use App\Models\UserKpiRealization;
use App\Models\KpiPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserKpiRealizationController extends Controller
{
    public function index(Request $request)
    {
        $periods = KpiPeriod::whereIn('status', ['open', 'closed'])->orderBy('period_start', 'desc')->get();
        $selectedPeriodId = $request->get('period_id', $periods->first()?->id);

        $userKpi = UserKpi::with([
            'kpiApproval' => function ($q) use ($selectedPeriodId) {
                $q->where('kpi_period_id', $selectedPeriodId);
            },
            'kpiApproval.kpiPeriod',
            'kpiApproval.kpiDetails.masterKpi.formulas',
            'realizations'
        ])
            ->where('user_id', Auth::id())
            ->whereHas('kpiApproval', function ($q) use ($selectedPeriodId) {
                $q->where('kpi_period_id', $selectedPeriodId);
            })
            ->first();

        $isApproved = false;
        $isExpired = false;
        $periodInfo = null;

        if ($userKpi && $userKpi->kpiApproval) {
            $isApproved = $userKpi->kpiApproval->status === 'approved';
            $periodInfo = $userKpi->kpiApproval->kpiPeriod;

            if ($periodInfo) {
                $isExpired = Carbon::now()->greaterThan(Carbon::parse($periodInfo->period_end));
            }
        }

        return view('task.kpi.index', compact('userKpi', 'periods', 'selectedPeriodId', 'isApproved', 'isExpired', 'periodInfo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_kpi_id' => 'required|exists:user_kpis,id',
            'realizations' => 'required|array',
            'realizations.*.user_kpi_detail_id' => 'required|exists:user_kpi_details,id',
            'realizations.*.value' => 'required|numeric|min:0',
            'realizations.*.notes' => 'nullable|string|max:500',
        ]);

        $userKpi = UserKpi::with('kpiApproval.kpiPeriod')->findOrFail($request->user_kpi_id);

        if (!$userKpi->kpiApproval || $userKpi->kpiApproval->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Gagal! Target KPI Anda belum disetujui oleh atasan.'], 403);
        }

        $periodEnd = Carbon::parse($userKpi->kpiApproval->kpiPeriod->period_end);
        if (Carbon::now()->greaterThan($periodEnd)) {
            return response()->json(['success' => false, 'message' => 'Gagal! Batas akhir pengisian untuk periode ini telah ditutup.'], 403);
        }

        foreach ($request->realizations as $item) {
            $detail = $userKpi->kpiApproval->kpiDetails()->find($item['user_kpi_detail_id']);

            $target = floatval($detail?->masterKpi?->target ?? 1);
            $bobot = floatval($detail?->masterKpi?->bobot ?? 0);
            $value = floatval($item['value']);

            // 1. Hitung persentase pencapaian dasar
            $achievementPercent = $target > 0 ? ($value / $target) * 100 : 0;

            // 2. Hitung nilai/skor riil berdasarkan bobot (Aman kalkulasi di backend)
            $nilai = ($achievementPercent / 100) * $bobot;

            UserKpiRealization::updateOrCreate(
                [
                    'user_kpi_id' => $userKpi->id,
                    'user_kpi_detail_id' => $item['user_kpi_detail_id'],
                ],
                [
                    'realization' => $value,
                    'achievement_percent' => $achievementPercent,
                    'nilai' => $nilai, // <-- Kolom nilai diisi otomatis di backend
                    'notes' => $item['notes'],
                    'created_by' => Auth::id(),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Realisasi capaian KPI Anda berhasil disimpan.'
        ]);
    }
}