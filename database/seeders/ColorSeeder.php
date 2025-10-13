<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            // Aluminum colors
            [
                'color_name' => 'Natural',
                'percentage_increment' => 0.00,
                'texture_path' => '/textures/aluminum/natural/',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'color_name' => 'White',
                'percentage_increment' => 5.00,
                'texture_path' => '/textures/aluminum/white/',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'color_name' => 'Black Anodized',
                'percentage_increment' => 15.00,
                'texture_path' => '/textures/aluminum/black/',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'color_name' => 'Woody',
                'percentage_increment' => 12.00,
                'texture_path' => '/textures/aluminum/woody/',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'color_name' => 'Bronze',
                'percentage_increment' => 12.00,
                'texture_path' => '/textures/aluminum/bronze/',
                'is_active' => true,
                'sort_order' => 5
            ],
            // Glass colors
            [
                'color_name' => 'Clear Glass',
                'percentage_increment' => 0.00,
                'texture_path' => '/textures/glass/clear/',
                'is_active' => true,
                'sort_order' => 10
            ],
            [
                'color_name' => 'Bronze Glass',
                'percentage_increment' => 8.00,
                'texture_path' => '/textures/glass/bronze/',
                'is_active' => true,
                'sort_order' => 11
            ],
            [
                'color_name' => 'Gray Glass',
                'percentage_increment' => 8.00,
                'texture_path' => '/textures/glass/gray/',
                'is_active' => true,
                'sort_order' => 12
            ],
            [
                'color_name' => 'Reflective Glass',
                'percentage_increment' => 25.00,
                'texture_path' => '/textures/glass/reflective/',
                'is_active' => true,
                'sort_order' => 13
            ]
        ];

        foreach ($colors as $colorData) {
            Color::create($colorData);
        }
    }
}
