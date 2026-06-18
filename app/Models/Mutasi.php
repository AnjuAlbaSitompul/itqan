<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $fillable = [
        'user_id',
        'from_id',
        'to_id',
        'jabatan_id',
        'is_head',
        'golongan',
        'reason',
        'effective_date',
        'status',
        'superior_approval_status',
        'approved_by',
        'superior_approved_by',
        'requested_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'from_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'to_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function superiorApprovedBy()
    {
        return $this->belongsTo(User::class, 'superior_approved_by');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
