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

        // Obtener las subcategorías
        $ventanaCategory = Category::where('name', 'Window')->first();
        $puertaCategory = Category::where('name', 'Door')->first();
        $mallaCategory = Category::where('name', 'Mesh')->first();


        // Definir materiales y fórmulas específicos por producto
        $products = [
            [
                'name' => 'Ventana Corrediza de 2 Hojas',
                'description' => 'Ventana corrediza de aluminio con 2 hojas deslizantes. Sistema premium con vidrio templado y herrajes de alta calidad.',
                'price' => 0.00,
                'category_id' => $ventanaCategory?->id,
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
                'is_gallery_visible' => false,
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
                ],
                'materials' => [
                    ['name' => 'Riel Superior/Inferior Ventana', 'formula' => '{width} * 2'],
                    ['name' => 'Jamba Lateral Ventana', 'formula' => '{height} * 2'],
                    ['name' => 'Horizontal Superior/Inferior de Hoja Ventana', 'formula' => '({width} / 2) * 4'],
                    ['name' => 'Vertical de Hoja Ventana', 'formula' => '{height} * 4'],
                    ['name' => 'Ruedas Dobles para Deslizamiento Ventana', 'formula' => '4'],
                    ['name' => 'Seguro Punto Rojo Ventana', 'formula' => '1'],
                    ['name' => 'Tornillos de Fijación', 'formula' => '8 + ceil(({width} + {height}) * 2 / 0.5)'],
                    ['name' => 'Vidrio Transparente 4mm', 'formula' => '(({width}/2 - {frameWidth}*2) * ({height} - {frameWidth}*2)) * 2'],
                    ['name' => 'Felpa para Ventana', 'formula' => '{width}'],
                    ['name' => 'Caucho de Ventana', 'formula' => '(2 * (({width}/2) + {height})) * 2'],
                ]
            ],
            [
                'name' => 'Portón Corredizo de 2 Hojas',
                'description' => 'Portón corredizo de aluminio con 2 hojas deslizantes, materiales reforzados y vidrio de 6mm.',
                'price' => 0.00,
                'category_id' => $puertaCategory?->id,
                'product_type' => 'customizable',
                'image' => null,
                'base_dimensions' => [
                    'width' => 2,
                    'height' => 2.5,
                    'frameWidth' => 0.01,
                    'hojas' => 2
                    ],
                'base_cost' => 0.00,
                'allows_customization' => true,
                'is_gallery_visible' => false,
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
                    ],
                'materials' => [
                    ['name' => 'Riel Superior Portón', 'formula' => '{width}'],
                    ['name' => 'Riel Inferior Portón', 'formula' => '{width}'],
                    ['name' => 'Jamba Marco Portón', 'formula' => '{height} * 2'],
                    ['name' => 'Horizontal Superior de Hoja Portón', 'formula' => '{width} / 2'],
                    ['name' => 'Horizontal Inferior de Hoja Portón', 'formula' => '{width} / 2'],
                    ['name' => 'Entrecierre Portón', 'formula' => '{height}'],
                    ['name' => 'Jamba Chapa Portón', 'formula' => '{height}'],
                    ['name' => 'Cerradura Portón', 'formula' => '2'],
                    ['name' => 'Ruedas Portón', 'formula' => '4'],
                    ['name' => 'Tornillos de Fijación', 'formula' => '8 + ceil(({width} + {height}) * 2 / 0.5)'],
                    ['name' => 'Caucho de Portón', 'formula' => '2 * (({width}/2) + {height})'],
                    ['name' => 'Vidrio Transparente 6mm', 'formula' => '(({width}/2 - {frameWidth}*2) * ({height} - {frameWidth}*2)) * 2'],
                    ]
            ],
            [
                'name' => 'Malla Antimosquitos',
                'description' => 'Malla antimosquitos para ventanas, fabricada con perfiles de aluminio, malla de fibra de vidrio, esquineros plásticos y caucho especial para sellado. Ideal para evitar el ingreso de insectos sin perder ventilación.',
                'price' => 0.00,
                'category_id' => $mallaCategory?->id,
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
                'is_gallery_visible' => false,
                'has_3d_model' => false,
                'model_scale' => 1.0,
                'materials' => [
                    [
                        'name' => 'Perfil Aluminio Malla',
                        'formula' => '({width} + {height}) * 2',
                        // Marco perimetral de aluminio para la malla
                    ],
                    [
                        'name' => 'Malla Fibra de Vidrio',
                        'formula' => '{width} * {height}',
                        // Superficie de malla antimosquitos
                    ],
                    [
                        'name' => 'Esquinero Plástico',
                        'formula' => '4',
                        // Uniones plásticas para las esquinas
                    ],
                    [
                        'name' => 'Caucho de Malla',
                        'formula' => '({width} + {height}) * 2',
                        // Caucho para fijar y sellar la malla
                    ],
                ]
            ],
        ];

        foreach ($products as $productData) {
            $slug = $this->generateSlug($productData['name']);
            $product = Product::create([
                'name' => $productData['name'],
                'slug' => $slug,
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

            $this->attachSpecificMaterials($product, $productData['materials'] ?? []);
        }

        $this->command->info('Productos de prueba creados exitosamente!');
    }

        /**
     * Genera un slug único para el producto
     */
    private function generateSlug($name)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }

    private function attachSpecificMaterials($product, $materials)
    {
        $attachedCount = 0;
        foreach ($materials as $mat) {
            $material = Material::where('name', $mat['name'])->first();
            if ($material) {
                $product->materials()->attach($material->id, [
                    'calculation_formula' => $mat['formula'],
                    'notes' => 'Material asignado por seeder'
                ]);
                $attachedCount++;
                $this->command->info("✓ {$mat['name']} asignado a {$product->name}");
            } else {
                $this->command->warn("✗ Material {$mat['name']} no encontrado");
            }
        }
        $this->command->info("Total materiales asignados a {$product->name}: {$attachedCount}");
    }
}