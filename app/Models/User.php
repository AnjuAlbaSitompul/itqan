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

    // ==========================================
    // RELASI HIERARKI ATASAN & BAWAHAN
    // ==========================================

    /**
     * Mendapatkan data langsung dari Atasan / Supervisor
     * Cara memanggil: $user->superior
     */
    public function superior()
    {
        return $this->hasOneThrough(
            User::class,            // Model target (Atasan)
            EmployeeProfile::class, // Model perantara (Profile kita)
            'user_id',              // Foreign key pada tabel perantara
            'id',                   // Foreign key pada tabel target
            'id',                   // Local key pada tabel kita
            'supervisor_id'         // Local key pada tabel perantara yang menuju ke target
        );
    }

    /**
     * Mendapatkan list data seluruh bawahan langsung
     * Cara memanggil: $user->subordinates
     */
    public function subordinates()
    {
        return $this->hasManyThrough(
            User::class,            // Model target (Bawahan)
            EmployeeProfile::class, // Model perantara (Profile bawahan)
            'supervisor_id',        // Foreign key pada tabel perantara (Menunjuk ke ID kita)
            'id',                   // Foreign key pada tabel target
            'id',                   // Local key pada tabel kita
            'user_id'               // Local key pada tabel perantara yang menuju target
        );
    }

    // ==========================================

    // ==========================================
    // MENGAMBIL SELURUH BAWAHAN (REKURSIF)
    // ==========================================

    /**
     * 1. Relasi Eager Load untuk bawahan rekursif (nested)
     * Cara memanggil: $user->subordinatesRecursive
     */
    public function subordinatesRecursive()
    {
        return $this->subordinates()->with('subordinatesRecursive');
    }

    /**
     * 2. Fungsi helper untuk mendapatkan semua bawahan dalam bentuk 1 list datar (Flat Collection)
     * Cara memanggil: $user->getAllSubordinates()
     */
    public function getAllSubordinates()
    {
        // Panggil relasi rekursif di atas
        $subordinates = $this->subordinatesRecursive;

        $allSubordinates = new Collection();

        // Buat fungsi closure untuk meratakan (flatten) data dari tree ke dalam satu array/collection
        $flattenSubordinates = function ($users) use (&$flattenSubordinates, &$allSubordinates) {
            foreach ($users as $user) {
                // Masukkan user ke dalam list
                $allSubordinates->push($user);

                // Jika user ini punya bawahan lagi, panggil fungsi ini lagi secara rekursif
                if ($user->subordinatesRecursive->isNotEmpty()) {
                    $flattenSubordinates($user->subordinatesRecursive);
                }
            }
        };

        $flattenSubordinates($subordinates);

        return $allSubordinates;
    }

    public function getAllUsersInOrgStructure()
    {
        // Cek apakah user punya profil dan terikat dengan sebuah unit organisasi
        if (!$this->profile || !$this->profile->organizational_unit_id) {
            return collect();
        }

        // Ambil unit organisasi user ini beserta seluruh anak-anaknya secara rekursif
        $orgUnit = OrganizationalUnit::with([
            'childrenRecursive.employees.user',
            'employees.user'
        ])->find($this->profile->organizational_unit_id);

        $allUsers = collect();

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

}