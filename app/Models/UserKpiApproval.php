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
}
