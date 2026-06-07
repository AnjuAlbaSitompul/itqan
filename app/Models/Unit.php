<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'outlet_id',
        'is_active',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function subunit()
    {
        return $this->hasMany(SubUnit::class);
    }
}
