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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->comment('Categoría del material');
            $table->text('description')->nullable();
            $table->string('unit_measure');
            $table->decimal('unit_price', 10, 6);
            
            // Campos para manejo por piezas
            $table->decimal('piece_size', 10, 3)->comment('Tamaño de la pieza completa (ej: 6.4 metros)');
            $table->decimal('piece_price', 10, 6)->comment('Precio de la pieza completa');
            $table->boolean('is_by_piece')->default(true)->comment('Si se maneja por piezas completas o por unidad');
            $table->boolean('supports_colors')->default(false)->comment('Si el material cambia a diferentes colores');
            
            // Campos para dimensiones
            $table->boolean('has_dimensions')->default(false)->comment('Si el material se maneja por dimensiones (ancho x alto)');
            $table->decimal('width', 8, 3)->nullable()->comment('Ancho en metros');
            $table->decimal('height', 8, 3)->nullable()->comment('Alto en metros');
            $table->decimal('calculated_area', 10, 6)->nullable()->comment('Área calculada automáticamente');
            
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index('is_by_piece');
            $table->index('has_dimensions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
