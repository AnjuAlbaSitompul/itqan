<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKpi extends Model
{
    protected $fillable = [
        'user_id',
        'master_kpi_id',
        'period_id',
        'weight',
        'target',
        'achievement_percent',
        'score',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterKpi()
    {
        return $this->belongsTo(KpiMaster::class, 'kpi_master_id');
    }

    public function period()
    {
        return $this->belongsTo(KpiPeriod::class, 'period_id');
    }
}
