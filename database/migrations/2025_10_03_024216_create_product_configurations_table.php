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
        Schema::create('product_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable()->comment('Nombre personalizado para la configuración');
            $table->json('configuration')->comment('Parámetros de configuración del producto');
            $table->decimal('price', 10, 2)->comment('Precio calculado para esta configuración');
            $table->json('material_breakdown')->nullable()->comment('Desglose detallado de materiales');
            $table->string('session_id')->nullable()->comment('ID de sesión para usuarios no autenticados');
            $table->boolean('is_saved')->default(false)->comment('Si la configuración fue guardada por el usuario');
            $table->boolean('is_quoted')->default(false)->comment('Si se solicitó cotización');
            $table->boolean('is_ordered')->default(false)->comment('Si se realizó pedido');
            $table->text('notes')->nullable()->comment('Notas adicionales del cliente');
            $table->timestamps();
            
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_configurations');
    }
};
