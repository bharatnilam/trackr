<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_id',
        'reviewable_type',
        // 'title',
        'body'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reviewable() {
        return $this->morphTo();
    }
}
