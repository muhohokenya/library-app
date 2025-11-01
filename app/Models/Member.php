<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'member_type',
    ];

    // Auto-generate member ID before creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->member_id)) {
                $member->member_id = self::generateMemberId();
            }
        });
    }

    // Generate unique member ID
    public static function generateMemberId(): string
    {
        do {
            $id = 'LIB' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('member_id', $id)->exists());

        return $id;
    }

    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function activeIssues()
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
