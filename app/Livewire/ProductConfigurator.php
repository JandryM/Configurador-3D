<?php
namespace App\Livewire;
use App\Models\Product;
use App\Models\Color;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductConfigurator extends Component
{
    public $showProformaModal = false;
    public $currentProformaStatus = null; // 'new' o 'saved'
    public $currentProformaId = null;
    
    public function updatedShowProformaModal($value)
    {
        if ($value) {
            // Cuando se abre el modal, verificar el estado de la configuración actual
            $this->checkCurrentConfigurationStatus();
        }
    }

    public function checkCurrentConfigurationStatus()
    {
        if (!auth()->check()) {
            $this->currentProformaStatus = 'new';
            $this->currentProformaId = null;
            return;
        }

        // Buscar proformas SIN orden asociada con esta configuración exacta
        // (Las proformas con orden ya no son relevantes - cada orden es independiente)
        $existingProformasWithoutOrder = DB::table('proformas')
            ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereNull('orders.id') // Sin orden
            ->where('proformas.user_id', auth()->id())
            ->where('proformas.product_id', $this->product->id)
            ->select('proformas.*')
            ->get();

        $matchingProformaWithoutOrder = null;
        foreach ($existingProformasWithoutOrder as $proforma) {
            // Verificar si la proforma ha expirado
            if (!$proforma->is_expired && now()->greaterThan($proforma->expiration_date)) {
                // Marcar como expirada
                DB::table('proformas')
                    ->where('id', $proforma->id)
                    ->update(['is_expired' => true, 'updated_at' => now()]);
                $proforma->is_expired = true;
            }
            
            // Si la proforma está expirada, ignorarla (no es válida)
            if ($proforma->is_expired) {
                continue;
            }
            
            $proformaConfig = json_decode($proforma->configuration, true);
            $proformaParams = $proformaConfig['parameters'] ?? [];
            
            // Comparar parámetros clave
            $paramsMatch = 
                ($proformaParams['width'] ?? null) == ($this->parameters['width'] ?? null) &&
                ($proformaParams['height'] ?? null) == ($this->parameters['height'] ?? null) &&
                ($proformaParams['depth'] ?? null) == ($this->parameters['depth'] ?? null) &&
                ($proformaParams['color'] ?? null) == ($this->parameters['color'] ?? null) &&
                ($proformaParams['glassColor'] ?? null) == ($this->parameters['glassColor'] ?? null);
            
            if ($paramsMatch) {
                $matchingProformaWithoutOrder = $proforma;
                break;
            }
        }

        // Si encontramos una proforma válida SIN orden
        if ($matchingProformaWithoutOrder) {
            $this->currentProformaStatus = 'saved';
            $this->currentProformaId = $matchingProformaWithoutOrder->id;
            
            // Cargar la cantidad de la proforma guardada
            $this->quantity = $matchingProformaWithoutOrder->quantity ?? 1;
            
            // Recalcular el precio con la cantidad correcta
            $this->calculatePrice();
            
            return;
        }

        // Si no encontramos ninguna proforma válida sin orden, es una configuración nueva
        $this->currentProformaStatus = 'new';
        $this->currentProformaId = null;
    }

    public function downloadProformaPdf()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para descargar la proforma.');
        }

        // Buscar la última proforma guardada con esta configuración
        $proformaData = DB::table('proformas')
            ->where('user_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $product = $this->product;
        $parameters = $this->parameters;
        $quantity = $this->quantity;
        $materialCosts = $this->materialCosts;
        $calculatedPrice = $this->calculatedPrice;
        $notes = $this->parameters['notes'] ?? null;

        $directCost = $this->getDirectCostPercentage();
        $indirectCost = $this->getIndirectCostPercentage();
        $wastePercentage = $this->getWastePercentage();
        $profitMargin = $this->getProfitMarginPercentage();

        $user = auth()->user();
        $isPdf = true; // Indicar que es para PDF

        // Agregar datos de expiración si existe la proforma guardada
        $expiration_date = $proformaData->expiration_date ?? null;
        $is_expired = $proformaData->is_expired ?? null;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('livewire.proformas.proforma', compact('product', 'parameters', 'quantity', 'materialCosts', 'calculatedPrice', 'notes', 'directCost', 'indirectCost', 'wastePercentage', 'profitMargin', 'user', 'isPdf', 'expiration_date', 'is_expired'));
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
        // Actualizar estado después de guardar
        $this->checkCurrentConfigurationStatus();
    }

    public function orderProforma()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para ordenar la proforma.');
        }

        // Guardar proforma (si no existe ya una igual para este usuario/producto/configuración)
        $proformaId = $this->saveProforma(true);

        if (!$proformaId) {
            session()->flash('error', 'No se pudo guardar la proforma.');
            return;
        }

        // Verificar si la proforma está expirada
        if ($this->checkProformaExpiration($proformaId)) {
            session()->flash('error', 'Esta proforma ha expirado. Por favor, genera una nueva configuración.');
            return;
        }

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
        
        // Actualizar estado después de ordenar
        $this->checkCurrentConfigurationStatus();
    }

    public Product $product;
    public $calculatedPrice = 0;
    public $materialCosts = [];
    public $quantity = 1; // Cantidad de unidades
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
            'width' => ['min' => 0.5, 'max' => 4.0, 'step' => 0.001],  // tres decimales
            'height' => ['min' => 0.3, 'max' => 3.5, 'step' => 0.001],  // tres decimales
            'depth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.001], // tres decimales (mm)
            'frameWidth' => ['min' => 0.02, 'max' => 0.1, 'step' => 0.001] // tres decimales (mm)
        ],
        'door' => [
            'width' => ['min' => 0.6, 'max' => 1.2, 'step' => 0.001],  // tres decimales
            'height' => ['min' => 1.8, 'max' => 2.5, 'step' => 0.001],  // tres decimales
            'depth' => ['min' => 0.03, 'max' => 0.1, 'step' => 0.001],
            'frameWidth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.001]
        ],
        'furniture' => [
            'width' => ['min' => 0.3, 'max' => 2.0, 'step' => 0.001],  // tres decimales
            'height' => ['min' => 0.5, 'max' => 2.0, 'step' => 0.001],  // tres decimales
            'depth' => ['min' => 0.3, 'max' => 1.0, 'step' => 0.001]
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
            ? $pivot->increase_value * ($quantity / $pieceSize)
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
        // Resetear estado cuando se modifica la configuración
        $this->currentProformaStatus = 'new';
        $this->currentProformaId = null;

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

    public function updatedQuantity($value)
    {
        // NO resetear estado - permitir que se mantenga saved/ordered
        // para que el usuario pueda actualizar la cantidad en la misma proforma
        
        // Validar que la cantidad sea al menos 1
        if (!is_numeric($value) || $value < 1) {
            $this->quantity = 1;
        } else {
            $this->quantity = (int)$value;
        }
        
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        // Validar ancho y alto antes de calcular
        $width = $this->parameters['width'] ?? null;
        $height = $this->parameters['height'] ?? null;

        if (!is_numeric($width) || !is_numeric($height) || $width <= 0 || $height <= 0) {
            $this->calculatedPrice = 0;
            $this->materialCosts = [];
            return;
        }
        try {
            $this->materialCosts = [];
            $totalCost = 0;

            // Cálculos sin redondeo para mantener precisión
            $area = $width * $height;
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
                    'quantity' => $quantity, // Sin redondeo en cálculos intermedios
                    'unit' => $material->unit_measure,
                    'unit_price' => $material->unit_price,
                    'total_cost' => $cost,
                    'color_increase' => $increase
                ];
                $totalCost += $cost;
            }

            $directCost = $this->getDirectCostPercentage();
            $indirectCost = $this->getIndirectCostPercentage();
            $wastePercentage = $this->getWastePercentage();
            $profitMargin = $this->getProfitMarginPercentage();

            $directAmount = $totalCost * ($directCost / 100);
            $indirectAmount = $totalCost * ($indirectCost / 100);
            $wasteAmount = $totalCost * ($wastePercentage / 100);
            
            // Calcular costo total de producción
            $totalProductionCost = $totalCost + $directAmount + $indirectAmount + $wasteAmount;
            
            // Agregar margen de ganancia sobre el costo total de producción
            $profitAmount = $totalProductionCost * ($profitMargin / 100);
            
            $precisePrice = $totalProductionCost + $profitAmount;
            // Redondeo SOLO al final para el precio mostrado al usuario
            // Multiplicar por la cantidad
            $this->calculatedPrice = round($precisePrice * $this->quantity, 2);
        } catch (\Exception $e) {
            $this->calculatedPrice = $this->product->price ?? 0;
        }
    }
    // Helpers para evitar código duplicado de obtención de porcentajes
    private function getDirectCostPercentage()
    {
        $directCost = \DB::table('product_cost_settings')
            ->where('product_id', $this->product->id)
            ->where('is_active', true)
            ->value('direct_cost_percentage');
        return $directCost !== null ? $directCost : 0;
    }

    private function getIndirectCostPercentage()
    {
        $currentSetting = \App\Models\GlobalCostSetting::current()->first();
        return $currentSetting ? $currentSetting->indirect_cost_percentage : 0;
    }

    private function getWastePercentage()
    {
        $wastePercentage = \DB::table('product_cost_settings')
            ->where('product_id', $this->product->id)
            ->where('is_active', true)
            ->value('waste_percentage');
        return $wastePercentage !== null ? $wastePercentage : 0;
    }

    private function getProfitMarginPercentage()
    {
        $profitMargin = \DB::table('product_cost_settings')
            ->where('product_id', $this->product->id)
            ->where('is_active', true)
            ->value('profit_margin_percentage');
        return $profitMargin !== null ? $profitMargin : 0;
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
        
        // Variables calculadas sin redondeo para mantener máxima precisión
        $area = $width * $height;
        $volume = $area * $depth;
        $perimeter = 2 * ($width + $height);

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
                'quantity' => $this->quantity,
                'calculated_price' => $this->calculatedPrice,
                'material_costs' => $this->materialCosts,
                'directCost' => $this->getDirectCostPercentage(),
                'indirectCost' => $this->getIndirectCostPercentage(),
                'wastePercentage' => $this->getWastePercentage(),
                'profitMargin' => $this->getProfitMarginPercentage(),
                'timestamp' => now()
            ];

            $proformaId = null;
            if (auth()->check()) {
                // Configurar fecha de expiración (15 días)
                $expirationDate = now()->addDays(15);
                
                // Buscar proforma existente con la misma configuración (SOLO las que NO tienen orden)
                $existingProformas = DB::table('proformas')
                    ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
                    ->whereNull('orders.id') // Excluir las que ya tienen una orden
                    ->where('proformas.user_id', auth()->id())
                    ->where('proformas.product_id', $this->product->id)
                    ->select('proformas.*')
                    ->get();
                
                $matchingProforma = null;
                foreach ($existingProformas as $proforma) {
                    // Verificar si la proforma ha expirado
                    if (!$proforma->is_expired && now()->greaterThan($proforma->expiration_date)) {
                        // Marcar como expirada
                        DB::table('proformas')
                            ->where('id', $proforma->id)
                            ->update(['is_expired' => true, 'updated_at' => now()]);
                        $proforma->is_expired = true;
                    }
                    
                    // Si la proforma está expirada, ignorarla (no es válida para actualizar)
                    if ($proforma->is_expired) {
                        continue;
                    }
                    
                    $proformaConfig = json_decode($proforma->configuration, true);
                    $proformaParams = $proformaConfig['parameters'] ?? [];
                    
                    // Comparar parámetros clave (width, height, depth, color, glassColor)
                    $paramsMatch = 
                        ($proformaParams['width'] ?? null) == ($this->parameters['width'] ?? null) &&
                        ($proformaParams['height'] ?? null) == ($this->parameters['height'] ?? null) &&
                        ($proformaParams['depth'] ?? null) == ($this->parameters['depth'] ?? null) &&
                        ($proformaParams['color'] ?? null) == ($this->parameters['color'] ?? null) &&
                        ($proformaParams['glassColor'] ?? null) == ($this->parameters['glassColor'] ?? null);
                    
                    if ($paramsMatch) {
                        $matchingProforma = $proforma;
                        break;
                    }
                }
                
                // Si existe una proforma NO ordenada con la misma configuración
                if ($matchingProforma) {
                    // Actualizar la proforma existente
                    $proformaId = $matchingProforma->id;
                    DB::table('proformas')
                        ->where('id', $proformaId)
                        ->update([
                            'configuration' => json_encode($configuration),
                            'quantity' => $this->quantity,
                            'price' => $this->calculatedPrice,
                            'expiration_date' => $expirationDate,
                            'is_expired' => false, // Renovar estado
                            'updated_at' => now()
                        ]);
                } else {
                    // Crear nueva proforma (no existe ninguna NO ordenada con esta configuración)
                    $lastId = DB::table('proformas')->max('id');
                    $nextNumber = 'PRF-' . str_pad(($lastId + 1), 4, '0', STR_PAD_LEFT);
                    $proformaId = DB::table('proformas')->insertGetId([
                        'number' => $nextNumber,
                        'user_id' => auth()->id(),
                        'product_id' => $this->product->id,
                        'configuration' => json_encode($configuration),
                        'quantity' => $this->quantity,
                        'price' => $this->calculatedPrice,
                        'expiration_date' => $expirationDate,
                        'is_expired' => false,
                        'is_ordered' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
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


    public function checkProformaExpiration($proformaId)
    {
        $proforma = DB::table('proformas')
            ->where('id', $proformaId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$proforma) {
            return false;
        }

        // Verificar si ha expirado
        if (!$proforma->is_expired && now()->greaterThan($proforma->expiration_date)) {
            // Marcar como expirada
            DB::table('proformas')
                ->where('id', $proformaId)
                ->update(['is_expired' => true, 'updated_at' => now()]);
            
            return true;
        }

        return $proforma->is_expired;
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
        $wastePercentage = $this->getWastePercentage();
        $profitMargin = $this->getProfitMarginPercentage();
        $user = auth()->user();
        return view('livewire.products.product-configurator', [
            'productType' => $this->getProductType(),
            'parameterLimits' => $this->limits[$this->getProductType()] ?? [],
            'availableColors' => $this->getAvailableColors(),
            'selectedColor' => $selectedColor,
            'colorTexturePath' => $this->getColorTexturePath($this->parameters['color']),
            'directCost' => $directCost,
            'indirectCost' => $indirectCost,
            'wastePercentage' => $wastePercentage,
            'profitMargin' => $profitMargin,
            'user' => $user
        ])->layout('layouts.guest', [
            'title' => 'Configurador 3D - ' . $this->product->name,
            'description' => 'Personaliza ' . $this->product->name . ' en tiempo real con nuestro configurador 3D interactivo.'
        ]);
    }
}
