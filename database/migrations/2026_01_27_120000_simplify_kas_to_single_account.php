<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure all existing transactions point to id_kas = 1
        DB::table('transaksi_kas')->update(['id_kas' => 1]);

        // Make id_kas NOT NULL with default 1
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kas')->default(1)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_kas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kas')->nullable()->change();
        });
    }
};
