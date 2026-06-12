<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    protected $fillable = [
        'kpi_master_id',
        'from',
        'to',
    ];

    public function kpiMaster()
    {
        return $this->belongsTo(KPIMaster::class);
    }
}
