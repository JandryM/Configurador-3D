<?php
namespace App\Livewire;
use App\Models\Product;
use App\Models\Color;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductConfigurator extends Component
{
    // Flag para saber si los parámetros son válidos
    public $parametersValid = true;
    public $showProformaModal = false;
    public function downloadProformaPdf()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para descargar la proforma.');
        }
        $product = $this->product;
        $parameters = $this->parameters;
        $materialCosts = $this->materialCosts;
        $calculatedPrice = $this->calculatedPrice;
        $notes = $this->parameters['notes'] ?? null;

        $directCost = $this->getDirectCostPercentage();
        $indirectCost = $this->getIndirectCostPercentage();

        $user = auth()->user();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('proforma', compact('product', 'parameters', 'materialCosts', 'calculatedPrice', 'notes', 'directCost', 'indirectCost', 'user'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'proforma_' . now()->format('Ymd_His') . '.pdf');
    }

    public function guardarProforma()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para guardar la proforma.');
        }
        $this->saveProforma();
    }

    public function orderProforma()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para ordenar la proforma.');
        }
        // Guardar proforma (si no existe ya una igual para este usuario/producto/configuración)
        $proformaId = $this->saveProforma(true); // true = devolver id
        // Generar número/código único de orden (ej: ORD-0001)
        $lastOrderId = DB::table('orders')->max('id');
        $nextOrderNumber = 'ORD-' . str_pad(($lastOrderId + 1), 4, '0', STR_PAD_LEFT);
        // Crear la orden asociada
        DB::table('orders')->insert([
            'proforma_id' => $proformaId,
            'number' => $nextOrderNumber,
            'status' => 'pending',
            'product_created_at' => now(),
            'estimated_finish_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // Marcar la proforma como ordenada
        DB::table('proformas')->where('id', $proformaId)->update(['is_ordered' => true]);
        session()->flash('message', '¡Orden creada exitosamente!');
    }
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
            'width' => ['min' => 0.5, 'max' => 4.0, 'step' => 0.01],  // dos decimales (cm)
            'height' => ['min' => 0.3, 'max' => 3.5, 'step' => 0.01],  // dos decimales (cm)
            'depth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.001], // tres decimales (mm)
            'frameWidth' => ['min' => 0.02, 'max' => 0.1, 'step' => 0.001] // tres decimales (mm)
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
        $increase = $pivot->increase_value ?? 0;
        $pieceSize = $material->piece_size;
        $piecePrice = $material->piece_price;
        $precioVidrioFinal = $piecePrice + $increase;
        $cost = $pieceSize > 0 ? $precioVidrioFinal * ($quantity / $pieceSize) : $precioVidrioFinal * $quantity;
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
        $pieceSize = $material->piece_size;
        $unitPrice = $material->unit_price;
        $increase = ($pivot && $pivot->increase_value > 0 && $pieceSize > 0)
            ? round($pivot->increase_value * ($quantity / $pieceSize), 2)
            : 0;
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

    public function updatedParameters($value, $key)
    {
        $this->updateParameter($key, $value);
    }

    public function updateParameter($parameter, $value)
    {

        // Si el valor es string vacío, convertir a null
        if ($value === '') {
            $value = null;
        }

        // Si es un parámetro numérico, forzar null si no es numérico
        if (in_array($parameter, ['width', 'height', 'depth', 'frameWidth'])) {
            if (!is_numeric($value) || $value === null) {
                $this->parameters[$parameter] = null;
                $this->calculatePrice();
                $parametersFor3D = $this->parameters;
                if ($this->getProductType() === 'window') {
                    $parametersFor3D = array_merge($this->parameters, ['depth' => 1.0]);
                }
                $parametersFor3D['texturePath'] = $this->getColorTexturePath($this->parameters['color']);
                $parametersFor3D['glassTexturePath'] = $this->getGlassTexturePath($this->parameters['glassColor']);
                $this->dispatch('updateModel3D', parameters: $parametersFor3D);
                return;
            }
        }

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
        $parametersFor3D['texturePath'] = $this->getColorTexturePath($this->parameters['color']);
        $parametersFor3D['glassTexturePath'] = $this->getGlassTexturePath($this->parameters['glassColor']);
        $this->dispatch('updateModel3D', parameters: $parametersFor3D);
    }

    public function calculatePrice()
    {
        // Validar ancho y alto antes de calcular
        $width = $this->parameters['width'] ?? null;
        $height = $this->parameters['height'] ?? null;
        $this->parametersValid = is_numeric($width) && is_numeric($height) && $width > 0 && $height > 0;

        if (!$this->parametersValid) {
            $this->calculatedPrice = 0;
            $this->materialCosts = [];
            return;
        }
        try {
            $this->materialCosts = [];
            $totalCost = 0;

            $area = round($width * $height, 3); // mantener 3 decimales para m2
            $depth = $this->getProductType() === 'window' ? 1.0 : ($this->parameters['depth'] ?? 1.0);
            $volume = round($area * $depth, 3); // mantener 3 decimales para m3

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
                    'quantity' => round($quantity, 4), // redondear a 4 decimales para calculo de cada material
                    'unit' => $material->unit_measure,
                    'unit_price' => $material->unit_price,
                    'total_cost' => $cost,
                    'color_increase' => $increase
                ];
                $totalCost += $cost;
            }

            $directCost = $this->getDirectCostPercentage();
            $indirectCost = $this->getIndirectCostPercentage();

            $directAmount = $totalCost * ($directCost / 100);
            $indirectAmount = $totalCost * ($indirectCost / 100);

            $precisePrice = $totalCost + $directAmount + $indirectAmount;
            $this->calculatedPrice = round($precisePrice, 2); // dos decimales al precio final
        } catch (\Exception $e) {
            $this->calculatedPrice = $this->product->price ?? 0;
        }
    }
    // Helpers para evitar código duplicado de obtención de porcentajes
    private function getDirectCostPercentage()
    {
        $directCost = \DB::table('product_cost_settings')
            ->where('product_id', $this->product->id)
            ->value('direct_cost_percentage');
        return $directCost !== null ? $directCost : 0;
    }

    private function getIndirectCostPercentage()
    {
        $indirectCost = \DB::table('global_cost_settings')
            ->orderByDesc('id')
            ->value('indirect_cost_percentage');
        return $indirectCost !== null ? $indirectCost : 0;
    }

    private function calculateMaterialQuantity($material, $area, $volume)
    {
        // Usar la fórmula de la tabla pivote si existe
        $pivot = $material->pivot ?? null;
        $formula = $pivot->calculation_formula ?? null;
        
        if ($formula) {
            return $this->evaluateFormulaSafely($formula, $this->parameters);
        }
        
        // Si no hay fórmula, usar área o volumen según corresponda
        return $material->has_dimensions ? $area : $volume;
    }

    private function evaluateFormulaSafely($formula, $parameters)
    {
        // Extraer parámetros con la precisión original
        $width = $parameters['width'] ?? 0;
        $height = $parameters['height'] ?? 0;
        $depth = $parameters['depth'] ?? 0;
        $frameWidth = $parameters['frameWidth'] ?? 0.05;
        
        // Variables calculadas - mantener alta precisión
        $area = round($width * $height, 3); // mantener 3 decimales para m2
        $volume = round($area * $depth, 4); // mantener 4 decimales para m3
        $perimeter = round(2 * ($width + $height), 2); // mantener 2 decimales para metros lineales

        // Reemplazar variables
        $safeFormula = str_replace(
            [
                '{width}', '{height}', '{depth}', '{frameWidth}',
                '{area}', '{volume}', '{perimeter}'
            ],
            [
                $width, $height, $depth, $frameWidth,
                $area, $volume, $perimeter
            ],
            $formula
        );
        
        try {
            $result = eval("return $safeFormula;");
            return is_numeric($result) ? (float)$result : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function saveProforma($returnId = false)
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

            $proformaId = null;
            if (auth()->check()) {
                // Siempre crear una nueva proforma, sin buscar duplicados
                $lastId = DB::table('proformas')->max('id');
                $nextNumber = 'PRF-' . str_pad(($lastId + 1), 4, '0', STR_PAD_LEFT);
                $proformaId = DB::table('proformas')->insertGetId([
                    'number' => $nextNumber,
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

            if ($returnId) {
                return $proformaId;
            }
            session()->flash('message', '¡Configuración guardada exitosamente!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la configuración: ' . $e->getMessage());
            if ($returnId) return null;
        }
    }

    public function requestQuote()
    {
        // Redirigir a formulario de cotización con la configuración (sin guardar aquí)
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
        $directCost = $this->getDirectCostPercentage();
        $indirectCost = $this->getIndirectCostPercentage();
        $user = auth()->user();
        return view('livewire.products.product-configurator', [
            'productType' => $this->getProductType(),
            'parameterLimits' => $this->limits[$this->getProductType()] ?? [],
            'availableColors' => $this->getAvailableColors(),
            'selectedColor' => $selectedColor,
            'colorTexturePath' => $this->getColorTexturePath($this->parameters['color']),
            'directCost' => $directCost,
            'indirectCost' => $indirectCost,
            'user' => $user,
            'parametersValid' => $this->parametersValid
        ])->layout('layouts.guest', [
            'title' => 'Configurador 3D - ' . $this->product->name,
            'description' => 'Personaliza ' . $this->product->name . ' en tiempo real con nuestro configurador 3D interactivo.'
        ]);
    }
}
