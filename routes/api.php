<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LandingController;
use App\Http\Controllers\Api\DokuController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes for Landing Page
Route::prefix('landing')->group(function () {
    Route::get('/stats', [LandingController::class, 'getStats']);
    Route::get('/donations', [LandingController::class, 'getRecentDonations']);
    Route::get('/gallery', [LandingController::class, 'getGallery']);
    Route::post('/donate', [LandingController::class, 'storeDonation']);
    
    // DOKU Payment Routes
    Route::prefix('payment')->group(function () {
        Route::post('/create', [DokuController::class, 'createPayment']);
        Route::post('/callback', [DokuController::class, 'handleCallback']);
        Route::get('/status/{orderId}', [DokuController::class, 'checkStatus']);
    });
});
