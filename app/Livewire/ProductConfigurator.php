<?php
namespace App\Livewire;
use App\Models\Product;
use App\Models\Color;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductConfigurator extends Component
{
    public Product $product;
    public $calculatedPrice = 0;
    public $materialCosts = [];
    // Parámetros por defecto para la configuración
    public $parameters = [
        'width' => 1.2,
        'height' => 1.5,
        'color' => 'Natural',
        'glassColor' => 'Transparent Glass',
    ];
    // Límites por tipo de producto
    public $limits = [
        'window' => [
            'width' => ['min' => 0.5, 'max' => 4.0, 'step' => 0.1],
            'height' => ['min' => 0.3, 'max' => 3.5, 'step' => 0.1],
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

    // Métodos de cálculo de costos
    private function calcularCostoVidrio($material, $quantity)
    {
        $glassColor = $this->parameters['glassColor'] ?? 'Transparent Glass';
        $colorId = Color::where('color_name', $glassColor)->value('id');
        $pivot = DB::table('material_color')
            ->where('material_id', $material->id)
            ->where('color_id', $colorId)
            ->where('category_id', $material->category_id)
            ->first();
        $increase = $pivot ? $pivot->increase_value ?? 0 : 0;
        $pieceSize = $material->piece_size;
        $piecePrice = $material->piece_price;
        $precioVidrioFinal = $piecePrice + $increase;
        if ($pieceSize > 0) {
            $proporcion = $quantity / $pieceSize;
            $cost = $precioVidrioFinal * $proporcion;
        } else {
            $cost = $precioVidrioFinal * $quantity;
        }
        return [$cost, $increase];
    }

    private function calcularCostoAluminio($material, $quantity)
    {
        $colorParam = $this->parameters['color'] ?? 'Natural';
        $colorId = Color::where('color_name', $colorParam)->value('id');
        $pivot = DB::table('material_color')
            ->where('material_id', $material->id)
            ->where('color_id', $colorId)
            ->where('category_id', $material->category_id)
            ->first();
        $increase = 0;
        $pieceSize = $material->piece_size;
        $unitPrice = $material->unit_price;
        if ($pivot && $pivot->increase_value > 0 && $pieceSize > 0) {
            $ratio = $quantity / $pieceSize;
            $increase = round($pivot->increase_value * $ratio, 2);
        }
        $cost = $unitPrice * $quantity + $increase;
        return [$cost, $increase];
    }

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
        
        $this->calculatePrice();
        
        // Dispatch evento para actualizar modelo 3D
        $parametersFor3D = $this->parameters;
        if ($this->getProductType() === 'window') {
            $parametersFor3D = array_merge($this->parameters, ['depth' => 1.0]);
        }
        
    // Incluir la ruta de textura correspondiente al color seleccionado para el frame
    $parametersFor3D['texturePath'] = $this->getColorTexturePath($this->parameters['color']);
    // Incluir la ruta de textura correspondiente al color seleccionado para el vidrio
    $parametersFor3D['glassTexturePath'] = $this->getGlassTexturePath($this->parameters['glassColor']);
        $this->dispatch('updateModel3D', parameters: $parametersFor3D);
    }

    public function calculatePrice()
    {
        try {
            $this->materialCosts = [];
            $totalCost = 0;

            $area = $this->parameters['width'] * $this->parameters['height'];
            $depth = $this->getProductType() === 'window' ? 1.0 : ($this->parameters['depth'] ?? 1.0);
            $volume = $area * $depth;

            foreach ($this->product->materials as $material) {
                $quantity = $this->calculateMaterialQuantity($material, $area, $volume);
                $cost = 0;
                $increase = 0;
                if (str_contains(strtolower($material->name), 'vidrio')) {
                    list($cost, $increase) = $this->calcularCostoVidrio($material, $quantity);
                } else if ($material->supports_colors) {
                    list($cost, $increase) = $this->calcularCostoAluminio($material, $quantity);
                } else {
                    $cost = $material->unit_price * $quantity;
                }
                $this->materialCosts[] = [
                    'name' => $material->name,
                    'quantity' => $quantity,
                    'unit' => $material->unit_measure,
                    'unit_price' => $material->unit_price,
                    'total_cost' => $cost,
                    'color_increase' => $increase
                ];
                $totalCost += $cost;
            }

            // Obtener porcentaje de costo directo para el producto
            $directCost = DB::table('product_cost_settings')
                ->where('product_id', $this->product->id)
                ->value('direct_cost_percentage');
            $directCost = $directCost !== null ? $directCost : 0;

            // Obtener porcentaje de costo indirecto global
            $indirectCost = DB::table('global_cost_settings')
                ->orderByDesc('id')
                ->value('indirect_cost_percentage');
            $indirectCost = $indirectCost !== null ? $indirectCost : 0;

            // Aplicar costos directos e indirectos
            $directAmount = $totalCost * ($directCost / 100);
            $indirectAmount = $totalCost * ($indirectCost / 100);
            $this->calculatedPrice = $totalCost + $directAmount + $indirectAmount;
        } catch (\Exception $e) {
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

    private function loadAvailableColors()
    {
        // Cargar todos los colores activos
        $this->availableColors = Color::where('is_active', true)
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

    public function getGlassTexturePath($glassColorName)
    {
        $color = $this->availableColors[$glassColorName] ?? null;
        // Si no existe, usar textura por defecto de vidrio
        return $color ? $color->texture_path : '/textures/glass/transparent/';
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
