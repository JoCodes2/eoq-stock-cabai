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
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_stok_masuk');
            $table->foreignUuid('jenis_id')->constrained('jenis_cabai')->cascadeOnDelete();
            $table->decimal('jumlah');
            $table->decimal('harga_beli_satuan');
            $table->string('nama_supplier');
            $table->decimal('total_harga');
            $table->string('no_invoice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuk');
    }
};
