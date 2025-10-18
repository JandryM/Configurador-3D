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

        // Obtener los IDs de categoría
        $aluminumCategoryId = Category::where('name', 'perfil_aluminio')->value('id');
        $glassCategoryId = Category::where('name', 'vidrio')->value('id');
        $hardwareCategoryId = Category::where('name', 'herraje')->value('id');
        $sealingCategoryId = Category::where('name', 'sellado')->value('id');

        // Get materials by category_id
        $aluminumMaterials = Material::where('category_id', $aluminumCategoryId)->get();
        $glassMaterials = Material::where('category_id', $glassCategoryId)->get();
        $hardwareMaterials = Material::where('category_id', $hardwareCategoryId)->get();
        $sealingMaterials = Material::where('category_id', $sealingCategoryId)->get();

        // Get colors
        $aluminumColors = Color::whereIn('color_name', ['Natural', 'White', 'Black Anodized', 'Woody', 'Bronze'])->get();
        $glassColors = Color::whereIn('color_name', ['Transparent Glass', 'Reflective Blue Sky Glass', 'Reflective Gray Dark Glass'])->get();

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
