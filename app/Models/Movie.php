<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'release_year',
        'pg_rating',
        'released_at',
        'runtime',
        'director',
        'genre',
        'actors',
        'synopsis',
        'poster_image_url',
        'external_id',
        'available_platforms'
    ];

    public function watchlistItems() {
        return $this->morphMany(WatchlistItem::class, 'watchable');
    }

    public function ratings() {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function reviews() {
        return $this->morphMany(Review::class,'reviewable');
    }
}
