<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kesehatan', function (Blueprint $table) {
            $table->id('id_kesehatan');
            $table->unsignedBigInteger('id_anak');
            $table->string('kategori', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kesehatan');
    }
};
