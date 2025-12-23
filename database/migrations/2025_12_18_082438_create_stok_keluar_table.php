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
        Schema::create('stok_keluar', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('master_data_id')
                ->constrained('master_data')
                ->cascadeOnDelete();

            $table->foreignUuid('permintaan_id')
                ->constrained('permintaan')
                ->cascadeOnDelete();

            $table->decimal('jumlah', 10, 2);
            $table->string('keterangan')->nullable();
            $table->timestamp('dikeluarkan_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_keluar');
    }
};
