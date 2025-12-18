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
        Schema::create('item_permintaan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('permintaan_id')
                ->constrained('permintaan')
                ->cascadeOnDelete()
                ->unique();
            $table->string('jenis_cabai', 100)->nullable();
            $table->string('satuan');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('total_harga', 14, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_permintaan');
    }
};
