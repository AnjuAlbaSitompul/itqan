<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKpiDetail extends Model
{
    protected $fillable = [
        'kpi_approval_id',
        'kpi_master_id',

    ];

    public function kpiApproval()
    {
        return $this->belongsTo(UserKpiApproval::class, 'kpi_approval_id');
    }

    public function masterKpi()
    {
        return $this->belongsTo(KpiMaster::class, 'kpi_master_id');
    }
}
