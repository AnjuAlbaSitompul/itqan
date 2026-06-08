<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiPeriod extends Model
{
    protected $fillable = [
        'name',
        'registration_start',
        'registration_end',
        'period_start',
        'period_end',
        'status',
        'created_by'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
