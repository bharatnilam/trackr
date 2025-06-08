<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'tv_show_id',
        'season_number',
        'title',
        'synopsis',
        'poster_image_url',
        'released_at',
        'number_of_episodes'
    ];

    public function tvShow() {
        return $this->belongsTo(TvShow::class);
    }

    public function ratings() {
        return $this->morphMany(Rating::class,'rateable');
    }
}
