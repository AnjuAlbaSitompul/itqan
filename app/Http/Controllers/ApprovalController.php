<?php

namespace App\Http\Controllers;

use App\Models\ManPowerRequest;
use App\Models\Mutasi;
use App\Models\Peringatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApprovalController extends Controller
{
    public function approvalRequest()
    {
        return view('approval.request.index');
    }

    public function list(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $subordinateIds = $user->getAllUsersInOrgStructure()
            ->map(function (User $subordinate) {
                return $subordinate->id;
            })
            ->values();

        $validated = $request->validate([
            'type' => ['nullable', Rule::in(['all', 'mutasi', 'peringatan', 'manpower'])],
            'status' => ['nullable', Rule::in(['all', 'pending', 'approved', 'rejected'])],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $typeFilter = $validated['type'] ?? 'all';
        $statusFilter = $validated['status'] ?? 'pending';
        $search = trim($validated['search'] ?? '');

        $items = collect();

        if ($typeFilter === 'all' || $typeFilter === 'mutasi') {
            $items = $items->merge($this->loadMutasiItems($subordinateIds, $statusFilter, $search));
        }

        if ($typeFilter === 'all' || $typeFilter === 'peringatan') {
            $items = $items->merge($this->loadPeringatanItems($subordinateIds, $statusFilter, $search));
        }

        if ($typeFilter === 'all' || $typeFilter === 'manpower') {
            $items = $items->merge($this->loadManPowerItems($subordinateIds, $statusFilter, $search));
        }

        $items = $items->sortByDesc(function (array $item) {
            return $item['created_at'];
        })->values();

        $pendingCount = $items->filter(function (array $item) {
            return $item['status'] === 'pending';
        })->count();

        $approvedCount = $items->filter(function (array $item) {
            return $item['status'] === 'approved';
        })->count();

        $rejectedCount = $items->filter(function (array $item) {
            return $item['status'] === 'rejected';
        })->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $items->count(),
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,
            ],
            'data' => $items,
        ]);
    }

    public function approve(Request $request, string $type, int $id)
    {
        return $this->processAction($request, $type, $id, 'approved');
    }

    public function reject(Request $request, string $type, int $id)
    {
        return $this->processAction($request, $type, $id, 'rejected');
    }

    private function processAction(Request $request, string $type, int $id, string $decision)
    {
        $request->validate([
            'type' => ['nullable', Rule::in(['mutasi', 'peringatan', 'manpower'])],
        ]);

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $subordinateIds = $user->getAllUsersInOrgStructure()
            ->map(function (User $subordinate) {
                return $subordinate->id;
            })
            ->values()
            ->all();

        $record = match ($type) {
            'mutasi' => Mutasi::whereKey($id)->whereIn('requested_by', $subordinateIds)->first(),
            'peringatan' => Peringatan::whereKey($id)->whereIn('requested_by', $subordinateIds)->first(),
            'manpower' => ManPowerRequest::whereKey($id)->whereIn('requested_by', $subordinateIds)->first(),
            default => null,
        };

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Request tidak ditemukan atau bukan bawahan Anda.',
            ], 404);
        }

        if ($record->superior_approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Request ini sudah diproses sebelumnya.',
            ], 422);
        }

        DB::transaction(function () use ($record, $user, $decision) {
            $record->update([
                'superior_approval_status' => $decision,
                'superior_approved_by' => $user->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Request berhasil di' . ($decision === 'approved' ? 'setujui' : 'tolak') . '.',
            'status' => $decision,
        ]);
    }

    private function loadMutasiItems(Collection $subordinateIds, string $statusFilter, string $search): Collection
    {
        return Mutasi::with([
            'user.profile.jabatan',
            'user.profile.organizationalUnit',
            'fromUnit',
            'toUnit',
            'jabatan',
            'requestedBy.profile.jabatan',
            'requestedBy.profile.organizationalUnit',
        ])
            ->whereIn('requested_by', $subordinateIds)
            ->when($statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->whereRaw('superior_approval_status = ?', [$statusFilter]);
            })
            ->when($search !== '', fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%')))
            ->latest()
            ->get()
            ->map(function (Mutasi $item) {
                return $this->formatItem(
                    'mutasi',
                    $item,
                    'Mutasi',
                    $item->user?->name ?? '-',
                    $item->requestedBy?->name ?? '-',
                    [
                        ['label' => 'Karyawan', 'value' => $item->user?->name ?? '-'],
                        ['label' => 'Dari Unit', 'value' => $item->fromUnit?->name ?? '-'],
                        ['label' => 'Ke Unit', 'value' => $item->toUnit?->name ?? '-'],
                        ['label' => 'Jabatan', 'value' => $item->jabatan?->name ?? '-'],
                        ['label' => 'Golongan', 'value' => $item->golongan ?? '-'],
                        ['label' => 'Efektif', 'value' => $item->effective_date ? $item->effective_date->format('d M Y') : '-'],
                        ['label' => 'Kepala Unit', 'value' => $item->is_head ? 'Ya' : 'Tidak'],
                        ['label' => 'Alasan', 'value' => $item->reason ?? '-'],
                    ]
                );
            });
    }

    private function loadPeringatanItems(Collection $subordinateIds, string $statusFilter, string $search): Collection
    {
        return Peringatan::with([
            'user.profile.jabatan',
            'user.profile.organizationalUnit',
            'requestedBy.profile.jabatan',
            'requestedBy.profile.organizationalUnit',
        ])
            ->whereIn('requested_by', $subordinateIds)
            ->when($statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->whereRaw('superior_approval_status = ?', [$statusFilter]);
            })
            ->when($search !== '', fn($query) => $query->whereHas('user', fn($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%')))
            ->latest()
            ->get()
            ->map(function (Peringatan $item) {
                return $this->formatItem(
                    'peringatan',
                    $item,
                    'Peringatan',
                    $item->user?->name ?? '-',
                    $item->requestedBy?->name ?? '-',
                    [
                        ['label' => 'Karyawan', 'value' => $item->user?->name ?? '-'],
                        ['label' => 'Jenis', 'value' => $item->type ?? '-'],
                        ['label' => 'Tanggal Terbit', 'value' => $this->formatDateValue($item->issued_date)],
                        ['label' => 'Tenggat', 'value' => $this->formatDateValue($item->due_date)],
                        ['label' => 'Alasan', 'value' => $item->reason ?? '-'],
                    ]
                );
            });
    }

    private function loadManPowerItems(Collection $subordinateIds, string $statusFilter, string $search): Collection
    {
        return ManPowerRequest::with([
            'requestedBy.profile.jabatan',
            'requestedBy.profile.organizationalUnit',
            'organizationalUnit',
        ])
            ->whereIn('requested_by', $subordinateIds)
            ->when($statusFilter !== 'all', function ($query) use ($statusFilter) {
                $query->whereRaw('superior_approval_status = ?', [$statusFilter]);
            })
            ->when($search !== '', fn($query) => $query->whereHas('organizationalUnit', fn($unitQuery) => $unitQuery->where('name', 'like', '%' . $search . '%')))
            ->latest()
            ->get()
            ->map(function (ManPowerRequest $item) {
                return $this->formatItem(
                    'manpower',
                    $item,
                    'Man Power',
                    $item->organizationalUnit?->name ?? '-',
                    $item->requestedBy?->name ?? '-',
                    [
                        ['label' => 'Unit', 'value' => $item->organizationalUnit?->name ?? '-'],
                        ['label' => 'Jumlah', 'value' => $item->jumlah_manpower ? $item->jumlah_manpower . ' orang' : '-'],
                        ['label' => 'Alasan', 'value' => $item->reason ?? '-'],
                    ]
                );
            });
    }

    private function formatItem(string $type, $item, string $typeLabel, string $title, string $requestedBy, array $details): array
    {
        $statusMeta = match ($item->superior_approval_status) {
            'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
            default => ['label' => 'Pending', 'class' => 'bg-warning text-dark'],
        };

        $typeMeta = match ($type) {
            'mutasi' => ['class' => 'bg-info text-dark'],
            'peringatan' => ['class' => 'bg-warning text-dark'],
            'manpower' => ['class' => 'bg-primary'],
            default => ['class' => 'bg-secondary'],
        };

        return [
            'id' => $item->id,
            'type' => $type,
            'type_label' => $typeLabel,
            'type_badge_class' => $typeMeta['class'],
            'status' => $item->superior_approval_status,
            'status_label' => $statusMeta['label'],
            'status_badge_class' => $statusMeta['class'],
            'title' => $title,
            'requested_by' => $requestedBy,
            'subtitle' => $this->buildSubtitle($type, $item),
            'created_at' => $item->created_at,
            'created_at_label' => $item->created_at ? $item->created_at->format('d M Y, H:i') : '-',
            'details' => $details,
            'action_url' => route('approval.request.approve', ['type' => $type, 'id' => $item->id]),
            'reject_url' => route('approval.request.reject', ['type' => $type, 'id' => $item->id]),
        ];
    }

    private function buildSubtitle(string $type, $item): string
    {
        return match ($type) {
            'mutasi' => trim(($item->fromUnit?->name ?? '-') . ' ke ' . ($item->toUnit?->name ?? '-')),
            'peringatan' => 'Peringatan untuk ' . ($item->user?->name ?? '-'),
            'manpower' => 'Kebutuhan tenaga untuk ' . ($item->organizationalUnit?->name ?? '-'),
            default => '-',
        };
    }

    private function formatDateValue($value): string
    {
        if (empty($value)) {
            return '-';
        }

        try {
            return Carbon::parse($value)->format('d M Y');
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
