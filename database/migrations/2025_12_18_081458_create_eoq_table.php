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
        Schema::create('pengaturan_eoq', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('master_data_id')
                ->constrained('master_data')
                ->cascadeOnDelete()
                ->unique();
            $table->decimal('permintaan_tahunan', 20, 2);
            $table->decimal('biaya_pemesanan', 12, 2);
            $table->decimal('biaya_penyimpanan', 12, 2);
            $table->integer('waktu_tunggu_hari')->default(1);
            $table->decimal('stok_aman', 20, 2)->default(0);
            $table->decimal('nilai_eoq', 20, 2)->nullable();
            $table->decimal('titik_pemesanan_ulang', 20, 2)->nullable();

            $table->date('terakhir_dihitung')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoq');
    }
};
