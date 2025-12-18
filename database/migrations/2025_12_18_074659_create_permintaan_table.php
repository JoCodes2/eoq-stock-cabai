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
        Schema::create('permintaan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('pengguna_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignUuid('jenis_cabai_id')
                ->constrained('jenis_cabai')
                ->restrictOnDelete();

            $table->string('nomor_permintaan')->unique();

            $table->enum('status', [
                'menunggu',
                'diproses',
                'dikirim',
                'selesai',
                'ditolak'
            ])->default('menunggu');

            $table->text('catatan')->nullable();

            $table->timestamp('diproses_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan');
    }
};
