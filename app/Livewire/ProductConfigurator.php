<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Material;
use App\Models\Color;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductConfigurator extends Component
{
    public Product $product;
    public $configuration = [];
    public $calculatedPrice = 0;
    public $materialCosts = [];
    public $showPriceBreakdown = false;
    
    // Parámetros por defecto para la configuración
    public $parameters = [
        'width' => 1200,            // mm
        'height' => 1500,           // mm
        'panelCount' => 2,          // número de paneles
        'color' => 'Natural',       // Nombre del color desde la BD (controla tanto frame como textura)
        'glassColor' => '#E0F6FF',  // Color del vidrio en hexadecimal
        'frameColor' => 0xC0C0C0,   // Color base del frame en hexadecimal (se usa si no hay texturas) - Plata claro
        'material' => null,
        'glassMaterial' => null,
        'hardware' => null
    ];

    // Límites por tipo de producto
    public $limits = [
        'window' => [
            'width' => ['min' => 0.3, 'max' => 3.0, 'step' => 0.1],
            'height' => ['min' => 0.3, 'max' => 2.5, 'step' => 0.1],
            'depth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.01],
            'frameWidth' => ['min' => 0.02, 'max' => 0.1, 'step' => 0.01]
        ],
        'door' => [
            'width' => ['min' => 0.6, 'max' => 1.2, 'step' => 0.1],
            'height' => ['min' => 1.8, 'max' => 2.5, 'step' => 0.1],
            'depth' => ['min' => 0.03, 'max' => 0.1, 'step' => 0.01],
            'frameWidth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.01]
        ],
        'furniture' => [
            'width' => ['min' => 0.3, 'max' => 2.0, 'step' => 0.1],
            'height' => ['min' => 0.5, 'max' => 2.0, 'step' => 0.1],
            'depth' => ['min' => 0.3, 'max' => 1.0, 'step' => 0.1]
        ]
    ];

        // Colores disponibles (se cargan desde la base de datos)
    public $availableColors = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        
        // Cargar colores disponibles desde la base de datos
        $this->loadAvailableColors();
        
        // Cargar configuración inicial del producto
        if ($product->base_dimensions) {
            $this->parameters = array_merge($this->parameters, $product->base_dimensions);
        }

        // Establecer tipo de producto para límites
        $this->setParameterLimits();
        
        // Calcular precio inicial
        $this->calculatePrice();
    }

    private function setParameterLimits()
    {
        $productType = $this->getProductType();
        if (isset($this->limits[$productType])) {
            // Ajustar parámetros a los límites si es necesario
            foreach ($this->limits[$productType] as $param => $limit) {
                if (isset($this->parameters[$param])) {
                    $this->parameters[$param] = max(
                        $limit['min'], 
                        min($limit['max'], $this->parameters[$param])
                    );
                }
            }
        }
    }

    private function getProductType()
    {
        $categoryName = strtolower($this->product->category?->name ?? '');
        
        if (str_contains($categoryName, 'window') || str_contains($categoryName, 'ventana') || str_contains($categoryName, 'vidrio')) {
            return 'window';
        } elseif (str_contains($categoryName, 'door') || str_contains($categoryName, 'puerta')) {
            return 'door';
        } else {
            return 'furniture';
        }
    }

    public function updateParameter($parameter, $value)
    {
        // Validar límites
        $productType = $this->getProductType();
        if (isset($this->limits[$productType][$parameter])) {
            $limit = $this->limits[$productType][$parameter];
            $value = max($limit['min'], min($limit['max'], (float)$value));
        }

        $this->parameters[$parameter] = $value;
        
        // No necesitamos sincronizar frameColor ya que se obtiene dinámicamente desde la BD
        
        $this->calculatePrice();
        
        // Dispatch evento para actualizar modelo 3D
        $parametersFor3D = $this->parameters;
        if ($this->getProductType() === 'window') {
            $parametersFor3D = array_merge($this->parameters, ['depth' => 1.0]);
        }
        
    // Incluir solo la ruta de textura correspondiente al color seleccionado
    $parametersFor3D['texturePath'] = $this->getColorTexturePath($this->parameters['color']);
    $this->dispatch('updateModel3D', parameters: $parametersFor3D);
    }

    public function calculatePrice()
    {
        try {
            $this->materialCosts = [];
            $totalCost = 0; // Sin costo base - solo materiales

            // Calcular área/volumen según el tipo de producto
            $area = $this->parameters['width'] * $this->parameters['height'];
            $depth = $this->getProductType() === 'window' ? 1.0 : $this->parameters['depth'];
            $volume = $area * $depth;

            // Obtener materiales del producto
            $materials = $this->product->materials;

            foreach ($materials as $material) {
                $quantity = $this->calculateMaterialQuantity($material, $area, $volume);
                $cost = $material->calculateCost($quantity);
                
                $this->materialCosts[] = [
                    'name' => $material->name,
                    'quantity' => $quantity,
                    'unit' => $material->unit_measure,
                    'unit_price' => $material->unit_price,
                    'total_cost' => $cost
                ];

                $totalCost += $cost;
            }

            // Aplicar incremento por color
            $colorIncrement = $this->getColorIncrement();
            $totalCostWithColor = $totalCost * (1 + ($colorIncrement / 100));

            // Aplicar margen de ganancia
            $profitMargin = 1.4; // 40% margen
            $this->calculatedPrice = $totalCostWithColor * $profitMargin;

        } catch (\Exception $e) {
            \Log::error('Error calculating price: ' . $e->getMessage());
            $this->calculatedPrice = $this->product->price ?? 0;
        }
    }

    private function calculateMaterialQuantity($material, $area, $volume)
    {
        $materialName = strtolower($material->name);
        $width = $this->parameters['width'];
        $height = $this->parameters['height'];
        
        // Cálculos específicos para ventana corredera de 2 hojas
        switch (true) {
            // Perfiles del marco principal
            case str_contains($materialName, 'riel superior') || str_contains($materialName, 'riel inferior'):
                // 2 rieles (superior e inferior) del ancho de la ventana
                return $width * 2;
                
            case str_contains($materialName, 'jamba lateral'):
                // 2 jambas laterales de la altura de la ventana
                return $height * 2;
                
            // Perfiles de las hojas (2 hojas)
            case str_contains($materialName, 'horizontal de hoja'):
                // 4 horizontales (2 superior + 2 inferior) para 2 hojas
                // Cada hoja tiene ancho = width/2
                return ($width / 2) * 4;
                
            case str_contains($materialName, 'vertical cerrado'):
                // 4 verticales (2 por cada hoja) de la altura completa
                return $height * 4;
                
            // Herrajes
            case str_contains($materialName, 'ruedas dobles'):
                // 4 ruedas (2 por hoja, 2 hojas)
                return 4;
                
            case str_contains($materialName, 'seguro punto rojo'):
                // 1 seguro por ventana completa
                return 1;
                
            case str_contains($materialName, 'tornillos'):
                // 4 tornillos por hoja (8 para 2 hojas) + tornillos adicionales para marco
                $hojasTornillos = 8; // 4 por hoja × 2 hojas
                $marcoTornillos = ceil(($width + $height) * 2 / 0.5); // 1 tornillo cada 50cm aprox
                return $hojasTornillos + $marcoTornillos;
                
            // Vidrio
            case str_contains($materialName, 'vidrio'):
                // Área de vidrio = 2 hojas con área útil (descontando marco)
                $frameWidth = $this->parameters['frameWidth'] ?? 0.05;
                $vidrioPorHoja = ($width/2 - $frameWidth*2) * ($height - $frameWidth*2);
                return $vidrioPorHoja * 2; // 2 hojas
                
            // Sellado
            case str_contains($materialName, 'felpa'):
                // Felpa va solo en la parte superior de las hojas
                return $width; // Solo superior, para ambas hojas
                
            case str_contains($materialName, 'caucho'):
                // Caucho va en todo el perímetro de cada hoja
                $perimetroPorHoja = 2 * (($width/2) + $height);
                return $perimetroPorHoja * 2; // 2 hojas
                
            default:
                // Fallback para otros materiales
                return $material->has_dimensions ? $area : $volume;
        }
    }

    public function saveConfiguration()
    {
        try {
            // Guardar configuración del usuario
            $configuration = [
                'product_id' => $this->product->id,
                'parameters' => $this->parameters,
                'calculated_price' => $this->calculatedPrice,
                'material_costs' => $this->materialCosts,
                'timestamp' => now()
            ];

            // Si el usuario está autenticado, guardarlo en la base de datos
            if (auth()->check()) {
                DB::table('product_configurations')->insert([
                    'user_id' => auth()->id(),
                    'product_id' => $this->product->id,
                    'configuration' => json_encode($configuration),
                    'price' => $this->calculatedPrice,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Guardar en sesión
            session(['product_configuration' => $configuration]);

            session()->flash('message', '¡Configuración guardada exitosamente!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la configuración: ' . $e->getMessage());
        }
    }

    public function addToCart()
    {
        try {
            $cartItem = [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'configuration' => $this->parameters,
                'price' => $this->calculatedPrice,
                'quantity' => 1,
                'is_custom' => true
            ];

            // Agregar al carrito (usando sesión por simplicidad)
            $cart = session('cart', []);
            $cart[] = $cartItem;
            session(['cart' => $cart]);

            session()->flash('message', '¡Producto personalizado agregado al carrito!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar al carrito: ' . $e->getMessage());
        }
    }

    public function requestQuote()
    {
        $this->saveConfiguration();
        
        // Redirigir a formulario de cotización con la configuración
        return redirect()->route('quote.request')->with([
            'product_configuration' => [
                'product_id' => $this->product->id,
                'parameters' => $this->parameters,
                'price' => $this->calculatedPrice
            ]
        ]);
    }

    public function exportConfiguration()
    {
        $config = [
            'product' => $this->product->name,
            'parameters' => $this->parameters,
            'price' => $this->calculatedPrice,
            'materials' => $this->materialCosts,
            'generated_at' => now()->format('Y-m-d H:i:s')
        ];

        $this->dispatch('downloadConfiguration', configuration: $config);
    }

    public function getLimitsForParameter($parameter)
    {
        $productType = $this->getProductType();
        return $this->limits[$productType][$parameter] ?? null;
    }

    private function getColorIncrement()
    {
        // Usar directamente el nombre del color desde los parámetros
        $selectedColorName = $this->parameters['color'] ?? 'Natural';
        
        // Buscar el incremento en la base de datos
        $color = Color::where('color_name', $selectedColorName)->first();
        
        return $color ? $color->percentage_increment : 0;
    }

    private function loadAvailableColors()
    {
        // Cargar todos los colores activos
        $this->availableColors = Color::where('is_active', true)
                                     ->where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->get()
                                     ->keyBy('color_name');
    }

    public function getAvailableColors()
    {
        return $this->availableColors;
    }

    public function getColorTexturePath($colorName)
    {
        $color = $this->availableColors[$colorName] ?? null;
        return $color ? $color->texture_path : '/textures/aluminum/natural/';
    }



    public function render()
    {
        $selectedColor = $this->availableColors[$this->parameters['color']] ?? null;
        
        return view('livewire.product-configurator', [
            'productType' => $this->getProductType(),
            'parameterLimits' => $this->limits[$this->getProductType()] ?? [],
            'availableColors' => $this->getAvailableColors(),
            'selectedColor' => $selectedColor,
            'colorTexturePath' => $this->getColorTexturePath($this->parameters['color'])
        ])->layout('components.layouts.configurator', [
            'title' => 'Configurador 3D - ' . $this->product->name,
            'description' => 'Personaliza ' . $this->product->name . ' en tiempo real con nuestro configurador 3D interactivo.'
        ]);
    }
}
