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
        // Obtener las categorías
        $windowCategory = \App\Models\Category::where('name', 'Window')->first();
        $doorCategory = \App\Models\Category::where('name', 'Door')->first();
        $meshCategory = \App\Models\Category::where('name', 'Mesh')->first();

        $materials = [
            // === MATERIALES VENTANA CORREDIZA DE 2 HOJAS ===
            [
                'name' => 'Riel Superior/Inferior Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Perfil de aluminio para rieles superior e inferior de ventana corrediza.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 19.00 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 19.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 15.0,
                'min_stock_alert' => 5.0,
                'is_active' => true
            ],
            [
                'name' => 'Jamba Lateral Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Perfil de aluminio para los lados verticales del marco de ventana corrediza.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 17.50 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 17.50,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 12.0,
                'min_stock_alert' => 5.0,
                'is_active' => true
            ],
            [
                'name' => 'Horizontal Superior/Inferior de Hoja Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Perfil horizontal para la parte superior e inferior de la hoja de ventana corrediza.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 17.00 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 17.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 18.0,
                'min_stock_alert' => 6.0,
                'is_active' => true
            ],
            [
                'name' => 'Vertical de Hoja Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Perfil vertical para los laterales de la hoja de ventana corrediza.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 19.00 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 19.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 20.0,
                'min_stock_alert' => 8.0,
                'is_active' => true
            ],
            [
                'name' => 'Ruedas Dobles para Deslizamiento Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Ruedas dobles para el deslizamiento de las hojas de ventana corrediza.',
                'unit_measure' => 'unidad',
                'unit_price' => 1.00,
                'piece_size' => 1,
                'piece_price' => 1.00,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 200.0,
                'min_stock_alert' => 50.0,
                'is_active' => true
            ],
            [
                'name' => 'Seguro Punto Rojo Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Sistema de seguridad para ventana corrediza.',
                'unit_measure' => 'unidad',
                'unit_price' => 1.50,
                'piece_size' => 1,
                'piece_price' => 1.50,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 80.0,
                'min_stock_alert' => 20.0,
                'is_active' => true
            ],
            [
                'name' => 'Tornillos de Fijación',
                'category_id' => $windowCategory->id,
                'description' => 'Tornillos para fijación de componentes de ventana y puerta corrediza.',
                'unit_measure' => 'unidad',
                'unit_price' => 0.10,
                'piece_size' => 1,
                'piece_price' => 0.10,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 1000.0,
                'min_stock_alert' => 200.0,
                'is_active' => true
            ],
            // === MATERIALES PORTÓN CORREDIZO DE 2 HOJAS ===
            [
                'name' => 'Riel Superior Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil de aluminio para el riel superior del portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 39.55 / 6.4, 
                'piece_size' => 6.4,
                'piece_price' => 39.55,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 5.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Riel Inferior Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil de aluminio para el riel inferior del portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 37.43 / 6.4, 
                'piece_size' => 6.4,
                'piece_price' => 37.43,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 5.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Jamba Marco Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil de aluminio para los lados verticales del marco de portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 37.91 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 37.91,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 8.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Horizontal Superior de Hoja Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil horizontal para la parte superior de la hoja de portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 34.02 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 34.02,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 6.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Horizontal Inferior de Hoja Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil horizontal para la parte inferior de la hoja de portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 40.76 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 40.76,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 6.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Entrecierre Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil entrecierre para la unión de las hojas del portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 37.75 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 37.75,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 6.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Jamba Chapa Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Perfil jamba chapa para los laterales exteriores de la hoja del portón.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 40.71 / 6.4,
                'piece_size' => 6.4,
                'piece_price' => 40.71,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 6.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Cerradura Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Cerradura para portón corredizo, se instala junto a la jamba chapa.',
                'unit_measure' => 'unidad',
                'unit_price' => 5.455,
                'piece_size' => 1,
                'piece_price' => 5.455,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 10.0,
                'min_stock_alert' => 2.0,
                'is_active' => true
            ],
            [
                'name' => 'Ruedas Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Ruedas para portón corredizo, dos por hoja.',
                'unit_measure' => 'unidad',
                'unit_price' => 1.375,
                'piece_size' => 1,
                'piece_price' => 1.375,
                'is_by_piece' => false,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 40.0,
                'min_stock_alert' => 8.0,
                'is_active' => true
            ],
            [
                'name' => 'Caucho de Portón',
                'category_id' => $doorCategory->id,
                'description' => 'Caucho especial para portón corredizo.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 5.07 / 18.0,
                'piece_size' => 18.0,
                'piece_price' => 5.07,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 20.0,
                'min_stock_alert' => 5.0,
                'is_active' => true
            ],
            [
                'name' => 'Vidrio Transparente 6mm',
                'category_id' => $doorCategory->id,
                'description' => 'Vidrio transparente de 6mm de espesor para portón corredizo.',
                'unit_measure' => 'metros_cuadrados',
                'unit_price' => 75.00 / (2.14 * 3.30),
                'piece_size' => 2.14 * 3.30,
                'piece_price' => 75.00,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => true,
                'width' => 2.14,
                'height' => 3.30,
                'stock_quantity' => 10.0,
                'min_stock_alert' => 3.0,
                'is_active' => true
            ],
            [
                'name' => 'Vidrio Transparente 4mm',
                'category_id' => $windowCategory->id,
                'description' => 'Vidrio transparente de 4mm de espesor',
                'unit_measure' => 'metros_cuadrados',
                'unit_price' => 55.00 / (2.14 * 3.30), // Precio por m² calculado
                'piece_size' => 2.14 * 3.30, // 7.062 m² (área de la pieza)
                'piece_price' => 55.00, // Precio total de la pieza
                'is_by_piece' => true, // NO es por piezas lineales
                'supports_colors' => true,
                'has_dimensions' => true, // SÍ es por dimensiones (m²)
                'width' => 2.14,
                'height' => 3.30,
                // Campos de inventario
                'stock_quantity' => 25.0, // 25 piezas = 176.55 m²
                'min_stock_alert' => 8.0, // Alerta cuando haya menos de 8 piezas
                'is_active' => true
            ],
            [
                'name' => 'Felpa para Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Felpa que va en la parte superior de las hojas de la ventana',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 1.00 / 5.0, // $1 por rollo de 5m
                'piece_size' => 5.0,
                'piece_price' => 1.00,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false,
                // Campos de inventario
                'stock_quantity' => 30.0, // 30 rollos = 150m
                'min_stock_alert' => 10.0,
                'is_active' => true
            ],
            [
                'name' => 'Caucho de Ventana',
                'category_id' => $windowCategory->id,
                'description' => 'Caucho que va entre el marco y el vidrio para sostener y sellar',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 4.00 / 18.0, // $4 por rollo de 18m
                'piece_size' => 18.0,
                'piece_price' => 4.00,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false,
                // Campos de inventario
                'stock_quantity' => 40.0, // 40 rollos = 720m
                'min_stock_alert' => 15.0,
                'is_active' => true
            ],

            // === MATERIALES PARA MALLA ANTIMOSQUITOS ===
            [
                'name' => 'Perfil Aluminio Malla',
                'category_id' => $meshCategory->id,
                'description' => 'Perfil de aluminio especial para fabricar el marco perimetral de la malla antimosquitos. Se utiliza para formar el cuadrado o rectángulo que sostiene la malla.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 24.31 / 6.4, // $24.31 por pieza de 6.4m
                'piece_size' => 6.4,
                'piece_price' => 24.31,
                'is_by_piece' => true,
                'supports_colors' => true,
                'has_dimensions' => false,
                'stock_quantity' => 10.0, // 10 piezas de 6.4m
                'min_stock_alert' => 3.0,
                'is_active' => true
            ],
            [
                'name' => 'Esquinero Plástico',
                'category_id' => $meshCategory->id,
                'description' => 'Esquineros plásticos individuales para unir los perfiles de aluminio en las esquinas de la malla antimosquitos. Se requieren 4 por malla.',
                'unit_measure' => 'unidad',
                'unit_price' => 1.25, // $1.25 por unidad
                'piece_size' => 1.0,
                'piece_price' => 1.25,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 40.0, // 40 esquinas
                'min_stock_alert' => 8.0,
                'is_active' => true
            ],
            [
                'name' => 'Caucho de Malla',
                'category_id' => $meshCategory->id,
                'description' => 'Caucho flexible que se coloca alrededor del marco de la malla antimosquitos para fijar y sellar la malla al perfil de aluminio.',
                'unit_measure' => 'metro_lineal',
                'unit_price' => 4.46 / 18.0, // $4.46 por rollo de 18m
                'piece_size' => 18.0,
                'piece_price' => 4.46,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => false,
                'stock_quantity' => 20.0, // 20 rollos
                'min_stock_alert' => 5.0,
                'is_active' => true
            ],
            [
                'name' => 'Malla Fibra de Vidrio',
                'category_id' => $meshCategory->id,
                'description' => 'Malla de fibra de vidrio resistente para protección contra insectos. Se vende por piezas de 2.14m x 3.30m (7.062 m²).',
                'unit_measure' => 'metros_cuadrados',
                'unit_price' => 5.50 / (2.14 * 3.30), // $5.5 por pieza de 7.062 m²
                'piece_size' => 2.14 * 3.30,
                'piece_price' => 5.50,
                'is_by_piece' => true,
                'supports_colors' => false,
                'has_dimensions' => true,
                'width' => 2.14,
                'height' => 3.30,
                'stock_quantity' => 15.0, // 15 piezas
                'min_stock_alert' => 4.0,
                'is_active' => true
            ]
        ];

        // Crear los materiales sin retazos iniciales
        foreach ($materials as $materialData) {
            Material::create($materialData);
        }
    }
}