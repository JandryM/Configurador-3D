<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Material;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener un usuario admin o crear uno por defecto
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Administrador',
                'email' => 'admin@qualityservices.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'profile_completed_at' => now(),
                'is_active' => true,
            ]);
        }

        // Crear materiales básicos si no existen
        $this->createBasicMaterials();

        // Obtener las categorías
        $windowsCategory = Category::where('name', 'Windows')->first();
        $doorsCategory = Category::where('name', 'Doors')->first();
        $glassPanelsCategory = Category::where('name', 'Glass Panels')->first();

        // Productos de prueba para el configurador 3D
        $products = [
            [
                'name' => 'Ventana de Aluminio Estándar',
                'description' => 'Ventana de aluminio con vidrio templado, ideal para personalización. Permite ajustar dimensiones, colores y accesorios según las necesidades del cliente.',
                'price' => 0.00, // Precio calculado dinámicamente por materiales
                'category_id' => $windowsCategory?->id,
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 1.2,
                    'height' => 1.5,
                    'depth' => 0.08,
                    'frameWidth' => 0.05
                ],
                'base_cost' => 0.00, // Sin costo base - solo materiales
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0,
                'model_3d_settings' => [
                    'backgroundColor' => '#f0f0f0',
                    'enableControls' => true,
                    'showWireframe' => false,
                    'enableShadows' => true,
                    'showGrid' => false,
                    'ambientLightIntensity' => 0.6,
                    'directionalLightIntensity' => 0.8,
                    'cameraPosition' => ['x' => 5, 'y' => 5, 'z' => 5]
                ]
            ],
            [
                'name' => 'Puerta de Madera Personalizable',
                'description' => 'Puerta de madera sólida con herrajes incluidos. Configurador 3D permite seleccionar dimensiones, tipo de madera, color y accesorios.',
                'price' => 450.00,
                'category_id' => $doorsCategory?->id,
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 0.9,
                    'height' => 2.1,
                    'depth' => 0.04,
                    'frameWidth' => 0.08
                ],
                'base_cost' => 200.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0,
                'model_3d_settings' => [
                    'backgroundColor' => '#f8f8f8',
                    'enableControls' => true,
                    'showWireframe' => false,
                    'enableShadows' => true,
                    'showGrid' => false,
                    'ambientLightIntensity' => 0.7,
                    'directionalLightIntensity' => 0.9
                ]
            ],
            [
                'name' => 'Mesa de Comedor Modular',
                'description' => 'Mesa de comedor con dimensiones ajustables. El configurador 3D permite personalizar tamaño, material de la superficie y estilo de las patas.',
                'price' => 680.00,
                'category_id' => $glassPanelsCategory?->id, // Usando Glass Panels como ejemplo
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 1.6,
                    'height' => 0.75,
                    'depth' => 0.9,
                    'legs' => true
                ],
                'base_cost' => 300.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0,
                'model_3d_settings' => [
                    'backgroundColor' => '#ffffff',
                    'enableControls' => true,
                    'showWireframe' => false,
                    'enableShadows' => true,
                    'showGrid' => true,
                    'ambientLightIntensity' => 0.5,
                    'directionalLightIntensity' => 1.0
                ]
            ],
            [
                'name' => 'Ventana Corrediza Premium',
                'description' => 'Ventana corrediza de alta calidad con doble vidrio hermético. Sistema de rieles premium y múltiples opciones de personalización.',
                'price' => 380.00,
                'category_id' => $windowsCategory?->id,
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 2.0,
                    'height' => 1.4,
                    'depth' => 0.12,
                    'frameWidth' => 0.06
                ],
                'base_cost' => 180.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0
            ],
            [
                'name' => 'Escritorio Ejecutivo',
                'description' => 'Escritorio ejecutivo con gavetas y espacio de almacenamiento. Dimensiones y acabados completamente personalizables.',
                'price' => 520.00,
                'category_id' => $doorsCategory?->id, // Usando Doors como ejemplo
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 1.4,
                    'height' => 0.75,
                    'depth' => 0.7
                ],
                'base_cost' => 250.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0
            ],
            [
                'name' => 'Closet Modular',
                'description' => 'Sistema de closet modular con múltiples configuraciones. Permite ajustar altura, ancho, número de divisiones y accesorios.',
                'price' => 890.00,
                'category_id' => $doorsCategory?->id, // Usando Doors como ejemplo
                'product_type' => 'customizable',
                'image' => null, // Sin imagen - usar configurador 3D
                'base_dimensions' => [
                    'width' => 2.0,
                    'height' => 2.4,
                    'depth' => 0.6
                ],
                'base_cost' => 400.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => true,
                'model_scale' => 1.0
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'category_id' => $productData['category_id'],
                'product_type' => $productData['product_type'],
                'base_dimensions' => $productData['base_dimensions'],
                'base_cost' => $productData['base_cost'],
                'allows_customization' => $productData['allows_customization'],
                'is_gallery_visible' => $productData['is_gallery_visible'],
                'image' => $productData['image'] ?? null,
                'has_3d_model' => $productData['has_3d_model'] ?? false,
                'model_scale' => $productData['model_scale'] ?? 1.0,
                'model_3d_settings' => $productData['model_3d_settings'] ?? null,
                'user_id' => $adminUser->id,
            ]);

            // Asociar materiales según el tipo de producto
            $this->attachMaterialsToProduct($product);
        }

        $this->command->info('Productos de prueba creados exitosamente!');
    }

    private function createBasicMaterials()
    {
        // Ya no necesitamos crear materiales aquí
        // Los materiales reales se crean en MaterialSeeder
    }

    private function attachMaterialsToProduct($product)
    {
        $categoryName = $product->category?->name ?? '';
        
        if (strtolower($categoryName) === 'windows') {
            // Ventanas correderas - materiales específicos reales
            
            // Perfiles del marco
            $rielSuperior = Material::where('name', 'Riel Superior/Inferior')->first();
            $jambaLateral = Material::where('name', 'Jamba Lateral')->first();
            
            // Perfiles de las hojas
            $horizontalHoja = Material::where('name', 'Horizontal de Hoja')->first();
            $verticalCerrado = Material::where('name', 'Vertical Cerrado de Hoja')->first();
            
            // Herrajes
            $ruedas = Material::where('name', 'Ruedas Dobles para Deslizamiento')->first();
            $seguro = Material::where('name', 'Seguro Punto Rojo')->first();
            $tornillos = Material::where('name', 'Tornillos de Fijación')->first();
            
            // Vidrio
            $vidrio = Material::where('name', 'Vidrio Transparente 4mm')->first();
            
            // Sellado
            $felpa = Material::where('name', 'Felpa para Ventana')->first();
            $caucho = Material::where('name', 'Caucho de Ventana')->first();
            
            $materials = [
                ['material' => $rielSuperior, 'formula' => 'Ancho × 2 (superior + inferior)', 'waste' => 5.0],
                ['material' => $jambaLateral, 'formula' => 'Alto × 2 (izquierda + derecha)', 'waste' => 5.0],
                ['material' => $horizontalHoja, 'formula' => '(Ancho/2) × 4 (2 hojas, sup+inf)', 'waste' => 10.0],
                ['material' => $verticalCerrado, 'formula' => 'Alto × 4 (2 laterales × 2 hojas)', 'waste' => 10.0],
                ['material' => $ruedas, 'formula' => '4 unidades (2 por hoja)', 'waste' => 0],
                ['material' => $seguro, 'formula' => '1 por ventana completa', 'waste' => 0],
                ['material' => $tornillos, 'formula' => '8 hojas + marco según perímetro', 'waste' => 20.0],
                ['material' => $vidrio, 'formula' => 'Área útil × 2 hojas', 'waste' => 5.0],
                ['material' => $felpa, 'formula' => 'Ancho total (parte superior)', 'waste' => 10.0],
                ['material' => $caucho, 'formula' => 'Perímetro × 2 hojas', 'waste' => 15.0]
            ];
            
            foreach ($materials as $materialData) {
                if ($materialData['material']) {
                    $product->materials()->attach($materialData['material']->id, [
                        'quantity' => 0, // Se calculará dinámicamente
                        'used_quantity' => 0,
                        'waste_percentage' => $materialData['waste'],
                        'calculation_formula' => $materialData['formula'],
                        'calculated_cost' => 0, // Se calculará dinámicamente
                        'notes' => 'Material para ventana corredera 2 hojas'
                    ]);
                }
            }
            
        }
    }
}
