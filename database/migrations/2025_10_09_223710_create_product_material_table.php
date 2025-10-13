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
            $table->decimal('quantity', 10, 3)->default(1)->comment('Cantidad de material necesaria');
            $table->decimal('used_quantity', 10, 3)->comment('Cantidad real usada del material');
            $table->decimal('waste_percentage', 5, 2)->default(0)->comment('Porcentaje de desperdicio');
            $table->text('calculation_formula')->nullable()->comment('Fórmula para calcular la cantidad usada');
            $table->decimal('calculated_cost', 10, 2)->nullable()->comment('Costo calculado para este producto');
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
