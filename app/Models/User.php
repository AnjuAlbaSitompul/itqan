<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'role_id',
    'password',
    'is_active'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function ownProfile()
    {
        return $this->hasOne(Profile::class);
    }

    // ==========================================
    // RELASI HIERARKI ATASAN & BAWAHAN BERDASARKAN UNIT
    // ==========================================

    /**
     * Mendapatkan data Atasan (1 Level di atas)
     * - Mengambil semua user dari parent unit tanpa mempedulikan status is_head.
     * - Jika user berada di puncak struktur (parent_id null), tampilkan user dengan role direksi & owner.
     */
    // ==========================================
    // RELASI HIERARKI ATASAN & BAWAHAN BERDASARKAN UNIT
    // ==========================================

    /**
     * Mendapatkan data Atasan (1 Level di atas)
     */
    public function superior()
    {
        $profile = $this->profile;

        if (!$profile || !$profile->organizational_unit_id) {
            return User::whereRaw('1 = 0');
        }

        $unit = OrganizationalUnit::find($profile->organizational_unit_id);

        // Jika dia BUKAN PUNCAK (punya parent_id)
        if ($unit && $unit->parent_id) {
            return User::whereHas('profile', function ($query) use ($unit) {
                $query->where('organizational_unit_id', $unit->parent_id);
            });
        }

        // Jika dia PUNCAK (tidak punya parent_id): Mengembalikan diri dia sendiri
        return User::where('id', $this->id);
    }

    /**
     * Mendapatkan list data bawahan langsung (1 Level di bawah)
     */
    public function subordinates()
    {
        $profile = $this->profile;

        if (!$profile || !$profile->organizational_unit_id) {
            return User::whereRaw('1 = 0');
        }

        $unitId = $profile->organizational_unit_id;
        $unit = OrganizationalUnit::find($unitId);

        // 1. Jika dia PUNCAK (tidak punya parent_id)
        if ($unit && is_null($unit->parent_id)) {
            return User::where(function ($query) use ($unitId) {
                // Ambil diri dia sendiri
                $query->where('id', $this->id)
                    // Gabungkan dengan bawahan langsung
                    ->orWhereHas('profile', function ($q) use ($unitId) {
                        $q->whereIn('organizational_unit_id', function ($sub) use ($unitId) {
                            $sub->select('id')
                                ->from('organizational_units')
                                ->where('parent_id', $unitId);
                        });
                    });
            });
        }

        // 2. Jika dia BUKAN PUNCAK: Ambil bawahan langsung saja
        return User::whereHas('profile', function ($query) use ($unitId) {
            $query->whereIn('organizational_unit_id', function ($subQuery) use ($unitId) {
                $subQuery->select('id')
                    ->from('organizational_units')
                    ->where('parent_id', $unitId);
            });
        });
    }

    // ==========================================
    // MENGAMBIL SELURUH BAWAHAN (REKURSIF)
    // ==========================================

    /**
     * Fungsi helper untuk mendapatkan semua bawahan dalam bentuk 1 list datar
     * (Menggunakan rekursi murni dari relasi builder)
     */
    public function getAllSubordinates()
    {
        $allSubordinates = new Collection();

        $directSubordinates = $this->subordinates()->with('profile')->get();

        foreach ($directSubordinates as $subordinate) {
            $allSubordinates->push($subordinate);

            // CEGAH INFINITE LOOP: 
            // Jangan lakukan rekursi jika subordinate adalah diri dia sendiri
            if ($subordinate->id !== $this->id) {
                $allSubordinates = $allSubordinates->merge($subordinate->getAllSubordinates());
            }
        }

        return $allSubordinates->unique('id')->values();
    }

    public function getAllUsersInOrgStructure()
    {
        // Cek apakah user punya profil dan terikat dengan sebuah unit organisasi
        if (!$this->profile || !$this->profile->organizational_unit_id) {
            return new Collection();
        }

        // Ambil unit organisasi user ini beserta seluruh anak-anaknya secara rekursif
        $orgUnit = OrganizationalUnit::with([
            'childrenRecursive.employees.user',
            'employees.user'
        ])->find($this->profile->organizational_unit_id);

        $allUsers = new Collection();

        // Fungsi closure untuk mengekstrak user dari tiap level unit
        $extractUsers = function ($unit) use (&$extractUsers, &$allUsers) {
            // Ambil semua pegawai di unit ini
            foreach ($unit->employees as $employee) {
                // Masukkan user (kecuali diri sendiri) ke dalam list
                if ($employee->user && $employee->user->id !== $this->id) {
                    $allUsers->push($employee->user);
                }
            }

            // Telusuri juga anak-anak dari unit ini (Sub-departemen / divisi di bawahnya)
            foreach ($unit->childrenRecursive as $childUnit) {
                $extractUsers($childUnit);
            }
        };

        $extractUsers($orgUnit);

        // Mengembalikan data secara unik (berjaga-jaga jika ada user rangkap jabatan di tabel profile)
        return $allUsers->unique('id')->values();
    }

    public function kpis()
    {
        return $this->hasMany(UserKpi::class);
    }

    public function approvals()
    {
        return $this->hasMany(UserKpiApproval::class, 'approver_id');
    }

    public function createdKpis()
    {
        return $this->hasMany(KpiMaster::class, 'created_by');
    }

    public function createdKpiApprovals()
    {
        return $this->hasMany(UserKpiApproval::class, 'created_by');
    }

    public function joggingTracks()
    {
        return $this->hasMany(JoggingTrack::class);
    }

    public function prayerReports()
    {
        return $this->hasMany(PrayerReport::class);
    }

    public function bookProposals()
    {
        return $this->hasMany(BookProposal::class);
    }

    public function bookLogs()
    {
        return $this->hasMany(BookProposal::class);
    }

    public function mutasis()
    {
        return $this->hasMany(Mutasi::class);
    }

    public function requestedMutasis()
    {
        return $this->hasMany(Mutasi::class, 'requested_by');
    }

    public function approvedMutasis()
    {
        return $this->hasMany(Mutasi::class, 'approved_by');
    }

    public function peringatans()
    {
        return $this->hasMany(Peringatan::class);
    }

    public function requestedPeringatans()
    {
        return $this->hasMany(Peringatan::class, 'requested_by');
    }

    public function approvedPeringatans()
    {
        return $this->hasMany(Peringatan::class, 'approved_by');
    }
}