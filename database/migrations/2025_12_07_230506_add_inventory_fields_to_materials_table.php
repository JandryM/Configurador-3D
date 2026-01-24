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
        Schema::table('materials', function (Blueprint $table) {
            // Cantidad total de piezas o unidades en stock
            $table->unsignedInteger('stock_quantity')->default(0)->after('is_by_piece');
            
            // Nivel mínimo de stock para alertas
            $table->decimal('min_stock_alert', 10, 3)->default(0)->after('stock_quantity');
            
            // Fechas de control
            $table->timestamp('last_used_date')->nullable()->after('min_stock_alert');

            // Control de estado activo/inactivo
            $table->boolean('is_active')->default(true)->after('last_used_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn([
                'stock_quantity',
                'min_stock_alert',
                'last_used_date',
                'is_active'
            ]);
        });
    }
};
