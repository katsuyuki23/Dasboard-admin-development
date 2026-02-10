<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->string('order_id')->unique()->nullable()->after('id_donasi');
            $table->text('payment_url')->nullable()->after('status_pembayaran');
            $table->string('payment_method')->nullable()->after('payment_url');
            $table->string('payment_channel')->nullable()->after('payment_method');
            $table->string('va_number')->nullable()->after('payment_channel');
            $table->text('qr_string')->nullable()->after('va_number');
            $table->timestamp('expired_at')->nullable()->after('qr_string');
        });
    }

    public function down(): void
    {
        Schema::table('donasi', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'payment_url',
                'payment_method',
                'payment_channel',
                'va_number',
                'qr_string',
                'expired_at'
            ]);
        });
    }
};
