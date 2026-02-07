<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_anak', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->unsignedBigInteger('id_anak');
            $table->enum('jenis_dokumen', ['KTP', 'KK', 'AKTA_LAHIR', 'LAINNYA']);
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_anak');
    }
};
