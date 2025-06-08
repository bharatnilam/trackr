<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
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
        'number_of_seasons',
        'number_of_episodes',
        'status',
        'poster_image_url',
        'external_id',
        'available_platforms'
    ];

    public function seasons() {
        return $this->hasMany(Season::class);
    }

    public function watchlistItems() {
        return $this->morphMany(WatchlistItem::class,'watchable');
    }

    public function ratings() {
        return $this->morphMany(Rating::class,'rateable');
    }

    public function reviews() {
        return $this->morphMany(Review::class,'reviewable');
    }
}
