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
        // Get aluminum colors (for aluminum profiles)
        $aluminumColors = Color::whereIn('color_name', [
            'Natural', 'White', 'Black Anodized', 'Champagne', 'Bronze'
        ])->get();

        // Get glass colors (for glass materials)
        $glassColors = Color::whereIn('color_name', [
            'Clear Glass', 'Bronze Glass', 'Gray Glass', 'Reflective Glass'
        ])->get();

        // Get materials by category
        $aluminumMaterials = Material::where('category', 'perfil_aluminio')->get();
        $glassMaterials = Material::where('category', 'vidrio')->get();

        // Assign aluminum colors to aluminum materials
        foreach ($aluminumMaterials as $material) {
            foreach ($aluminumColors as $color) {
                DB::table('material_color')->insert([
                    'color_id' => $color->id,
                    'material_id' => $material->id,
                    'category' => 'aluminum_profile',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Assign glass colors to glass materials
        foreach ($glassMaterials as $material) {
            foreach ($glassColors as $color) {
                DB::table('material_color')->insert([
                    'color_id' => $color->id,
                    'material_id' => $material->id,
                    'category' => 'glass',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Other materials (herraje, sellado) typically don't have color variations
        // but we can assign neutral colors if needed
        $otherMaterials = Material::whereIn('category', ['herraje', 'sellado'])->get();
        $neutralColor = Color::where('color_name', 'Natural')->first();

        foreach ($otherMaterials as $material) {
            if ($neutralColor) {
                DB::table('material_color')->insert([
                    'color_id' => $neutralColor->id,
                    'material_id' => $material->id,
                    'category' => $material->category,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
