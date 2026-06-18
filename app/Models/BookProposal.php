<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookProposal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'status',
        'approved_by', // Ubah superior_id jadi ini
        'due_date',    // Tambahkan due_date
    ];

    // ... (relasi user dan readingLogs biarkan tetap)

    // Ubah nama fungsi relasi atasan agar lebih sesuai konteks
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function readingLogs()
    {
        return $this->hasMany(BookReadingLog::class);
    }
}
