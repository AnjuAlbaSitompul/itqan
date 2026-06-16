<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKpiLog extends Model
{
    protected $fillable = [
        'kpi_approval_id',
        'user_id',
    ];

    public function kpiApproval()
    {
        return $this->belongsTo(UserKpiApproval::class, 'kpi_approval_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
