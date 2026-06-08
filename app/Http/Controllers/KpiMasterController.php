<?php

namespace App\Http\Controllers;

use App\Models\KpiPeriod;
use App\Models\Role;
use App\Models\User;
use App\Notifications\KPIOpenNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class KpiMasterController extends Controller
{

    public function storeKpiPeriod(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'status' => 'required|in:draft,open,closed',
        ]);

        DB::beginTransaction();

        try {

            if ($validated['status'] === 'open') {

                $currentOpen = KpiPeriod::where('status', 'open')
                    ->lockForUpdate()
                    ->first();

                if ($currentOpen) {

                    if (
                        Carbon::parse($currentOpen->period_end)
                            ->lt(Carbon::today())
                    ) {

                        $currentOpen->update([
                            'status' => 'closed'
                        ]);

                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'Masih terdapat KPI Period yang sedang aktif.'
                        ], 422);

                    }
                }
            }

            $period = KpiPeriod::create([
                'name' => $validated['name'],
                'registration_start' => $validated['registration_start'],
                'registration_end' => $validated['registration_end'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'status' => $validated['status'],
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            $roles = Role::whereIn('name', ['admin', 'manager'])->pluck('id');

            $users = User::whereIn('role_id', $roles)->get();
            Notification::send(
                $users,
                new KPIOpenNotification(
                    'Approval KPI',
                    'Terdapat KPI yang menunggu persetujuan Anda.'
                )
            );


            return response()->json([
                'success' => true,
                'message' => 'KPI Period created successfully',
                'data' => $period
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function kpiPeriod()
    {
        $periods = KpiPeriod::with('creator')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }
}
