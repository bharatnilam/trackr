<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class WatchlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'watchable_id',
        'watchable_type',
        'is_watched',
        'watched_at',
        'added_to_watchlist_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function watchable() {
        return $this->morphTo();
    }
}
