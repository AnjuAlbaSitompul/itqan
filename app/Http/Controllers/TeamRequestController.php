<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\ManPowerRequest;
use App\Models\Mutasi;
use App\Models\OrganizationalUnit;
use App\Models\Peringatan;
use App\Models\User;
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

        $approvers = $user?->profile?->organizationalUnit
            ? $this->resolveApprovers($user->profile->organizationalUnit)
            : collect();

        return view('team.request.index', compact(
            'users',
            'organizationalUnits',
            'jabatans',
            'approvers'
        ));
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

    public function storePeringatan(Request $request)
    {
        $requester = Auth::user();
        $allowedUsers = $requester ? $this->getManagedUsers($requester) : collect();
        $allowedUserIds = $allowedUsers->pluck('id')->all();

        $validated = $request->validate([
            'user_id' => ['required', Rule::in($allowedUserIds)],
            'type' => ['required', Rule::in(['peringatan_1', 'peringatan_2', 'peringatan_3'])],
            'reason' => ['required', 'string'],
            'issued_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issued_date'],
        ]);

        $approvers = $requester?->profile?->organizationalUnit
            ? $this->resolveApprovers($requester->profile->organizationalUnit)
            : collect();

        $peringatan = DB::transaction(function () use ($validated, $requester) {
            return Peringatan::create([
                'user_id' => $validated['user_id'],
                'type' => $validated['type'],
                'reason' => $validated['reason'],
                'issued_date' => $validated['issued_date'],
                'due_date' => $validated['due_date'] ?? null,
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
        $requester = Auth::user();
        $allowedUsers = $requester ? $this->getManagedUsers($requester) : collect();
        $allowedUnits = $requester ? $this->getAccessibleOrganizationalUnits($requester) : collect();
        $allowedUserIds = $allowedUsers->pluck('id')->all();
        $allowedUnitIds = $allowedUnits->pluck('id')->all();

        $validated = $request->validate([
            'user_id' => ['required', Rule::in($allowedUserIds)],
            'from_id' => ['required', Rule::in($allowedUnitIds)],
            'to_id' => ['required', 'different:from_id', Rule::in($allowedUnitIds)],
            'jabatan_id' => ['required', 'exists:jabatans,id'],
            'is_head' => ['nullable', 'boolean'],
            'golongan' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string'],
            'effective_date' => ['nullable', 'date'],
        ]);

        $approvers = $requester?->profile?->organizationalUnit
            ? $this->resolveApprovers($requester->profile->organizationalUnit)
            : collect();

        $mutasi = DB::transaction(function () use ($validated, $requester) {
            return Mutasi::create([
                'user_id' => $validated['user_id'],
                'from_id' => $validated['from_id'],
                'to_id' => $validated['to_id'],
                'jabatan_id' => $validated['jabatan_id'],
                'is_head' => (bool) ($validated['is_head'] ?? false),
                'golongan' => $validated['golongan'],
                'reason' => $validated['reason'] ?? null,
                'effective_date' => $validated['effective_date'] ?? null,
                'status' => 'pending',
                'superior_approval_status' => 'pending',
                'approved_by' => null,
                'superior_approved_by' => null,
                'requested_by' => $requester?->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Permintaan mutasi berhasil dikirim.',
            'data' => $mutasi->load(['user:id,name', 'fromUnit:id,name', 'toUnit:id,name', 'jabatan:id,name', 'requestedBy:id,name']),
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
