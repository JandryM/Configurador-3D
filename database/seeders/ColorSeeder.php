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
                'color_name' => 'Transparent Glass',
                'percentage_increment' => 0.00,
                'texture_path' => '/textures/glass/transparent/',
                'is_active' => true,
                'sort_order' => 10
            ],
            [
                'color_name' => 'Reflective Blue Sky Glass',
                'percentage_increment' => 8.00,
                'texture_path' => '/textures/glass/reflective_blue/',
                'is_active' => true,
                'sort_order' => 11
            ],
            [
                'color_name' => 'Reflective Gray Dark Glass',
                'percentage_increment' => 8.00,
                'texture_path' => '/textures/glass/reflective_gray_dark/',
                'is_active' => true,
                'sort_order' => 12
            ],
        ];

        foreach ($colors as $colorData) {
            Color::create($colorData);
        }
    }
}
