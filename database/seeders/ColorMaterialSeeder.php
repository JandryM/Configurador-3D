<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class ColorMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar tabla para evitar duplicados
        DB::table('material_color')->truncate();
        // Get aluminum colors (for aluminum profiles)
        $aluminumColors = Color::whereIn('color_name', [
            'Natural', 'White', 'Black Anodized', 'Woody', 'Bronze'
        ])->get();

        // Get glass colors (for glass materials)
        $glassColors = Color::whereIn('color_name', [
            'Transparent Glass', 'Reflective Blue Sky Glass', 'Reflective Gray Dark Glass',
        ])->get();

        // Obtener los IDs de categoría desde la tabla categories con los nuevos nombres
        $windowsCategoryId = DB::table('categories')->where('name', 'Windows')->value('id');
        $doorsCategoryId = DB::table('categories')->where('name', 'Doors')->value('id');
        $glassPanelsCategoryId = DB::table('categories')->where('name', 'Glass Panels')->value('id');
        $slidingSystemsCategoryId = DB::table('categories')->where('name', 'Sliding Systems')->value('id');
        $securityCategoryId = DB::table('categories')->where('name', 'Security')->value('id');

        // Puedes ajustar la lógica según cómo quieras asignar colores/materiales a cada categoría
        // Ejemplo: asignar colores de aluminio a ventanas y puertas
        $aluminumMaterials = Material::whereIn('category_id', [$windowsCategoryId, $doorsCategoryId])->where('supports_colors', true)->whereRaw('lower(name) not like ?', ['%vidrio%'])->get();
        $glassMaterials = Material::where('supports_colors', true)->whereRaw('lower(name) like ?', ['%vidrio%'])->get();
        $otherMaterials = Material::whereIn('category_id', [$slidingSystemsCategoryId, $securityCategoryId])->get();

        // Asignar colores de aluminio a materiales de aluminio (ventanas y puertas)
        $colorIncreases = [
            'Natural' => 0,
            'White' => 0,
            'Black Anodized' => 4,
            'Woody' => 10,
            'Bronze' => 4
        ];
        foreach ($aluminumMaterials as $material) {
            foreach ($aluminumColors as $color) {
                $increase = $colorIncreases[$color->color_name] ?? 0;
                DB::table('material_color')->insert([
                    'color_id' => $color->id,
                    'material_id' => $material->id,
                    'category_id' => $material->category_id,
                    'increase_value' => $increase,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Asignar colores de vidrio a materiales de vidrio (paneles de vidrio)
        $glassIncreases = [
            'Transparent Glass' => 0,
            'Reflective Blue Sky Glass' => 10,
            'Reflective Gray Dark Glass' => 20
        ];
        foreach ($glassMaterials as $material) {
            foreach ($glassColors as $color) {
                $increase = $glassIncreases[$color->color_name] ?? 0;
                DB::table('material_color')->insert([
                    'color_id' => $color->id,
                    'material_id' => $material->id,
                    'category_id' => $material->category_id,
                    'increase_value' => $increase,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Otros materiales (sliding systems, security) típicamente no tienen variaciones de color
        $neutralColor = Color::where('color_name', 'Natural')->first();
        foreach ($otherMaterials as $material) {
            if ($neutralColor) {
                DB::table('material_color')->insert([
                    'color_id' => $neutralColor->id,
                    'material_id' => $material->id,
                    'category_id' => $material->category_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
