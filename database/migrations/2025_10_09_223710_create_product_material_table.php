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
        Schema::create('product_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->text('calculation_formula')->nullable()->comment('Fórmula para calcular la cantidad usada');
            $table->text('notes')->nullable()->comment('Notas adicionales sobre el cálculo');
            $table->timestamps();
            
            // Evitar duplicados y mejorar consultas
            $table->unique(['product_id', 'material_id']);
            $table->index('product_id');
            $table->index('material_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_material');
    }
};
