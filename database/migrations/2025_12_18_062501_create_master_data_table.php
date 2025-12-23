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
        Schema::create('master_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('satuan')->default('kg');
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);

            $table->decimal('harga_beli_terakhir', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);

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
