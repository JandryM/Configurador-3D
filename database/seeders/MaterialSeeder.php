<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            // Perfiles de aluminio para marco
            [
                'name' => 'Riel Superior/Inferior',
                'category_id' => 1,
                'description' => 'Perfil de aluminio para rieles superior e inferior donde se deslizan las hojas de la ventana',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 19.00 / 6.4, // $19 por pieza de 6.4m
                'piece_size' => 6.4,
                'piece_price' => 19.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false
            ],
            [
                'name' => 'Jamba Lateral',
                'category_id' => 1,
                'description' => 'Perfil de aluminio para los lados verticales del marco (izquierda y derecha)',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 17.50 / 6.4, // $17.50 por pieza de 6.4m
                'piece_size' => 6.4,
                'piece_price' => 17.50,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false
            ],
            [
                'name' => 'Horizontal de Hoja',
                'category_id' => 1,
                'description' => 'Perfil horizontal para la parte superior e inferior del cuadro de la hoja (donde va el vidrio)',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 17.00 / 6.4, // $17 por pieza de 6.4m
                'piece_size' => 6.4,
                'piece_price' => 17.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false
            ],
            [
                'name' => 'Vertical Cerrado de Hoja',
                'category_id' => 1,
                'description' => 'Perfil vertical para los laterales del cuadro de la hoja de ventana',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 19.00 / 6.4, // $19 por pieza de 6.4m
                'piece_size' => 6.4,
                'piece_price' => 19.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false
            ],
            [
                'name' => 'Ruedas Dobles para Deslizamiento',
                'category_id' => 1,
                'description' => 'Ruedas dobles para el deslizamiento de las hojas de ventana (2 por hoja, 4 por ventana completa)',
                'unit_measure' => 'unidad',
                'unit_price' => 1.00,
                'piece_size' => 1,
                'piece_price' => 1.00,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false
            ],
            [
                'name' => 'Seguro Punto Rojo',
                'category_id' => 1,
                'description' => 'Sistema de seguridad para ventana corredera (1 por ventana completa)',
                'unit_measure' => 'unidad',
                'unit_price' => 1.50,
                'piece_size' => 1,
                'piece_price' => 1.50,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false
            ],
            [
                'name' => 'Tornillos de Fijación',
                'category_id' => 1,
                'description' => 'Tornillos para fijación de componentes (aproximadamente 4 por hoja)',
                'unit_measure' => 'unidad',
                'unit_price' => 0.10,
                'piece_size' => 1,
                'piece_price' => 0.10,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false
            ],
            [
                'name' => 'Vidrio Transparente 4mm',
                'category_id' => 1,
                'description' => 'Vidrio transparente de 4mm de espesor',
                'unit_measure' => 'metros_cuadrados',
                'unit_price' => 55.00 / (2.14 * 3.30), // $55 por pieza de 2.14m x 3.30m
                'piece_size' => 2.14 * 3.30, // 7.062 m²
                'piece_price' => 55.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => true,
                'width' => 2.14,
                'height' => 3.30,
                'calculated_area' => 2.14 * 3.30
            ],
            [
                'name' => 'Felpa para Ventana',
                'category_id' => 1,
                'description' => 'Felpa que va en la parte superior de las hojas de la ventana',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 1.00 / 5.0, // $1 por rollo de 5m
                'piece_size' => 5.0,
                'piece_price' => 1.00,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false
            ],
            [
                'name' => 'Caucho de Ventana',
                'category_id' => 1,
                'description' => 'Caucho que va entre el marco y el vidrio para sostener y sellar',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 4.00 / 18.0, // $4 por rollo de 18m
                'piece_size' => 18.0,
                'piece_price' => 4.00,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false
            ]
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}