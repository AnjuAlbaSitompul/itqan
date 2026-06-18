<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReadingLog extends Model
{
    protected $fillable = [

        'book_proposal_id',
        'page_from',
        'page_to',
        'summary',
        'log_date',
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
