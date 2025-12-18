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

            // Parameter EOQ
            $table->decimal('permintaan_tahunan', 10, 2);
            $table->decimal('biaya_pemesanan', 12, 2);
            $table->decimal('biaya_penyimpanan', 12, 2);

            // Parameter pendukung
            $table->integer('waktu_tunggu_hari')->default(1);
            $table->decimal('stok_aman', 10, 2)->default(0);

            // Hasil perhitungan EOQ
            $table->decimal('nilai_eoq', 10, 2);
            $table->decimal('titik_pemesanan_ulang', 10, 2);

            // Audit perhitungan
            $table->date('terakhir_dihitung')->nullable();

            // Opsional (nilai plus)
            $table->boolean('aktif')->default(true);
            $table->text('catatan')->nullable();

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
