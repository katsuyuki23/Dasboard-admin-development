<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donatur_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_donatur');
            $table->string('whatsapp_number', 20)->unique();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('id_donatur')->references('id_donatur')->on('donatur')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donatur_whatsapp');
    }
};
