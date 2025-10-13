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
                'name' => 'Doors',
                'description' => 'Aluminum doors - entrance doors, patio doors, security doors',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Glass Panels',
                'description' => 'Fixed glass panels and curtain walls',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Sliding Systems',
                'description' => 'Large sliding door and window systems',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Security',
                'description' => 'Security windows and doors with reinforced frames',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
