<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $ratings = $user->ratings()->with('rateable')->get();

        return RatingResource::collection($ratings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request): RatingResource
    {
        $validatedData = $request->validated();

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

        return new RatingResource($rating);
    }

    /**
     * Display the specified resource.
     */
    /* public function show(Rating $rating)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating): RatingResource
    {
        $validatedData = $request->validated();

        $rating->update($validatedData);

        $rating->load('rateable');

        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateRatingRequest $request, Rating $rating)
    {
        $rating->delete();

        return response()->json([
            'message' => 'Rating deleted successfully'
        ], 204);
    }
}
