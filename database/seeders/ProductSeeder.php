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
    public function run(): void
    {
        // Obtener un usuario admin
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

        // Obtener las categorías
        $windowsCategory = Category::where('name', 'Windows')->first();
        $melaminaCategory = Category::where('name', 'Melamina')->first();

        // Crear productos de prueba
        $products = [
            [
                'name' => 'Ventana Corrediza de 2 Hojas',
                'description' => 'Ventana corrediza de aluminio con 2 hojas deslizantes. Sistema premium con vidrio templado y herrajes de alta calidad.',
                'price' => 0.00,
                'category_id' => $windowsCategory?->id,
                'product_type' => 'customizable',
                'image' => null,
                'base_dimensions' => [
                    'width' => 1.2,
                    'height' => 1.5,
                    'depth' => 0.08,
                    'frameWidth' => 0.05
                ],
                'base_cost' => 0.00,
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
                ]
            ],
            [
                'name' => 'Malla Antimosquitos',
                'description' => 'Malla antimosquitos para ventanas, fabricada en aluminio y malla de alta resistencia. Ideal para evitar el ingreso de insectos sin perder ventilación.',
                'price' => 0.00,
                'category_id' => $windowsCategory?->id,
                'product_type' => 'customizable',
                'image' => null,
                'base_dimensions' => [
                    'width' => 1.0,
                    'height' => 1.2,
                    'depth' => 0.02,
                    'frameWidth' => 0.02
                ],
                'base_cost' => 0.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => false,
                'model_scale' => 1.0
            ],
            [
                'name' => 'Closet Melamina 2 Puertas',
                'description' => 'Closet de melamina con 2 puertas, estantes internos y acabado premium. Ideal para dormitorios modernos.',
                'price' => 0.00,
                'category_id' => $melaminaCategory?->id,
                'product_type' => 'customizable',
                'image' => null,
                'base_dimensions' => [
                    'width' => 1.5,
                    'height' => 2.0,
                    'depth' => 0.6
                ],
                'base_cost' => 0.00,
                'allows_customization' => true,
                'is_gallery_visible' => true,
                'has_3d_model' => false,
                'model_scale' => 1.0
            ],
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

            $this->attachMaterialsToProduct($product);
        }

        $this->command->info('Productos de prueba creados exitosamente!');
    }

    private function attachMaterialsToProduct($product)
    {
        $categoryName = strtolower($product->category?->name ?? '');
        
        if ($categoryName === 'windows') {
            $this->attachWindowMaterials($product);
        } elseif ($categoryName === 'doors') {
            $this->command->info("Producto {$product->name} es una puerta - sin materiales específicos configurados");
        } else {
            $this->command->info("Producto {$product->name} tiene categoría {$categoryName} - sin materiales específicos");
        }
    }

    private function attachWindowMaterials($product)
    {
        $materialsMapping = [
            // Perfiles del marco
            'Riel Superior/Inferior' => '{width} * 2',
            'Jamba Lateral' => '{height} * 2',
            
            // Perfiles de las hojas  
            'Horizontal de Hoja' => '({width} / 2) * 4',
            'Vertical Cerrado de Hoja' => '{height} * 4',
            
            // Herrajes
            'Ruedas Dobles para Deslizamiento' => '4',
            'Seguro Punto Rojo' => '1',
            'Tornillos de Fijación' => '8 + ceil(({width} + {height}) * 2 / 0.5)',
            
            // Vidrio
            'Vidrio Transparente 4mm' => '(({width}/2 - {frameWidth}*2) * ({height} - {frameWidth}*2)) * 2',
            
            // Sellado
            'Felpa para Ventana' => '{width}',
            'Caucho de Ventana' => '(2 * (({width}/2) + {height})) * 2'
        ];

        $attachedCount = 0;
        
        foreach ($materialsMapping as $materialName => $formula) {
            $material = Material::where('name', $materialName)->first();
            
            if ($material) {
                $product->materials()->attach($material->id, [
                    'calculation_formula' => $formula,
                    'notes' => 'Material para ventana corredera 2 hojas'
                ]);
                $attachedCount++;
                $this->command->info("✓ {$materialName} asignado a {$product->name}");
            } else {
                $this->command->warn("✗ Material {$materialName} no encontrado");
            }
        }
        
        $this->command->info("Total materiales asignados a {$product->name}: {$attachedCount}");
    }
}