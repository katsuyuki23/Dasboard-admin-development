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
        Schema::create('growth_monitoring', function (Blueprint $table) {
            $table->id('id_monitoring');
            $table->foreignId('id_anak')->constrained('anak', 'id_anak')->onDelete('cascade');
            $table->date('tanggal_ukur');
            $table->integer('usia_bulan');
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->decimal('lingkar_kepala', 5, 2);
            $table->decimal('z_score_berat', 5, 2)->nullable();
            $table->decimal('z_score_tinggi', 5, 2)->nullable();
            $table->enum('status_gizi', ['NORMAL', 'KURANG_GIZI', 'GIZI_BURUK', 'GIZI_LEBIH', 'OBESITAS', 'STUNTING', 'RESIKO_STUNTING', 'TINGGI'])->default('NORMAL');
            $table->text('rekomendasi_ai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_monitoring');
    }
};
