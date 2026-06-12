<?php

namespace App\Http\Controllers;

use App\Models\KpiMaster;
use App\Models\KpiPeriod;
use App\Models\Role;
use App\Models\User;
use App\Models\UserKpi;
use App\Notifications\KPIOpenNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class KpiMasterController extends Controller
{

    public function storeMyKpi(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'definition_of_done' => 'nullable|string',
            'guard_rail' => 'nullable|string',
            'satuan' => 'required|in:percentage,number,currency',
            'bobot' => 'required|numeric|min:0', // <-- Tambahkan validasi bobot
            'target' => 'required|numeric',
            'formulas' => 'required|array',
            'formulas.*.from' => 'required|numeric',
            'formulas.*.to' => 'required|numeric|gt:formulas.*.from',
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan Master KPI
            $kpimaster = KpiMaster::create([
                'title' => $validated['title'],
                'definition_of_done' => $validated['definition_of_done'] ?? null,
                'guard_rail' => $validated['guard_rail'] ?? null,
                'satuan' => $validated['satuan'],
                'bobot' => $validated['bobot'], // <-- Simpan bobot ke DB
                'target' => $validated['target'],
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            // 3. Simpan Relasi Formula
            foreach ($validated['formulas'] as $formula) {
                $kpimaster->formulas()->create([
                    'from' => $formula['from'],
                    'to' => $formula['to'],
                ]);
            }

            DB::commit();

            // 4. Load relasi formulas agar JSON memiliki data lengkap
            $kpimaster->load('formulas');

            // 5. Kembalikan FULL OBJECT agar JS bisa menggambar Card dengan lengkap
            return response()->json([
                'success' => true,
                'message' => 'KPI Master berhasil dibuat.',
                'data' => $kpimaster // <-- Kirim seluruh objek
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function myKpi()
    {
        $kpimaster = Auth::user()->createdKpis()->with('formulas')->get();
        return response()->json([
            'success' => true,
            'data' => $kpimaster,
        ]);


    }

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

            if ($validated['status'] === 'open') {
                $roles = Role::whereIn('name', ['admin', 'spv', 'manager', 'manager_HO', 'direksi'])->pluck('id');

                $users = User::whereIn('role_id', $roles)->get();
                Notification::send(
                    $users,
                    new KPIOpenNotification(
                        'KPI Period ' . $period->name . ' telah dibuka.',
                        'Silahkan akses aplikasi untuk melihat detail KPI Period.'
                    )
                );
            }


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
            ->orderByRaw("
            CASE
                WHEN status = 'open' THEN 1
                WHEN status = 'draft' THEN 2
                WHEN status = 'closed' THEN 3
                ELSE 4
            END
        ")
            ->orderByDesc('period_start')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $periods,
        ]);
    }
}
