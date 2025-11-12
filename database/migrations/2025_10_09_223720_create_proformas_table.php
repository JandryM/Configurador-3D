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
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->json('configuration')->comment('Parámetros de configuración del producto');
            $table->integer('quantity')->default(1)->comment('Cantidad de unidades del producto');
            $table->decimal('price', 10, 2)->comment('Precio calculado para esta configuración');
            $table->boolean('is_ordered')->default(false)->comment('Si se realizó pedido');
            $table->text('notes')->nullable()->comment('Notas adicionales del cliente');
            $table->date('expiration_date')->nullable()->comment('Fecha de expiración de la proforma');
            $table->boolean('is_expired')->default(false)->comment('Indica si la proforma ha expirado');
            $table->timestamps();
            
            $table->index(['user_id', 'product_id']);
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
