<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKpiApproval extends Model
{
    protected $fillable = [
        'level',
        'kpi_period_id',
        'approver_id',
        'created_by',
        'status',
        'notes',
        'approved_at'
    ];

    public function kpiPeriod()
    {
        return $this->belongsTo(KpiPeriod::class, 'kpi_period_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kpiDetails()
    {
        return $this->hasMany(UserKpiDetail::class, 'kpi_approval_id');
    }

    public function userKpis()
    {
        return $this->hasMany(UserKpi::class, 'kpi_approval_id');
    }


}
