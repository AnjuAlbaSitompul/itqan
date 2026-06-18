<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Peringatan extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'issued_date',
        'due_date',
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
