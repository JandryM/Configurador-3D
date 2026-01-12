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
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique()->comment('Código único de la proforma');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('total_price', 12, 2)->nullable()->comment('Precio total de la proforma');
            $table->boolean('is_ordered')->default(false)->comment('Si se realizó pedido');
            $table->date('expiration_date')->nullable()->comment('Fecha de expiración de la proforma');
            $table->boolean('is_expired')->default(false)->comment('Indica si la proforma ha expirado');
            $table->boolean('is_active')->default(true)->comment('Indica si la proforma está activa');
            $table->timestamps();
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('proformas');
    }
};
