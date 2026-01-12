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
        Schema::create('proforma_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proforma_id')->constrained('proformas')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('configuration')->comment('Parámetros de configuración del producto');
            $table->integer('quantity')->default(1)->comment('Cantidad de unidades del producto');
            $table->decimal('price', 10, 2)->comment('Precio calculado para esta configuración');
            $table->boolean('is_active')->default(true)->comment('Indica si el ítem está activo');
            $table->text('notes')->nullable()->comment('Notas adicionales del cliente');
            $table->timestamps();
            $table->index(['proforma_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_items');
    }
};
