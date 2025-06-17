<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWatchlistItemRequest;
use App\Http\Requests\UpdateWatchlistItemRequest;
use App\Http\Resources\WatchlistItemResource;
use App\Models\WatchlistItem;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\returnArgument;

#[Group('Watchlist Items')]
class WatchlistItemController extends Controller
{
    /**
     * Display a listing of all watchlist items.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $watchlistItems = $user->watchlistItems()->with('watchable')->get();

        return WatchlistItemResource::collection($watchlistItems);
    }

    /**
     * Store a newly created watchlist item in storage.
     */
    public function store(StoreWatchlistItemRequest $request): WatchlistItemResource
    {
        $validatedData = $request->validated();

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

        return new WatchlistItemResource($watchlistItem);
    }

    /**
     * Display the specified resource.
     */
    /* public function show(WatchlistItem $watchlistItem)
    {
        //
    } */

    /**
     * Update the specified watchlist item in storage.
     */
    public function update(UpdateWatchlistItemRequest $request, WatchlistItem $watchlistItem): WatchlistItemResource
    {
        $validated = $request->validated();

        if (isset($validated['is_watched']) && $validated['is_watched'] === true) {
            if (! $watchlistItem->is_watched) {
                $watchlistItem->is_watched = true;
                $watchlistItem->watched_at = now();
            }
        } elseif (isset($validated['is_watched']) && $validated['is_watched'] === false) {
            if ($watchlistItem->is_watched) {
                $watchlistItem->is_watched = false;
                $watchlistItem->watched_at = null;
            }
        }

        $watchlistItem->save();

        return new WatchlistItemResource($watchlistItem);
    }

    /**
     * Remove the specified watchlist item from storage.
     */
    public function destroy(UpdateWatchlistItemRequest $request, WatchlistItem $watchlistItem)
    {
        $watchlistItem->delete();

        return response()->json([
            'message' => 'Item removed from watchlist successfully'
        ], 204);
    }

    /**
     * Display a listing of user watchlist items
     * @param \Illuminate\Http\Request $request
     * @return AnonymousResourceCollection
     */
    public function getWatchedHistory(Request $request): AnonymousResourceCollection {
        $user = $request->user();

        $watchedItems = $user->watchlistItems()
            ->where('is_watched', true)
            ->orderBy('watched_at', 'desc')
            ->with('watchable')
            ->get();

        return WatchlistItemResource::collection($watchedItems);
    }
}
