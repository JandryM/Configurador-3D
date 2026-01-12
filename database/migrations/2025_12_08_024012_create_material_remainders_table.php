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
        Schema::create('material_remainders', function (Blueprint $table) {
            $table->id();
            
            // Relación con material
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            
            // Longitud/cantidad disponible del retazo
            $table->decimal('remaining_length', 10, 3);
            
            // De qué operación/movimiento se generó este retazo
            $table->foreignId('material_movement_id')->nullable()->constrained('material_movements')->onDelete('set null');
            
            // Estado del retazo
            $table->enum('status', ['available', 'reserved', 'used'])->default('available');
            
            // Notas adicionales
            $table->text('notes')->nullable();
            
            // Fecha en que se usó (cuando status = 'used')
            $table->timestamp('used_at')->nullable();
            
            $table->timestamps();
            
            // Índices para consultas frecuentes
            $table->index(['material_id', 'status']);
            $table->index('remaining_length');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_remainders');
    }
};
