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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->unique()->constrained('proformas')->onDelete('cascade');
            $table->string('number')->unique()->comment('Unique order code');
            $table->enum('status', ['pending', 'approved', 'paid', 'in_production', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_proof')->nullable()->comment('Path to payment receipt image');
            $table->timestamp('product_created_at')->nullable()->comment('Product creation date');
            $table->timestamp('estimated_finish_at')->nullable()->comment('Estimated finish date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
