<?php

namespace App\Http\Controllers;

use App\Models\WatchlistItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\returnArgument;

class WatchlistItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $watchlistItems = $user->watchlistItems()->with('watchable')->get();

        return response()->json([
            'message' => 'Watchlist items retrieved successfully',
            'watchlist_items' => $watchlistItems
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'watchable_id' => 'required|integer',
                'watchable_type' => 'required|string|in:Movie,TvShow'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        $user = $request->user();
        $modelType = 'App\\Models\\' . $validatedData['watchable_type'];

        try {
            $watchable = $modelType::findOrFail($validatedData['watchable_id']);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Watchable item not found'
            ], 404);
        }

        $existingItem = WatchlistItem::where('user_id', $user->id)
            ->where('watchable_id', $validatedData['watchable_id'])
            ->where('watchable_type', $modelType)
            ->first();

        if ($existingItem) {
            return response()->json([
                'message' => 'Item already in watchlist'
            ], 409);
        }

        $watchlistItem = $user->watchlistItems()->create([
            'watchable_id' => $validatedData['watchable_id'],
            'watchable_type' => $modelType,
            'is_watched' => false
        ]);

        $watchlistItem->load('watchable');

        return response()->json([
            'message' => 'Item added to watchlist successfully!',
            'watchlist_item' => $watchlistItem
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /* public function show(WatchlistItem $watchlistItem)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WatchlistItem $watchlistItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, WatchlistItem $watchlistItem)
    {
        if ($request->user()->id !== $watchlistItem->user_id) {
            return response()->json([
                'message' => 'Unauthorized to delete this watchlist item'
            ], 403);
        }

        $watchlistItem->delete();

        return response()->json([
            'message' => 'Item removed from watchlist successfully'
        ], 204);
    }
}
