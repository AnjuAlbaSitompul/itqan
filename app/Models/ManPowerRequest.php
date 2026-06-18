<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationalUnit;
use App\Models\User;

class ManPowerRequest extends Model
{
    protected $fillable = [
        'requested_by',
        'organizational_unit_id',
        'jumlah_manpower',
        'reason',
        'status',
        'superior_approval_status',
        'approved_by',
        'superior_approved_by',
    ];

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'organizational_unit_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function superiorApprovedBy()
    {
        return $this->belongsTo(User::class, 'superior_approved_by');
    }
}
