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
        Schema::table('proforma_items', function (Blueprint $table) {
            // Snapshot de costos calculados en el momento de crear la proforma
            $table->decimal('material_cost', 10, 2)->nullable()->after('price')
                ->comment('Costo de materiales calculado (snapshot)');
            
            $table->decimal('direct_cost', 10, 2)->nullable()->after('material_cost')
                ->comment('Costo directo calculado (snapshot)');
            
            $table->decimal('indirect_cost', 10, 2)->nullable()->after('direct_cost')
                ->comment('Costo indirecto calculado (snapshot)');
            
            $table->decimal('waste_cost', 10, 2)->nullable()->after('indirect_cost')
                ->comment('Costo de desperdicio calculado (snapshot)');
            
            $table->decimal('profit_amount', 10, 2)->nullable()->after('waste_cost')
                ->comment('Monto de ganancia calculado (snapshot)');
            
            $table->decimal('total_cost', 10, 2)->nullable()->after('profit_amount')
                ->comment('Costo total (material + directo + indirecto + desperdicio)');
            
            $table->decimal('profit_margin_percentage', 8, 2)->nullable()->after('total_cost')
                ->comment('Porcentaje de margen de ganancia usado (snapshot)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proforma_items', function (Blueprint $table) {
            $table->dropColumn([
                'material_cost',
                'direct_cost',
                'indirect_cost',
                'waste_cost',
                'profit_amount',
                'total_cost',
                'profit_margin_percentage',
            ]);
        });
    }
};
