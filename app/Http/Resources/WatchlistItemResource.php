<?php

namespace App\Http\Resources;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WatchlistItemResource extends JsonResource
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
            'isWatched' => $this->is_watched,
            'watchedAt' => $this->watched_at,
            'addedAt' => $this->added_to_watchlist_at,
            'item' => $this->whenLoaded('watchable', function () {
                if ($this->watchable instanceof Movie) {
                    return new MovieResource($this->watchable);
                }
                if ($this->watchable instanceof TvShow) {
                    return new TvShowResource($this->watchable);
                }
                return $this->watchable;
            })
        ];
    }
}
