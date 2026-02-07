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
        Schema::dropIfExists('penguruses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu recreate karena tabel ini tidak digunakan
        // Tabel 'penguruses' adalah hasil dari migration yang salah
        // Model Pengurus menggunakan tabel 'pengurus' (bukan 'penguruses')
    }
};
