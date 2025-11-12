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
        Schema::create('global_cost_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('indirect_cost_percentage', 5, 2)->default(0.00);
            $table->date('valid_from')->nullable()->comment('Fecha desde la cual es válido este porcentaje');
            $table->date('valid_until')->nullable()->comment('Fecha hasta la cual es válido este porcentaje');
            $table->boolean('is_locked')->default(false)->comment('Si está bloqueado no se puede modificar');
            $table->integer('edit_attempts')->default(0)->comment('Número de ediciones realizadas (máx 2 en 1 hora)');
            $table->timestamps();
        });

        Schema::create('product_cost_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true)->comment('Indica si esta configuración está activa o no');
            $table->decimal('direct_cost_percentage', 5, 2)->default(0.00);
            $table->decimal('waste_percentage', 5, 2)->default(0.00)->comment('Porcentaje adicional de desperdicio del producto');
            $table->decimal('profit_margin_percentage', 5, 2)->default(0.00)->comment('Porcentaje de margen de ganancia sobre el costo total');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_cost_settings');
        Schema::dropIfExists('global_cost_settings');
    }
};
