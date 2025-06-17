<?php

namespace App\Http\Resources;

use App\Models\Movie;
use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'body' => $this->body,
            'reviewedItem' => $this->whenLoaded('reviewable', function () {
                if ($this->reviewable instanceof Movie) {
                    return new MovieResource($this->reviewable);
                }
                if ($this->reviewable instanceof TvShow) {
                    return new TvShowResource($this->reviewable);
                }
                return $this->reviewable;
            })   
        ];
    }
}
