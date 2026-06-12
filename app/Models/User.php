<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
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
}