<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
