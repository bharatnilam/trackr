<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $reviews = $user->reviews()->with('rateable')->get();

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request): ReviewResource
    {
        $validatedData = $request->validated();

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
            'body' => $validatedData['body']
        ]);

        $review->load('reviewable');

        return new ReviewResource($review);
    }

    /**
     * Display the specified resource.
     */
    /* public function show(Review $review)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review): ReviewResource
    {
        $validatedData = $request->validated();

        $review->update($validatedData);

        $review->load('reviewable');

        return new ReviewResource($review);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateReviewRequest $request, Review $review)
    {
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ], 204);
    }
}
