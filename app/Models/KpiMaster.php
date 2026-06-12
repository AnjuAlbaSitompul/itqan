<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiMaster extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'definition_of_done',
        'guard_rail',
        'satuan',
        'bobot',
        'target',
        'is_active',
    ];

    public function formulas()
    {
        return $this->hasMany(Formula::class);
    }
}
