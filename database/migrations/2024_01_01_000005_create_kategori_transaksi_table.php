<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_transaksi', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 50);
            // created_at/updated_at not in schema but good practice? Schema implies NO timestamps for this one based on `DEFAULT CHARSET` line... wait, schema says create table ... ENGINE=InnoDB...
            // Checking provided schema:
            // CREATE TABLE kategori_transaksi ( id_kategori ... nama_kategori ... ) ENGINE ...
            // NO timestamps in provided schema for kategori_transaksi.
            // But I will add them or strictly follow?
            // "Clean, maintainable...". Eloquent defaults to timestamps.
            // Provided schema does NOT have timestamps for kategori_transaksi.
            // Provided schema DOES have timestamps for others.
            // I will strictly follow provided schema, so NO timestamps for kategori_transaksi.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_transaksi');
    }
};
