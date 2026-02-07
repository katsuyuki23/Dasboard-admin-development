<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id('id_pengurus');
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->date('mulai_bekerja');
            $table->string('jabatan', 50);
            $table->string('status_kepegawaian', 50); // Tetap, Kontrak, Volunteer, dll
            $table->string('pendidikan_terakhir', 50);
            $table->text('pelatihan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengurus');
    }
};
