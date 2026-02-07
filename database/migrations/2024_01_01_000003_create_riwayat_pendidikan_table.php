<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id('id_pendidikan');
            $table->unsignedBigInteger('id_anak');
            $table->string('jenjang', 50)->nullable();
            $table->string('nama_sekolah', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_anak')->references('id_anak')->on('anak')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikan');
    }
};
