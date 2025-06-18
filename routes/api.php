<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\WatchlistItemController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('movies',MovieController::class);

    Route::apiResource('tv-shows', TvShowController::class);

    Route::get('/watchlist', [WatchlistItemController::class,'index']);
    Route::post('/watchlist', [WatchlistItemController::class,'store']);
    Route::delete('/watchlist/{watchlistItem}', [WatchlistItemController::class,'destroy']);
    Route::get('watched-history', [WatchlistItemController::class, 'getWatchedHistory']);

    Route::apiResource('ratings', RatingController::class)->except(['show']);
    Route::apiResource('reviews', ReviewController::class)->except(['show']);
});

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
