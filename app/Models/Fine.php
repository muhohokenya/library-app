<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $guarded = [];


    public function member():BelongsTo{
        return $this->belongsTo(Member::class,'member_id');
    }


    public function bookIssue():BelongsTo{
        return $this->belongsTo(BookIssue::class,'book_issue_id');
    }
}
