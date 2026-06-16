<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserKpiRealization extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (opsional, tapi baik untuk kejelasan).
     */
    protected $table = 'user_kpi_realizations';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'user_kpi_id',
        'user_kpi_detail_id',
        'realization',
        'achievement_percent',
        'nilai',
        'notes',
        'created_by',
    ];

    /**
     * Casting tipe data kolom agar otomatis dikonversi saat ditarik dari database.
     */
    protected $casts = [
        'realization' => 'decimal:2',
        'achievement_percent' => 'decimal:2',
        'nilai' => 'decimal:2',
    ];

    /**
     * Relasi ke model UserKpi (Menunjukkan target KPI milik siapa).
     */
    public function userKpi(): BelongsTo
    {
        return $this->belongsTo(UserKpi::class, 'user_kpi_id');
    }

    /**
     * Relasi ke model UserKpiDetail (Menunjukkan master KPI spesifik yang direalisasikan).
     */
    public function userKpiDetail(): BelongsTo
    {
        return $this->belongsTo(UserKpiDetail::class, 'user_kpi_detail_id');
    }

    /**
     * Relasi ke model User (Siapa yang membuat/menginput realisasi KPI ini).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}