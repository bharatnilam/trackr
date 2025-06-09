<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return response()->json([
            'message' => 'Movies retrieved successfully',
            'movies' => $movies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'release_year' => 'required|integer|min:1800|max:' . (date('Y') + 5),
                'pg_rating' => 'nullable|string|max:20',
                'runtime' => 'nullable|integer|min:1',
                'director' => 'nullable|string|max"255',
                'genre' => 'nullable|string|max:255',
                'actors' => 'nullable|string',
                'synopsis' => 'nullable|string',
                'poster_image_url' => 'nullable|url|max:2048',
                'external_id' => 'nullable|string|max:255|unique:movies',
                'available_platforms' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $movie = Movie::create($validatedData);

        return response()->json([
            'message' => 'Movie created successfully',
            'movie' => $movie
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        return response()->json([
            'message' => 'Movie retrieved successfully',
            'movie' => $movie
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'release_year' => 'sometimes|required|integer|min:1800|max:' . (date('Y') + 5),
                'pg_rating' => 'nullable|string|max:20',
                'runtime' => 'nullable|integer|min:1',
                'director' => 'nullable|string|max"255',
                'genre' => 'nullable|string|max:255',
                'actors' => 'nullable|string',
                'synopsis' => 'nullable|string',
                'poster_image_url' => 'nullable|url|max:2048',
                'external_id' => 'nullable|string|max:255|unique:movies',
                'available_platforms' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'=> 'Validation error',
                'errors'=> $e->errors()
            ], 422);
        }

        $movie->update($validatedData);

        return response()->json([
            'message' => 'Movie updated successfully',
            'movie' => $movie
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([
            'message' => 'Movie deleted successfully'
        ], 204);
    }
}
