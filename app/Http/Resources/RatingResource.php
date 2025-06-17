<?php

namespace App\Http\Resources;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
            'rating' => $this->rating,
            'ratedItem' => $this->whenLoaded('rateable', function () {
                if ($this->rateable instanceof Movie) {
                    return new MovieResource($this->rateable);
                }
                if ($this->rateable instanceof TvShow) {
                    return new TvShowResource($this->rateable);
                }
                return $this->rateable;
            })
        ];
    }
}
