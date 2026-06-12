<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use App\Models\Jabatan;
use App\Models\OrganizationalUnit;
use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationalUnitController extends Controller
{
    public function index()
    {
        $organizations = OrganizationalUnit::with([
            'childrenRecursive',
            'employees.user',
            'employees.jabatan'
        ])
            ->whereNull('parent_id')
            ->get();

        $users = User::orderBy('name')->get();
        $jabatans = Jabatan::orderBy('name')->get();
        $outlets = Outlet::orderBy('name')->get();

        return view('organization.structure.index', compact(
            'organizations',
            'users',
            'jabatans',
            'outlets'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tree' => ['required', 'array']
        ]);

        DB::beginTransaction();

        try {
            $savedIds = [];

            foreach ($request->tree as $node) {
                // Memanggil saveNode dengan parameter awal supervisorId = null
                $this->saveNode($node, null, $savedIds, null);
            }

            // Hapus node yang tidak ada lagi di tree
            OrganizationalUnit::whereNotIn('id', $savedIds)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Organization structure saved successfully'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    private function saveNode(
        array $node,
        ?int $parentId,
        array &$savedIds,
        ?int $supervisorId = null // <--- Tambahkan parameter ini
    ): OrganizationalUnit {

        $isNew = !isset($node['id']) || Str::startsWith((string) $node['id'], 'tmp_');

        if ($isNew) {
            $unit = OrganizationalUnit::create([
                'parent_id' => $parentId,
                'name' => $node['name'],
                'type' => $node['type'],
                'outlet_id' => $node['outlet_id'] ?: null,
                'man_power' => $node['man_power'] ?: 0,
                'is_active' => true,
            ]);
        } else {
            $unit = OrganizationalUnit::findOrFail($node['id']);
            $unit->update([
                'parent_id' => $parentId,
                'name' => $node['name'],
                'type' => $node['type'],
                'outlet_id' => $node['outlet_id'] ?: null,
                'man_power' => $node['man_power'] ?: 0,
            ]);
        }

        $savedIds[] = $unit->id;

        // ======================
        // Sync Employees
        // ======================
        $employeeIds = collect($node['employees'] ?? [])->pluck('user_id')->toArray();

        EmployeeProfile::where('organizational_unit_id', $unit->id)
            ->whereNotIn('user_id', $employeeIds)
            ->update([
                'organizational_unit_id' => null,
                'supervisor_id' => null // Optional: clear supervisor jika di-remove
            ]);

        // Simpan id user pertama di node ini untuk dijadikan atasan node child
        $currentUnitHeadId = null;

        foreach ($node['employees'] ?? [] as $index => $employee) {

            // Jadikan user pertama di array ini sebagai kepala dari unit tersebut
            if ($index === 0) {
                $currentUnitHeadId = $employee['user_id'];
            }

            EmployeeProfile::updateOrCreate(
                ['user_id' => $employee['user_id']],
                [
                    'organizational_unit_id' => $unit->id,
                    'jabatan_id' => $employee['jabatan_id'] ?? null,
                    'supervisor_id' => $supervisorId // <--- Set ke atasan yang diturunkan
                ]
            );
        }

        // ======================
        // Children Recursive
        // ======================

        // Siapa atasan untuk node di bawahnya? 
        // Jika unit ini punya head, gunakan id head tersebut. 
        // Jika kosong, teruskan atasan dari unit sebelumnya
        $nextSupervisorId = $currentUnitHeadId ?? $supervisorId;

        foreach ($node['children'] ?? [] as $child) {
            $this->saveNode($child, $unit->id, $savedIds, $nextSupervisorId);
        }

        return $unit;
    }
}