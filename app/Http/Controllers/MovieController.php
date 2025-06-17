<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

#[Group('Movies')]
class MovieController extends Controller
{
    /**
     * Display a listing of all movies.
     */
    public function index(): AnonymousResourceCollection
    {
        $movies = Movie::all();
        return MovieResource::collection($movies);
    }

    /**
     * Store a newly created movie in storage.
     */
    public function store(StoreMovieRequest $request): MovieResource
    {
        $movie = Movie::create($request->validated());

        return new MovieResource($movie);
    }

    /**
     * Display the specified movie.
     */
    public function show(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }

    /**
     * Update the specified movie in storage.
     */
    public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
    {
        $movie->update($request->validated());

        return new MovieResource($movie);
    }

    /**
     * Remove the specified movie from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([
            'message' => 'Movie deleted successfully'
        ], 204);
    }
}
