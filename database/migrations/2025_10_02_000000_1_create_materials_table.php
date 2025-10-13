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
            $table->string('category')->nullable()->comment('Categoría del material (Aluminio y Vidrio, Melamina, etc.)');
            $table->text('description')->nullable();
            $table->string('unit_measure');
            $table->decimal('unit_price', 10, 2);
            
            // Campos para manejo por piezas
            $table->decimal('piece_size', 10, 3)->comment('Tamaño de la pieza completa (ej: 6.4 metros)');
            $table->decimal('piece_price', 10, 2)->comment('Precio de la pieza completa');
            $table->boolean('is_by_piece')->default(true)->comment('Si se maneja por piezas completas o por unidad');
            
            // Campos para dimensiones
            $table->boolean('has_dimensions')->default(false)->comment('Si el material se maneja por dimensiones (ancho x alto)');
            $table->decimal('width', 8, 3)->nullable()->comment('Ancho en metros');
            $table->decimal('height', 8, 3)->nullable()->comment('Alto en metros');
            $table->decimal('calculated_area', 10, 6)->nullable()->comment('Área calculada automáticamente');
            
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index('category');
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
