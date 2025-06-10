<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\returnArgument;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $ratings = $user->ratings()->with('rateable')->get();

        return response()->json([
            'message' => 'Ratings retrieved successfully',
            'ratings' => $ratings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'rateable_id' => 'required|integer',
                'rateable_type' => 'required|string|in:Movie,TvShow,Season',
                'rating' => 'required|integer|min:1|max:10'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $user = $request->user();
        $modelType = 'App\\Models\\' . $validatedData['rateable_type'];

        try {
            $rateable = $modelType::findOrFail($validatedData['rateable_id']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Rateable item not found'
            ], 404);
        }

        $existingRating = Rating::where('user_id', $user->id)
            ->where('rateable_id', $validatedData['rateable_id'])
            ->where('rateable_type', $modelType)
            ->first();

        if ($existingRating) {
            return response()->json([
                'message' => 'You have already rated this item. Use PUT to update your rating.'
            ], 409);
        }

        $rating = $user->ratings()->create([
            'rateable_id' => $validatedData['rateable_id'],
            'rateable_type' => $modelType,
            'rating' => $validatedData['rating']
        ]);

        $rating->load('rateable');

        return response()->json([
            'message' => 'Rating added successfully',
            'rating' => $rating
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        if ($request->user()->id !== $rating->user_id) {
            return response()->json([
                'message' => 'Unauthorized to update this rating'
            ], 403);
        }

        try {
            $validatedData = $request->validate([
                'rating' => 'required|integer|min:1|max:10'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $rating->update($validatedData);

        $rating->load('rateable');

        return response()->json([
            'message' => 'Rating updated successfully',
            'rating' => $rating
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Rating $rating)
    {
        if ($request->user()->id !== $rating->user_id) {
            return response()->json([
                'message' => 'Unauthorized to delete this rating'
            ], 403);
        }

        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted successfully'
        ], 204);
    }
}
