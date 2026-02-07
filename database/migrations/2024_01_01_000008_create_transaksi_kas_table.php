<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_kas', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_kas');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_donasi')->nullable();
            $table->enum('jenis_transaksi', ['MASUK', 'KELUAR']);
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_kas')->references('id_kas')->on('kas');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_transaksi');
            $table->foreign('id_donasi')->references('id_donasi')->on('donasi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_kas');
    }
};
