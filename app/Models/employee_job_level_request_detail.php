<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employee_job_level_request_detail extends Model
{
    protected $fillable = [
        'request_id',
        'job_level_id',
    ];

    public function employeeJobLevelRequest()
    {
        return $this->belongsTo(employee_job_level_request::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(job_level::class);
    }
}
