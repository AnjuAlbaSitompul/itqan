<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id',
        'outlet_id',
        'supervisor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(job_level::class);
    }
}
