<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\LaporanController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/donasi', [\App\Http\Controllers\PublicDonasiController::class, 'form'])->name('public.donasi.form');
Route::post('/donasi', [\App\Http\Controllers\PublicDonasiController::class, 'store'])->name('public.donasi.store');
Route::get('/donasi/success/{id}', [\App\Http\Controllers\PublicDonasiController::class, 'success'])->name('public.donasi.success');
Route::get('/suara-donatur', [\App\Http\Controllers\PublicDonasiController::class, 'forum'])->name('public.donasi.forum');
Route::post('/suara-donatur', [\App\Http\Controllers\PublicDonasiController::class, 'storePesan'])->name('public.donasi.pesan.store');

// Auth Routes
Route::middleware(['block_admin_port'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');
});

// Protected Routes
// Removing role:ADMIN for safety integration
// Protected Routes
// Removing role:ADMIN for safety integration
Route::middleware(['auth', 'block_admin_port'])->prefix('admin')->group(function () {
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markRead');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modules
    // We will add resource controllers here as we build them
    
    // Anak (Data Anak, Kesehatan, Pendidikan)
    Route::get('/anak/export/excel', [\App\Http\Controllers\AnakController::class, 'exportExcel'])->name('anak.export.excel');
    Route::get('/anak/export/pdf', [\App\Http\Controllers\AnakController::class, 'exportPdf'])->name('anak.export.pdf');
    Route::resource('anak', AnakController::class);
    
    // Growth Monitoring
    Route::get('anak/{anak}/growth/create', [\App\Http\Controllers\GrowthMonitoringController::class, 'create'])->name('growth.create');
    Route::post('anak/{anak}/growth', [\App\Http\Controllers\GrowthMonitoringController::class, 'store'])->name('growth.store');

    // Pengurus Panti
    Route::resource('pengurus', \App\Http\Controllers\PengurusController::class);

    // Dokumen Anak
    Route::post('/anak/{id}/dokumen', [\App\Http\Controllers\AnakController::class, 'uploadDokumen'])->name('anak.dokumen.upload');
    Route::delete('/dokumen/{id}', [\App\Http\Controllers\AnakController::class, 'deleteDokumen'])->name('dokumen.delete');

    // Gallery Foto Kegiatan
    Route::resource('gallery', \App\Http\Controllers\GalleryController::class)->except(['show']);
    
    // Profile & Change Password
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password.update');
    
    // Riwayat (Inline)
    Route::post('/riwayat-kesehatan', [RiwayatController::class, 'storeKesehatan'])->name('riwayat-kesehatan.store');
    Route::delete('/riwayat-kesehatan/{id}', [RiwayatController::class, 'destroyKesehatan'])->name('riwayat-kesehatan.destroy');
    Route::post('/riwayat-pendidikan', [RiwayatController::class, 'storePendidikan'])->name('riwayat-pendidikan.store');
    Route::delete('/riwayat-pendidikan/{id}', [RiwayatController::class, 'destroyPendidikan'])->name('riwayat-pendidikan.destroy');
    
    // Keuangan (Donatur)
    Route::resource('donatur', \App\Http\Controllers\DonaturController::class);
    
    // Donasi
    Route::resource('donasi', DonasiController::class);

    // Transaksi
    Route::get('/pengeluaran', [\App\Http\Controllers\TransaksiKasController::class, 'indexPengeluaran'])->name('pengeluaran.index');
    Route::get('/pengeluaran/create', [\App\Http\Controllers\TransaksiKasController::class, 'createPengeluaran'])->name('pengeluaran.create');
    Route::get('/pengeluaran/{id}/edit', [\App\Http\Controllers\TransaksiKasController::class, 'editPengeluaran'])->name('pengeluaran.edit');
    Route::resource('transaksi', \App\Http\Controllers\TransaksiKasController::class);

    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/export', [\App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
    Route::post('/laporan/rekap', [\App\Http\Controllers\LaporanController::class, 'exportRekap'])->name('laporan.rekap');

    // Activity Logs
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Helper Routes for testing
Route::get('/test-etl', function() {
    \Illuminate\Support\Facades\Artisan::call('duckdb:extract');
    return "ETL Triggered";
});

Route::get('/test-wa', function() {
    try {
        $service = new \App\Services\WhatsAppNotificationService();
        $result = $service->notifyAdmin("🔔 Tes Notifikasi dari Laravel! Sistem berfungsi normal.");
        return $result ? "Berhasil kirim ke Admin!" : "Gagal kirim. Cek Log.";
    } catch (\Throwable $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/test-wa-group', function() {
    try {
        $service = new \App\Services\WhatsAppNotificationService();
        $result = $service->sendToGroup("🔔 Tes Notifikasi ke Grup WhatsApp!\n\nSistem WhatsApp Group Notification sudah aktif! ✅");
        return $result ? "Berhasil kirim ke Grup WhatsApp!" : "Gagal kirim. Cek Log.";
    } catch (\Throwable $e) {
        return "Error: " . $e->getMessage();
    }
});

// Midtrans Callback (Exclude from CSRF in VerifyCsrfToken if needed, or use api routes)
Route::post('/midtrans/callback', [\App\Http\Controllers\PaymentCallbackController::class, 'handle']);

// Tripay Callback
Route::post('/tripay/callback', [\App\Http\Controllers\TripayCallbackController::class, 'handle']);

// WhatsApp Webhook (Exempt from CSRF in VerifyCsrfToken middleware)
Route::post('/webhook/whatsapp', [\App\Http\Controllers\WhatsAppWebhookController::class, 'handle'])->name('webhook.whatsapp');

// Helper to Simulate Tripay Callback (For Localhost Testing)
Route::get('/test-tripay-simulation', function() {
    $donasi = \App\Models\Donasi::where('status_pembayaran', 'pending')->latest('tanggal_catat')->first();
    
    if(!$donasi) return "Tidak ada donasi pending untuk dites.";

    $merchantRef = 'DONASI-' . $donasi->id_donasi . '-' . time();
    $amount = (int) $donasi->jumlah;
    
    $service = new \App\Services\TripayService();
    $signature = $service->createSignature($amount, $merchantRef);

    // Simulate Callback Payload
    $response = \Illuminate\Support\Facades\Http::post(url('/tripay/callback'), [
        'merchant_ref' => $merchantRef,
        'total_amount' => $amount,
        'status' => 'PAID',
        'signature' => $signature
    ]);

    return "Simulasi dikirim untuk Donasi ID: $donasi->id_donasi. <br>Ref: $merchantRef <br>Status: " . $response->status() . " <br>Body: " . $response->body();
});

Route::get('/test-db', function() {
    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $count = \Illuminate\Support\Facades\DB::table('transaksi_kas')->count();
        $sum = \Illuminate\Support\Facades\DB::table('transaksi_kas')->where('jenis_transaksi', 'MASUK')->whereYear('tanggal', 2026)->sum('nominal');
        $kas = \Illuminate\Support\Facades\DB::table('kas')->first();
        
        return response()->json([
            'status' => 'Connected',
            'database' => $dbName,
            'config_host' => config('database.connections.mysql.host'),
            'transaksi_count' => $count,
            'sum_masuk_2026' => $sum,
            'kas_first' => $kas,
            'server_time' => now()->toDateTimeString(),
            'php_version' => phpversion()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
    }
});
