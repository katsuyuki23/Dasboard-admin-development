<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasi', function (Blueprint $table) {
            $table->id('id_donasi');
            $table->enum('type_donasi', ['DONATUR_TETAP', 'NON_DONATUR']);
            $table->unsignedBigInteger('id_donatur')->nullable();
            $table->enum('sumber_non_donatur', ['NON_DONATUR', 'PROGRAM_UEP', 'BANTUAN', 'KOTAK_AMAL'])->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_catat');
            $table->timestamps();

            $table->foreign('id_donatur')->references('id_donatur')->on('donatur')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
