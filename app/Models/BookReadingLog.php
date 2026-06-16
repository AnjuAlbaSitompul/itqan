<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReadingLog extends Model
{
    protected $fillable = [
        'user_id',
        'book_proposal_id',
        'pages_read',
        'reading_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookProposal()
    {
        return $this->belongsTo(BookProposal::class);
    }
}
