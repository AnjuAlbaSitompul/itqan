<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee_job_level_request extends Model
{
    protected $fillable = [
        'user_id',
        'period',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
