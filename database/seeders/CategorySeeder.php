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
        $categories = [
            [
                'name' => 'Aluminio',
                'description' => 'Productos y perfiles de aluminio',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Vidrio',
                'description' => 'Paneles, puertas y accesorios de vidrio',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Melamina',
                'description' => 'Productos y muebles de melamina',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
