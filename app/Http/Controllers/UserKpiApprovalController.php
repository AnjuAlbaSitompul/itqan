<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\UserKpiApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserKpiApprovalController extends Controller
{
    public function index()
    {
        // Get periods with status 'open' first, then ascending by name
        $periods = KpiPeriod::whereIn('status', ['open', 'closed'])
            ->orderByRaw("(status = 'open') DESC")
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        // Logic to display KPI approvals
        return view('approval.kpi.index', compact('periods'));
    }

    public function approval($id, $action, Request $request)
    {
        if ($action === 'reject') {
            $request->validate([
                'notes' => 'required|string|max:255',
            ]);
        }

        $approval = UserKpiApproval::with('userKpis')
            ->findOrFail($id);

        if ($approval->approver_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($approval->status !== 'pending' && $approval->status !== 'review') {
            return response()->json([
                'message' => 'Approval sudah diproses sebelumnya'
            ], 422);
        }

        DB::transaction(function () use ($approval, $action, $request) {

            if ($action === 'approve') {
                $approval->update([
                    'status' => 'approved',
                ]);

            }

            if ($action === 'reject') {
                $approval->update([
                    'status' => 'rejected',
                    'notes' => $request->notes,
                ]);

                $approval->update([
                    'status' => 'rejected',
                ]);
            }
        });

        return response()->json([
            'message' => 'Approval berhasil diproses'
        ]);
    }

    public function list(Request $request)
    {
        $list = UserKpiApproval::where('approver_id', Auth::id())
            ->when($request->filled('period_id'), function ($query) use ($request) {
                $query->where('kpi_period_id', $request->period_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->whereHas('kpiPeriod', function ($query) {
                $query->where('status', 'open');
            })
            ->with([
                'kpiDetails.masterKpi.formulas',
                'userKpis.user',
                'creator',
            ])
            ->get();

        $data = UserKpiApproval::where('approver_id', Auth::id())
            ->when($request->filled('period_id'), function ($query) use ($request) {
                $query->where('kpi_period_id', $request->period_id);
            })
            ->with([
                'kpiDetails.masterKpi.formulas',
                'userKpis.user',
                'creator',
            ])->get();


        return response()->json([
            'list' => $list,
            'approve' => $data->where('status', 'approved')->count(),
            'reject' => $data->where('status', 'rejected')->count(),
            'pending' => $data->where('status', 'pending')->count(),
            'review' => $data->where('status', 'review')->count(),
        ]);

    }
}
