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
        Schema::create('requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('market_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('product')->cascadeOnDelete();
            $table->integer('quantity');
            $table->date('request_date');
            $table->date('end_time');
            $table->text('description')->nullable();
            $table->enum('status_request', ['open', 'close', 'fulfilled'])->default('open');
            $table->foreignUuid('selected_supplier_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
