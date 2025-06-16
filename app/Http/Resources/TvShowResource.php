<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'releaseYear' => $this->release_year,
            'pgRating' => $this->pg_rating,
            'runtime' => $this->runtime,
            'director' => $this->director,
            'genre' => $this->genre,
            'actors' => $this->actors,
            'synopsis' => $this->synopsis,
            'numberOfSeasons' => $this->number_of_seasons,
            'numberOfEpisodes' => $this->number_of_episodes,
            'status' => $this->status,
            'posterImageUrl' => $this->poster_image_url,
            'externalId' => $this->external_id,
            'availablePlatforms' => $this->available_platforms
        ];
    }
}
