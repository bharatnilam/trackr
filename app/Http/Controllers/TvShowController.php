<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TvShowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tvShows = TvShow::all();
        return response()->json([
            'message' => 'TV Shows retrieved successfully',
            'tv_shows' => $tvShows
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
                'director' => 'nullable|string|max:255',
                'genre' => 'nullable|string|max:255',
                'actors' => 'nullable|string',
                'synopsis' => 'nullable|string',
                'number_of_seasons' => 'nullable|integer|min:1',
                'number_of_episodes' => 'nullable|integer|min:1',
                'status' => 'nullable|string|max:255',
                'poster_image_url' => 'nullable|url|max:2048',
                'external_id' => 'nullable|string|max:255|unique:tv_shows',
                'available_platforms' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $tvShow = TvShow::create($validatedData);

        return response()->json([
            'message'=> 'TV Show created successfully',
            'tv_show' => $tvShow
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TvShow $tvShow)
    {
        return response()->json([
            'message' => 'TV Show retrieved successfully',
            'tv_show' => $tvShow
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TvShow $tvShow)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'release_year' => 'sometimes|required|integer|min:1800|max:' . (date('Y') + 5),
                'pg_rating' => 'nullable|string|max:20',
                'runtime' => 'nullable|integer|min:1',
                'director' => 'nullable|string|max:255',
                'genre' => 'nullable|string|max:255',
                'actors' => 'nullable|string',
                'synopsis' => 'nullable|string',
                'number_of_seasons' => 'nullable|integer|min:1',
                'number_of_episodes' => 'nullable|integer|min:1',
                'status' => 'nullable|string|max:255',
                'poster_image_url' => 'nullable|url|max:2048',
                'external_id' => 'nullable|string|max:255|unique:tv_shows',
                'available_platforms' => 'nullable|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors'=> $e->errors()
            ], 422);
        }

        $tvShow->update($validatedData);

        return response()->json([
            'message'=> 'TV Show updated successfully',
            'tv_show' => $tvShow
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();
        
        return response()->json([
            'message' => 'TV Show deleted successfully'
        ], 204);
    }
}
