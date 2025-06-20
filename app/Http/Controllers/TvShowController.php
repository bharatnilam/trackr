<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTvShowRequest;
use App\Http\Requests\UpdateTvShowRequest;
use App\Http\Resources\TvShowResource;
use App\Models\TvShow;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

#[Group('TV Shows')]
class TvShowController extends Controller
{
    /**
     * Display a listing of all TV shows.
     */
    public function index(): AnonymousResourceCollection
    {
        $tvShows = TvShow::all();
        return TvShowResource::collection($tvShows);
    }

    /**
     * Store a newly created TV show in storage.
     */
    public function store(StoreTvShowRequest $request): TvShowResource
    {
        $tvShow = TvShow::create($request->validated());

        return new TvShowResource($tvShow);
    }

    /**
     * Display the specified TV show.
     */
    public function show(TvShow $tvShow): TvShowResource
    {
        return new TvShowResource($tvShow);
    }

    /**
     * Update the specified TV show in storage.
     */
    public function update(UpdateTvShowRequest $request, TvShow $tvShow): TvShowResource
    {
        $tvShow->update($request->validated());

        return new TvShowResource($tvShow);
    }

    /**
     * Remove the specified TV show from storage.
     */
    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();
        
        return response()->json([
            'message' => 'TV Show deleted successfully'
        ], 204);
    }
}
