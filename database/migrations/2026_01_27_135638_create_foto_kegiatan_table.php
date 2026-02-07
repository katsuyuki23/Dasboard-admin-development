<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_kegiatan', function (Blueprint $table) {
            $table->id('id_foto');
            $table->unsignedBigInteger('id_anak')->nullable(); // Nullable karena bisa foto kegiatan umum
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('path_foto');
            $table->date('tanggal_kegiatan');
            $table->timestamps();

            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_kegiatan');
    }
};
