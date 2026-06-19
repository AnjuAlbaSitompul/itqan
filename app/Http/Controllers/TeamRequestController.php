<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\ManPowerRequest;
use App\Models\Mutasi;
use App\Models\OrganizationalUnit;
use App\Models\Peringatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeamRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $users = $user
            ? $this->getManagedUsers($user)->load(['profile.organizationalUnit', 'profile.jabatan'])
            : collect();

        $organizationalUnits = $user
            ? $this->getAccessibleOrganizationalUnits($user)
            : collect();

        $jabatans = Jabatan::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $approvers = $user->superior()->get() ?? collect();

        return view('team.request.index', compact(
            'users',
            'organizationalUnits',
            'jabatans',
            'approvers'
        ));
    }

    public function detailUser(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => [
                'required',
                Rule::in(['peringatan', 'mutasi', 'man_power'])
            ],
        ]);

        $user = User::with([
            'profile.jabatan',
            'profile.organizationalUnit'
        ])->findOrFail($request->user_id);

        $data = [];

        switch ($request->type) {
            case 'peringatan':
                $activeWarning = $user->peringatans()
                    ->where('status', 'approved')
                    ->whereDate('due_date', '>=', now())
                    ->latest()
                    ->first();

                // Disesuaikan dengan array Rule validasi pada method store
                $types = ['peringatan_1', 'peringatan_2', 'peringatan_3'];

                if ($activeWarning) {
                    $index = array_search($activeWarning->type, $types);
                    $availableTypes = collect($types)
                        ->slice($index + 1)
                        ->values();
                } else {
                    $availableTypes = collect($types);
                }

                $data = [
                    'user' => $user,
                    'active_warning' => $activeWarning,
                    'available_types' => $availableTypes,
                ];
                break;

            case 'mutasi':
                $currentUnitId = $user->profile?->organizational_unit_id;
                $unitType = $user->profile?->organizationalUnit?->type;

                $availableUnits = OrganizationalUnit::query()
                    ->where('type', $unitType)
                    ->where('id', '!=', $currentUnitId)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name']);

                $data = [
                    'user' => $user,
                    'current_unit' => $user->profile?->organizationalUnit,
                    'available_units' => $availableUnits,
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function storePeringatan(Request $request)
    {
        $requester = Auth::user();
        $allowedUsers = $requester ? $this->getManagedUsers($requester) : collect();
        $allowedUserIds = $allowedUsers->pluck('id')->all();

        $validated = $request->validate([
            'user_id' => ['required', Rule::in($allowedUserIds)],
            'type' => ['required', Rule::in(['peringatan_1', 'peringatan_2', 'peringatan_3'])],
            'reason' => ['required', 'string'],
        ]);

        $approvers = $requester?->profile?->organizationalUnit
            ? $this->resolveApprovers($requester->profile->organizationalUnit)
            : collect();

        $peringatan = DB::transaction(function () use ($validated, $requester) {
            return Peringatan::create([
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'issued_date' => now(), // Auto issue hari ini
                'due_date' => null,
                'status' => 'pending',
                'superior_approval_status' => 'pending',
                'approved_by' => null,
                'superior_approved_by' => null,
                'requested_by' => $requester?->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peringatan berhasil dikirim.',
            'data' => $peringatan->load(['user:id,name', 'requestedBy:id,name']),
            'approvers' => $approvers->map(function ($approver) {
                return [
                    'id' => $approver->id,
                    'name' => $approver->name,
                ];
            })->values(),
        ]);
    }

    public function storeMutasi(Request $request)
    {


        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'to_id' => ['required', 'exists:organizational_units,id'],
            'reason' => ['required', 'string'], // Wajib diisi sesuai permintaan
        ]);

        $unitId = User::find($validated['user_id'])->profile?->organizational_unit_id;
        $approvers = User::find($validated['user_id'])->Superior()->get() ?? collect();
        $type = OrganizationalUnit::find($unitId)?->type;
        $toUnitType = OrganizationalUnit::findOrFail($validated['to_id'])->type;

        if ($type !== $toUnitType) {
            return response()->json([
                'message' => 'Unit tujuan harus memiliki tipe yang sama dengan unit asal.',
                'errors' => ['to_id' => ['Unit tujuan harus memiliki tipe yang sama dengan unit asal.']]
            ], 422);
        }


        // Validasi tambahan untuk mencegah mutasi ke unit yang sama
        if ($unitId == $validated['to_id']) {
            return response()->json([
                'message' => 'Unit tujuan tidak boleh sama dengan unit asal.',
                'errors' => ['to_id' => ['Unit tujuan tidak boleh sama dengan unit asal.']]
            ], 422);
        }


        $mutasi = DB::transaction(function () use ($unitId, $validated) {
            return Mutasi::create([
                'user_id' => $validated['user_id'],
                'from_id' => $unitId,
                'to_id' => $validated['to_id'],
                'is_head' => false,
                'golongan' => null,
                'reason' => $validated['reason'],
                'effective_date' => null,
                'status' => 'pending',
                'superior_approval_status' => 'pending',
                'approved_by' => null,
                'superior_approved_by' => null,
                'requested_by' => Auth::id(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Permintaan mutasi berhasil dikirim.',
            'data' => $mutasi->load(['user:id,name', 'fromUnit:id,name', 'toUnit:id,name', 'requestedBy:id,name']),
            'approvers' => $approvers->map(function ($approver) {
                return [
                    'id' => $approver->id,
                    'name' => $approver->name,
                ];
            })->values(),
        ]);
    }



    public function storeManPower(Request $request)
    {
        $requester = Auth::user();
        $allowedUnits = $requester ? $this->getAccessibleOrganizationalUnits($requester) : collect();
        $allowedUnitIds = $allowedUnits->pluck('id')->all();

        $validated = $request->validate([
            'organizational_unit_id' => ['required', Rule::in($allowedUnitIds)],
            'jumlah_manpower' => ['nullable', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ]);

        $unit = OrganizationalUnit::with('employees.user')->findOrFail($validated['organizational_unit_id']);
        $approvers = $this->resolveApprovers($unit);

        $manPowerRequest = DB::transaction(function () use ($validated, $requester) {
            return ManPowerRequest::create([
                'requested_by' => $requester?->id,
                'organizational_unit_id' => $validated['organizational_unit_id'],
                'jumlah_manpower' => $validated['jumlah_manpower'] ?? null,
                'reason' => $validated['reason'] ?? null,
                'status' => 'pending',
                'superior_approval_status' => 'pending',
                'approved_by' => null,
                'superior_approved_by' => null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Permintaan man power berhasil dikirim.',
            'data' => $manPowerRequest->load([
                'requestedBy:id,name',
                'organizationalUnit:id,name',
            ]),
            'approvers' => $approvers->map(function ($approver) {
                return [
                    'id' => $approver->id,
                    'name' => $approver->name,
                ];
            })->values(),
        ]);
    }



    private function resolveApprovers(OrganizationalUnit $unit)
    {
        $current = $unit;

        while ($current) {
            $current->loadMissing(['employees.user', 'parent.employees.user']);

            $heads = $current->employees
                ->where('is_head', true)
                ->pluck('user')
                ->filter()
                ->unique('id')
                ->values();

            if ($heads->isNotEmpty()) {
                return $heads;
            }

            $current = $current->parent;
        }

        return collect();
    }

    private function getAccessibleOrganizationalUnits(User $user): Collection
    {
        $unitId = $user->profile?->organizational_unit_id;

        if (!$unitId) {
            return collect();
        }

        $rootUnit = OrganizationalUnit::with('childrenRecursive')->find($unitId);

        if (!$rootUnit) {
            return collect();
        }

        $units = collect();

        $appendUnitTree = function (OrganizationalUnit $unit) use (&$appendUnitTree, &$units) {
            $units->push($unit);

            foreach ($unit->childrenRecursive as $childUnit) {
                $appendUnitTree($childUnit);
            }
        };

        $appendUnitTree($rootUnit);

        return $units
            ->unique('id')
            ->values();
    }

    private function getManagedUsers(User $user): Collection
    {
        return $user->getAllUsersInOrgStructure();
    }
}
