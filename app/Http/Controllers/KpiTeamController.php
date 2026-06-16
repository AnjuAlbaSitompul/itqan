<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\User;
use App\Models\UserKpi;
use App\Models\UserKpiApproval;
use App\Models\UserKpiDetail;
use App\Notifications\KpiApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KpiTeamController extends Controller
{

    public function report(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:kpi_periods,id',
            'member_id' => 'nullable|exists:users,id',
        ]);

        $periodId = $request->period_id;
        $memberId = $request->member_id;

        $userKpis = UserKpi::with([
            'user:id,name',
            'kpiApproval:id,kpi_period_id,status,approved_at',
            'kpiApproval.kpiPeriod:id,name',
            'kpiApproval.kpiDetails:id,kpi_approval_id,kpi_master_id',
            'kpiApproval.kpiDetails.masterKpi',
            'kpiApproval.kpiDetails.masterKpi.formulas',
            'realizations' // <-- Panggil relasi realisasi di sini
        ])
            ->whereHas('kpiApproval', function ($query) use ($periodId) {
                $query->where('kpi_period_id', $periodId);
            })
            ->when($memberId, function ($query) use ($memberId) {
                $query->where('user_id', $memberId);
            })
            ->get();

        $data = $userKpis->map(function ($userKpi) {
            return [
                'user_kpi_id' => $userKpi->id,
                'member' => [
                    'id' => $userKpi->user?->id,
                    'name' => $userKpi->user?->name,
                ],
                'approval' => [
                    'id' => $userKpi->kpiApproval?->id,
                    'status' => $userKpi->kpiApproval?->status,
                    'approved_at' => $userKpi->kpiApproval?->approved_at,
                ],
                'period' => [
                    'id' => $userKpi->kpiApproval?->kpiPeriod?->id,
                    'name' => $userKpi->kpiApproval?->kpiPeriod?->name,
                ],
                'kpis' => $userKpi->kpiApproval?->kpiDetails->map(function ($detail) use ($userKpi) {

                    // Cari data realisasi yang cocok untuk detail KPI ini
                    $realization = $userKpi->realizations
                        ->where('user_kpi_detail_id', $detail->id)
                        ->first();

                    return [
                        'detail_id' => $detail->id,
                        'master_kpi_id' => $detail->masterKpi?->id,
                        'title' => $detail->masterKpi?->title,
                        'definition_of_done' => $detail->masterKpi?->definition_of_done,
                        'guard_rail' => $detail->masterKpi?->guard_rail,
                        'target' => $detail->masterKpi?->target,
                        'satuan' => $detail->masterKpi?->satuan,
                        'bobot' => $detail->masterKpi?->bobot,
                        'formulas' => $detail->masterKpi?->formulas->map(function ($formula) {
                            return [
                                'id' => $formula->id,
                                'from' => $formula->from ?? null,
                                'to' => $formula->to ?? null, // <-- Typo 't00' diperbaiki di sini
                            ];
                        })->values(),

                        // Masukkan data realisasi ke dalam JSON respons
                        'realization' => $realization ? [
                            'id' => $realization->id,
                            'value' => $realization->realization,
                            'achievement_percent' => $realization->achievement_percent,
                            'nilai' => $realization->nilai,
                            'notes' => $realization->notes,
                        ] : null,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Report KPI berhasil diambil',
            'data' => $data,
        ]);
    }
    public function approvalList(Request $request)
    {
        $approvals = Auth::user()->createdKpiApprovals()
            ->with(['approver', 'kpiPeriod', 'userKpis.user', 'kpiDetails.masterKpi.formulas'])
            ->whereHas('kpiPeriod', function ($query) {
                $query->where('status', 'open');
            })
            ->when($request->filled('period_id'), function ($query) use ($request) {
                $query->where('kpi_period_id', $request->period_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $approvals
        ]);
    }

    public function index()
    {
        $subordinates = Auth::user()->subordinates()->get();
        $allSubordinate = Auth::user()->getAllSubordinates();
        $allSubordinate->load(['profile.jabatan', 'profile.organizationalUnit']);
        $superior = Auth::user()->superior()->get();

        $kpiperiod = KpiPeriod::where('status', 'open')->first();
        $unregisteredMembers = collect();
        $periods = KpiPeriod::whereIn('status', ['open', 'closed'])
            ->orderByRaw("(status = 'open') DESC")
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $isTeamSet = false;
        $isExists = false;
        $assignedKpis = [];

        // Pastikan kpiperiod ada (tidak null) sebelum cek ke database lain
        if ($kpiperiod) {
            $isTeamSet = UserKpiApproval::where('kpi_period_id', $kpiperiod->id)->exists();
            $isExists = $isTeamSet;

            $subordinateIds = Auth::user()->subordinates()->pluck('users.id')->toArray();

            $registeredIds = UserKpi::whereHas('kpiApproval', function ($q) use ($kpiperiod) {
                $q->where('kpi_period_id', $kpiperiod->id);
            })
                ->whereIn('user_id', $subordinateIds)
                ->pluck('user_id')
                ->unique();

            $unregisteredMembers = Auth::user()
                ->subordinates()
                ->whereNotIn('users.id', $registeredIds)
                ->get();
        }

        if ($superior->isEmpty()) {
            $superior = User::whereHas('role', function ($q) { // Perbaikan "user" menjadi "User"
                $q->whereIn('name', ['direksi']);
            })->get();
        }


        if ($isTeamSet) {
            $assignedKpis = Auth::user()->createdKpiApprovals()
                ->where('kpi_period_id', $kpiperiod->id)
                ->with(['userKpis.user', 'kpiDetails.masterKpi'])
                ->get();
        }

        return view('team.kpi.index', compact('kpiperiod', 'isTeamSet', 'isExists', 'subordinates', 'superior', 'assignedKpis', 'unregisteredMembers', 'periods', 'allSubordinate'));
    }

    public function assignKpi(Request $request)
    {
        $request->validate([
            'team_members' => 'required|array|min:1',
            'team_members.*' => 'exists:users,id',
            'kpi_masters' => 'required|array|min:1',
            'kpi_masters.*' => 'exists:kpi_masters,id',
            'approver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $activePeriod = KpiPeriod::where('status', 'open')->first();

        if (!$activePeriod) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode KPI yang sedang aktif (Open).'
            ], 400);
        }

        $teamAssigned = UserKpi::with('kpiApproval')
            ->whereHas('kpiApproval', function ($query) use ($activePeriod) {
                $query->where('kpi_period_id', $activePeriod->id);
            })
            ->whereIn('user_id', $request->team_members)
            ->exists();

        if ($teamAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Salah satu atau beberapa anggota tim sudah memiliki penugasan KPI untuk periode ini.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $approval = UserKpiApproval::create([
                'level' => 1,
                'kpi_period_id' => $activePeriod->id,
                'approver_id' => $request->approver_id,
                'created_by' => Auth::id(),
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

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

            $UserSuperior = User::where('id', $request->approver_id)->first();
            if ($UserSuperior) {
                $UserSuperior->notify(
                    new KpiApprovalNotification(
                        'Approval KPI Menunggu' . ' ' . $activePeriod->name,
                        Auth::user()->name,
                    )
                );
            }

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

    public function updateAssignment(Request $request, $id)
    {
        $request->validate([
            'team_members' => 'required|array|min:1',
            'team_members.*' => 'exists:users,id',
            'kpi_masters' => 'required|array|min:1',
            'kpi_masters.*' => 'exists:kpi_masters,id',
            'approver_id' => 'required|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $approval = UserKpiApproval::where('created_by', Auth::id())
            ->findOrFail($id);

        if ($approval->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Assignment yang sudah disetujui tidak dapat diubah.'
            ], 422);
        }

        $teamAssigned = UserKpi::whereHas('kpiApproval', function ($query) use ($approval) {
            $query->where('kpi_period_id', $approval->kpi_period_id)
                ->where('id', '!=', $approval->id);
        })
            ->whereIn('user_id', $request->team_members)
            ->exists();

        if ($teamAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'Salah satu atau beberapa anggota tim sudah memiliki penugasan KPI pada periode ini.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            $updateData = [
                'approver_id' => $request->approver_id,
                'notes' => $request->notes,
            ];

            // LOGIKA UPDATE STATUS (Menjadi review jika sebelumnya rejected)
            if ($approval->status === 'rejected') {
                $updateData['status'] = 'review';
                $updateData['approved_at'] = null;

                if (Schema::hasColumn($approval->getTable(), 'rejected_at')) {
                    $updateData['rejected_at'] = null;
                }
            }

            $approval->update($updateData);

            // Update Team Members
            UserKpi::where('kpi_approval_id', $approval->id)->delete();

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

            // Update KPI Details
            UserKpiDetail::where('kpi_approval_id', $approval->id)->delete();

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
                'message' => $approval->status === 'rejected'
                    ? 'Assignment berhasil diperbarui dan dikirim ulang untuk direview.'
                    : 'Assignment berhasil diperbarui.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}