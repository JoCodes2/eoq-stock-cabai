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

            $table->string('nomor_permintaan')->unique();

            $table->enum('status', [
                'menunggu',
                'diproses',
                'dikirim',
                'selesai',
                'ditolak'
            ])->default('menunggu');

            $table->text('catatan')->nullable();
            $table->decimal('total_harga_nota', 14, 2)->default(0);

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
