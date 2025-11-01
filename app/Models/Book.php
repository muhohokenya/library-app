<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'quantity',
        'available_quantity',
        'publisher',
        'published_year',
    ];

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function activeIssues()
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }
}
