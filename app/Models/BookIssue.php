<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_id',
        'issue_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Check if book is overdue
    public function isOverdue(): bool
    {
        if ($this->status === 'returned') {
            return false;
        }
        return Carbon::parse($this->due_date)->isPast();
    }


    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

}
