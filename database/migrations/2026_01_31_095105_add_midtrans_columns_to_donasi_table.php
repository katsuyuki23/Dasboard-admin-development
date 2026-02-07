<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('jumlah');
            $table->enum('status_pembayaran', ['pending', 'success', 'failed', 'expired'])
                  ->default('pending')
                  ->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'status_pembayaran']);
        });
    }
};
