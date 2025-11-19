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
                'name' => 'Windows',
                'description' => 'Aluminum windows in various styles - sliding, casement, fixed',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Melamina',
                'description' => 'Productos y muebles de melamina',
                'is_active' => true,
                'sort_order' => 2
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
