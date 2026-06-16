<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserKpi extends Model
{
    protected $fillable = [
        'user_id',
        'kpi_approval_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kpiApproval()
    {
        return $this->belongsTo(
            UserKpiApproval::class,
            'kpi_approval_id'
        );
    }

    public function realizations(): HasMany
    {
        return $this->hasMany(UserKpiRealization::class, 'user_kpi_id');
    }
}
