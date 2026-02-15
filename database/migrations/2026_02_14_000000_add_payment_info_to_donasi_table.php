<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->json('payment_info')->nullable()->after('expired_at');
            // Ensure status_pembayaran is long enough for various gateway statuses
            $table->string('status_pembayaran', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropColumn('payment_info');
            $table->string('status_pembayaran', 255)->change(); // Revert or keep as is
        });
    }
};
