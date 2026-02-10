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

Route::get('/test-db', function() {
    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $count = \Illuminate\Support\Facades\DB::table('transaksi_kas')->count();
        $sum = \Illuminate\Support\Facades\DB::table('transaksi_kas')->where('jenis_transaksi', 'MASUK')->whereYear('tanggal', 2026)->sum('nominal');
        
        return response()->json([
            'status' => 'DB Connected',
            'database' => $dbName,
            'host' => config('database.connections.mysql.host'),
            'transaksi_count' => $count,
            'sum_2026' => $sum,
            'server_time' => now()->toDateTimeString(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'DB Error',
            'error' => $e->getMessage(), 
            'config' => config('database.connections.mysql.host') // Show host to verify
        ], 500);
    }
});
