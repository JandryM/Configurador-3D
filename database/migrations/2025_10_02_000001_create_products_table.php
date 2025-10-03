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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2)->nullable(); // Nullable para productos personalizables
            $table->string('category');
            $table->enum('product_type', ['gallery', 'customizable'])->default('gallery');
            $table->json('base_dimensions')->nullable(); // Para productos personalizables
            $table->decimal('base_cost', 10, 2)->nullable(); // Costo base para productos personalizables
            $table->boolean('allows_customization')->default(false);
            $table->string('image')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_gallery_visible')->default(true);
            $table->timestamps();
            
            // Índices para mejorar consultas
            $table->index('product_type');
            $table->index('allows_customization');
            $table->index('category');
            $table->index('is_gallery_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
