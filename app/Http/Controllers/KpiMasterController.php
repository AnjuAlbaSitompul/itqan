<?php

namespace App\Http\Controllers;

use App\Models\KpiMaster;
use App\Models\KpiPeriod;
use App\Models\Role;
use App\Models\User;
use App\Models\UserKpi;
use App\Models\UserKpiApproval;
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
            'bobot' => 'required|numeric|min:0',
            'target' => 'required|numeric',
            'formulas' => 'required|array|min:1',
            'formulas.*.from' => 'required|numeric',
            'formulas.*.to' => 'required|numeric|gt:formulas.*.from',
            'formulas.*.progress' => 'required|numeric|min:0', // <-- Tambahkan validasi progress
        ], [
            // Kustomisasi pesan error (opsional) agar lebih ramah dibaca pengguna
            'formulas.*.to.gt' => 'Nilai "Sampai (To)" harus lebih besar dari nilai "Dari (From)".',
            'formulas.*.progress.required' => 'Kolom "Hasil (%)" wajib diisi pada setiap baris formula.',
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan Master KPI
            $kpimaster = KpiMaster::create([
                'title' => $validated['title'],
                'definition_of_done' => $validated['definition_of_done'] ?? null,
                'guard_rail' => $validated['guard_rail'] ?? null,
                'satuan' => $validated['satuan'],
                'bobot' => $validated['bobot'],
                'target' => $validated['target'],
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            // 3. Simpan Relasi Formula
            foreach ($validated['formulas'] as $formula) {
                $kpimaster->formulas()->create([
                    'from' => $formula['from'],
                    'to' => $formula['to'],
                    'progress' => $formula['progress'], // <-- Masukkan progress ke database
                ]);
            }

            DB::commit();

            // 4. Load relasi formulas agar JSON memiliki data lengkap
            $kpimaster->load('formulas');

            // 5. Kembalikan FULL OBJECT agar JS bisa menggambar Card dengan lengkap
            return response()->json([
                'success' => true,
                'message' => 'KPI Master berhasil dibuat.',
                'data' => $kpimaster
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

    public function updateKpiPeriod(Request $request, $id)
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
            $period = KpiPeriod::findOrFail($id);

            if ($validated['status'] === 'open' && $period->status !== 'open') {
                $currentOpen = KpiPeriod::where('status', 'open')
                    ->lockForUpdate()
                    ->first();

                if ($currentOpen && $currentOpen->id !== $period->id) {
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

            $period->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'KPI Period updated successfully',
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

    public function kpiPeriod(Request $request)
    {
        $query = KpiPeriod::query()->orderBy('period_start', 'desc');

        // Opsional: Implementasi filter tanggal dari front-end
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('period_start', [$request->start, $request->end]);
        }

        $periods = $query->get();

        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }

    public function show($id)
    {
        // 1. Ambil data utama KpiPeriod
        $period = KpiPeriod::findOrFail($id);

        // 2. Hitung jumlah approval berdasarkan status 'pending' untuk periode ini
        $pending = UserKpiApproval::where('kpi_period_id', $period->id)
            ->where('status', 'pending')
            ->count();

        /**
         * 3. Ambil data statistik peran (role) dari user yang terikat dalam periode ini.
         * Menggunakan subquery Join agar menghemat memori RAM server, daripada menarik ribuan koleksi model.
         */
        $roleCounts = DB::table('user_kpi_approvals')
            ->join('user_kpis', 'user_kpi_approvals.id', '=', 'user_kpis.kpi_approval_id')
            ->join('users', 'user_kpis.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('user_kpi_approvals.kpi_period_id', $period->id)
            ->select('roles.name as role_name', DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->pluck('total', 'role_name')
            ->toArray();

        // Ambil nilai dari array pemetaan (jika role tidak ditemukan dalam database, beri nilai default 0)
        // Sesuaikan string 'pegawai', 'spv', 'manager' dengan value kolom 'name' di tabel roles Anda
        $employee = $roleCounts['pegawai'] ?? 0;
        $spv = $roleCounts['spv'] ?? 0;
        $manager = $roleCounts['manager'] ?? 0;

        $stats = [
            'employee' => $employee,
            'supervisor' => $spv,
            'manager' => $manager,
            'pending' => $pending
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $period->id,
                'name' => $period->name,
                'status' => strtolower($period->status),
                'registration_start' => $period->registration_start,
                'registration_end' => $period->registration_end,
                'period_start' => $period->period_start,
                'period_end' => $period->period_end,
                'stats' => $stats
            ]
        ]);
    }
}
