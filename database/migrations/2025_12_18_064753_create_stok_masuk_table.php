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

            $table->foreignUuid('master_data_id')
                ->constrained('master_data')
                ->cascadeOnDelete();

            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_beli_satuan', 12, 2);
            $table->string('nama_supplier');
            $table->decimal('total_harga', 14, 2);
            $table->string('no_invoice')->nullable();
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
