<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookProposal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'status', // pending, approved, rejected
        'superior_id', // Atasan yang akan menyetujui proposal
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function superior()
    {
        return $this->belongsTo(User::class, 'superior_id');
    }

    public function readingLogs()
    {
        return $this->hasMany(BookReadingLog::class);
    }
}
