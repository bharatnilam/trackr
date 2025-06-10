<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $reviews = $user->reviews()->with('rateable')->get();

        return response()->json([
            'message' => 'Reviews retrieved successfully',
            'reviews' => $reviews
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reviewable_id' => 'required|integer',
                'reviewable_type' => 'required|string|in:Movie,TvShow,Season',
                'body' => 'required|string|max:1000'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $user = $request->user();
        $modelType = 'App\\Models\\' . $validatedData['reviewable_type'];

        try {
            $reviewable = $modelType::findOrFail($validatedData['reviewable_id']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Rateable item not found'
            ], 404);
        }

        $existingReview = Review::where('user_id', $user->id)
            ->where('reviewable_id', $validatedData['reviewable_id'])
            ->where('reviewable_type', $modelType)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this item. Use PUT to update your review.'
            ], 409);
        }

        $review = $user->reviews()->create([
            'reviewable_id' => $validatedData['reviewable_id'],
            'reviewable_type' => $modelType,
            'body' => $validatedData['review']
        ]);

        $review->load('reviewable');

        return response()->json([
            'message' => 'Review added successfully',
            'review' => $review
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        if ($request->user()->id !== $review->user_id) {
            return response()->json([
                'message' => 'Unauthorized to update this review'
            ], 403);
        }

        try {
            $validatedData = $request->validate([
                'body' => 'required|string|max:1000'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $review->update($validatedData);

        $review->load('reviewable');

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Review $review)
    {
        if ($request->user()->id !== $review->user_id) {
            return response()->json([
                'message' => 'Unauthorized to delete this review'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ], 204);
    }
}
