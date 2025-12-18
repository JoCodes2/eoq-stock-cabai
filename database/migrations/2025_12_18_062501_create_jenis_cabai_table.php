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
        Schema::create('jenis_cabai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode');
            $table->string('nama');
            $table->string('satuan')->default('kg');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_cabai');
    }
};
