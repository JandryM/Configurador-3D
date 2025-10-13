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
        Schema::table('products', function (Blueprint $table) {
            $table->string('model_3d_file')->nullable()->comment('Ruta al archivo del modelo 3D (.glb, .gltf, .obj)');
            $table->json('model_3d_textures')->nullable()->comment('Array de texturas asociadas al modelo 3D');
            $table->json('model_3d_materials')->nullable()->comment('Materiales específicos del modelo 3D');
            $table->json('model_3d_settings')->nullable()->comment('Configuraciones del visor 3D (cámara, iluminación, etc.)');
            $table->boolean('has_3d_model')->default(false)->comment('Indica si el producto tiene modelo 3D');
            $table->decimal('model_scale', 8, 4)->default(1.0000)->comment('Escala del modelo 3D');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'model_3d_file',
                'model_3d_textures',
                'model_3d_materials',
                'model_3d_settings',
                'has_3d_model',
                'model_scale'
            ]);
        });
    }
};
