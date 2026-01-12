<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();

            // Relación con material
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');

            // Cantidad del movimiento
            $table->decimal('quantity', 10, 3);

            // Stock antes y después del movimiento (para auditoría) - enteros porque son piezas completas
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');

            // Referencia a la orden relacionada
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');

            // Usuario que realizó el movimiento
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // ...

            // Retazo específico usado en este movimiento (si aplica) - sin foreign key para evitar dependencia circular
            $table->unsignedBigInteger('material_remainder_id')->nullable();

            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index('material_id');
            $table->index('order_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
        Schema::dropIfExists('material_movements');
    }
};
