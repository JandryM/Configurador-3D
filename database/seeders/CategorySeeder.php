<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categoría principal
        $aluminioVidrio = Category::create([
            'name' => 'Aluminio y Vidrio',
            'description' => 'Productos fabricados en aluminio y vidrio',
            'is_active' => true,
            'sort_order' => 1,
            'parent_id' => null
        ]);

        // Crear subcategorías
        $subcategories = [
            [
                'name' => 'Window',
                'description' => 'Ventanas corredizas de aluminio con vidrio',
                'is_active' => true,
                'sort_order' => 1,
                'parent_id' => $aluminioVidrio->id
            ],
            [
                'name' => 'Door',
                'description' => 'Puertas y portones corredizos de aluminio con vidrio',
                'is_active' => true,
                'sort_order' => 2,
                'parent_id' => $aluminioVidrio->id
            ],
            [
                'name' => 'Mesh',
                'description' => 'Mallas antimosquitos con marco de aluminio',
                'is_active' => true,
                'sort_order' => 3,
                'parent_id' => $aluminioVidrio->id
            ],
        ];

        foreach ($subcategories as $subcategoryData) {
            Category::create($subcategoryData);
        }
    }
}
