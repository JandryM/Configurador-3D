<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Material;
use App\Models\Color;

class CategoryMaterialColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $windowsCategory = Category::where('name', 'Windows')->first();
        $doorsCategory = Category::where('name', 'Doors')->first();
        $glassCategory = Category::where('name', 'Glass Panels')->first();
        $slidingCategory = Category::where('name', 'Sliding Systems')->first();
        $securityCategory = Category::where('name', 'Security')->first();

        // Get materials by current category field
        $aluminumMaterials = Material::where('category', 'perfil_aluminio')->get();
        $glassMaterials = Material::where('category', 'vidrio')->get();
        $hardwareMaterials = Material::where('category', 'herraje')->get();
        $sealingMaterials = Material::where('category', 'sellado')->get();

        // Get colors
        $aluminumColors = Color::whereIn('color_name', ['Natural', 'White', 'Black Anodized', 'Champagne', 'Bronze'])->get();
        $glassColors = Color::whereIn('color_name', ['Clear Glass', 'Bronze Glass', 'Gray Glass', 'Reflective Glass'])->get();

        // Assign aluminum materials to all categories (can be used in windows, doors, etc.)
        $allCategories = [$windowsCategory, $doorsCategory, $glassCategory, $slidingCategory, $securityCategory];
        
        foreach ($allCategories as $category) {
            if ($category) {
                // Attach aluminum materials
                foreach ($aluminumMaterials as $material) {
                    $category->materials()->attach($material->id);
                }
                
                // Attach hardware and sealing materials
                foreach ($hardwareMaterials as $material) {
                    $category->materials()->attach($material->id);
                }
                
                foreach ($sealingMaterials as $material) {
                    $category->materials()->attach($material->id);
                }
                
                // Attach aluminum colors
                foreach ($aluminumColors as $color) {
                    $category->colors()->attach($color->id);
                }
            }
        }

        // Glass materials and colors for specific categories
        $glassCategories = [$windowsCategory, $doorsCategory, $glassCategory, $slidingCategory];
        foreach ($glassCategories as $category) {
            if ($category) {
                foreach ($glassMaterials as $material) {
                    $category->materials()->attach($material->id);
                }
                
                foreach ($glassColors as $color) {
                    $category->colors()->attach($color->id);
                }
            }
        }
    }
}
