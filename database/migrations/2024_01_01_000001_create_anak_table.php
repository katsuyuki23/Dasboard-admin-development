<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anak', function (Blueprint $table) {
            $table->id('id_anak'); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nomor_induk', 50)->unique()->nullable();
            $table->string('nik', 20)->unique()->nullable();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nama', 100);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('status_anak', ['YATIM', 'PIATU', 'YATIM_PIATU']);
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('nama_wali', 100)->nullable();
            $table->string('hubungan_wali', 50)->nullable();
            $table->string('no_hp_wali', 20)->nullable();
            $table->text('alamat_wali')->nullable();
            $table->text('alamat_asal')->nullable();
            $table->text('alasan_masuk')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};
