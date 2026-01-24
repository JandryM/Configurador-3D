<?php

namespace App\Livewire;
use App\Models\Product;
use App\Models\Color;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ProductConfigurator extends Component
{
    // Para advertencia de actualización de costos
    public $showCostUpdateWarning = false;
    public $proformaIdPendingCostUpdate = null;
    public $pendingConfiguration = null;
    public $pendingItemId = null; // ID del ítem existente que se va a actualizar
    public $showProformaModal = false;
    public $currentProformaStatus = null; // 'new' o 'saved'
    public $currentProformaId = null;
    public $showProformaSelectorModal = false; // Modal separado para selector
    public $availableProformas = []; // Lista de proformas disponibles
    public $selectedProformaToAdd = null; // Proforma seleccionada para agregar
    public $showCreateConfirmModal = false; // Modal de confirmación para crear nueva proforma
    public $showOrderConfirmModal = false; // Modal de confirmación para ordenar proforma

    public function updatedShowProformaModal($value)
    {
        if ($value) {
            // Cuando se abre el modal, verificar el estado de la configuración actual
            $this->checkCurrentConfigurationStatus();
            // Cargar proformas disponibles
            $this->loadAvailableProformas();
        }
    }

    /**
     * Query builder para obtener proformas activas (no ordenadas, no expiradas y no desactivadas) del usuario autenticado
     */
    private function getActiveProformasQuery()
    {
        return DB::table('proformas')
            ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereNull('orders.id')
            ->where('proformas.user_id', auth()->id())
            ->where('proformas.is_expired', false)
            ->where('proformas.is_active', true)
            ->where('proformas.expiration_date', '>', now())
            ->select('proformas.*');
    }

    /**
     * Cargar todas las proformas disponibles del usuario (no ordenadas, no expiradas y no desactivadas)
     */
    public function loadAvailableProformas()
    {
        if (!auth()->check()) {
            $this->availableProformas = [];
            return;
        }

        $proformas = $this->getActiveProformasQuery()
            ->orderBy('proformas.created_at', 'desc')
            ->get();

        $this->availableProformas = $proformas->map(function ($proforma) {
            $itemsCount = DB::table('proforma_items')
                ->where('proforma_id', $proforma->id)
                ->count();

            return [
                'id' => $proforma->id,
                'number' => $proforma->number,
                'total_price' => $proforma->total_price,
                'items_count' => $itemsCount,
                'created_at' => $proforma->created_at,
            ];
        })->toArray();
    }

    public function openProformaSelectorModal()
    {
        $this->loadAvailableProformas();
        $this->selectedProformaToAdd = null;
        $this->showProformaSelectorModal = true;
    }

    public function closeProformaSelectorModal()
    {
        $this->showProformaSelectorModal = false;
        $this->selectedProformaToAdd = null;
    }

    public function checkCurrentConfigurationStatus()
    {
        if (!auth()->check()) {
            $this->currentProformaStatus = 'new';
            $this->currentProformaId = null;
            return;
        }

        // Buscar en TODAS las proformas activas si existe esta configuración
        $activeProformas = $this->getActiveProformasQuery()->get();

        foreach ($activeProformas as $proforma) {
            // Buscar ítems activos de esta proforma que coincidan con el producto y configuración actual
            $existingItems = DB::table('proforma_items')
                ->where('proforma_id', $proforma->id)
                ->where('product_id', $this->product->id)
                ->where('is_active', true)
                ->get();

            $productType = $this->getProductType();
            foreach ($existingItems as $item) {
                $itemConfig = json_decode($item->configuration, true);
                $itemParams = $itemConfig['parameters'] ?? [];
                // Comparar parámetros clave
                $paramsMatch =
                    ($itemParams['width'] ?? null) == ($this->parameters['width'] ?? null) &&
                    ($itemParams['height'] ?? null) == ($this->parameters['height'] ?? null) &&
                    ($itemParams['depth'] ?? null) == ($this->parameters['depth'] ?? null) &&
                    ($itemParams['color'] ?? null) == ($this->parameters['color'] ?? null);
                // Solo comparar glassColor si es window o door
                if (in_array($productType, ['window', 'door'])) {
                    $paramsMatch = $paramsMatch && (($itemParams['glassColor'] ?? null) == ($this->parameters['glassColor'] ?? null));
                }
                if ($paramsMatch) {
                    // Encontramos la configuración en esta proforma
                    $this->currentProformaStatus = 'saved';
                    $this->currentProformaId = $proforma->id;
                    $this->quantity = $item->quantity ?? 1;
                    $this->calculatePrice();
                    return;
                }
            }
        }

        // Si no encontramos la configuración en ninguna proforma, es nueva
        $this->currentProformaStatus = 'new';
        $this->currentProformaId = null;
    }

    /**
     * Obtener los items de una proforma con toda su información completa
     */
    private function getProformaItemsForPdf($proformaId)
    {
        return DB::table('proforma_items')
            ->where('proforma_id', $proformaId)
            ->get()
            ->map(function ($item) {
                $product = \App\Models\Product::find($item->product_id);
                $config = json_decode($item->configuration, true) ?? [];
                $parameters = $config['parameters'] ?? [];
                $materialCosts = $config['material_costs'] ?? [];

                // Obtener costos si no están en la configuración
                $directCost = $config['directCost'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $item->product_id)
                    ->value('direct_cost_percentage') ?? 0;

                $currentIndirectSetting = \App\Models\GlobalCostSetting::current()->first();
                $indirectCost = $config['indirectCost'] ?? ($currentIndirectSetting ? $currentIndirectSetting->indirect_cost_percentage : 0);

                $wastePercentage = $config['wastePercentage'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $item->product_id)
                    ->value('waste_percentage') ?? 0;

                $profitMargin = $config['profitMargin'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $item->product_id)
                    ->value('profit_margin_percentage') ?? 0;

                return [
                    'id' => $item->id,
                    'product' => $product,
                    'product_name' => $product ? $product->name : 'Producto eliminado',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'notes' => $item->notes,
                    'parameters' => $parameters,
                    'materialCosts' => $materialCosts,
                    'directCost' => $directCost,
                    'indirectCost' => $indirectCost,
                    'wastePercentage' => $wastePercentage,
                    'profitMargin' => $profitMargin,
                ];
            })
            ->toArray();
    }

    public function downloadProformaPdf($proformaId = null)
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para descargar la proforma.');
        }

        // Si se pasa un ID, buscar esa proforma específica
        if ($proformaId) {
            $proforma = DB::table('proformas')
                ->where('id', $proformaId)
                ->where('user_id', auth()->id())
                ->first();
        } else {
            // Si no, buscar la proforma activa del usuario
            $proforma = $this->getOrCreateActiveProforma(false);
        }

        if (!$proforma) {
            session()->flash('error', 'No tienes ninguna proforma guardada.');
            return;
        }

        $items = $this->getProformaItemsForPdf($proforma->id);

        if (empty($items)) {
            session()->flash('error', 'La proforma está vacía.');
            return;
        }

        $user = auth()->user();
        $total_price = $proforma->total_price;
        $number = $proforma->number;
        $created_at = $proforma->created_at;
        $expiration_date = $proforma->expiration_date;
        $is_expired = $proforma->is_expired;
        $isPdf = true;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('livewire.proformas.proforma', compact(
            'items',
            'user',
            'total_price',
            'number',
            'created_at',
            'expiration_date',
            'is_expired',
            'isPdf'
        ));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'proforma_' . $number . '_' . now()->format('Ymd_His') . '.pdf');
    }

    public function guardarProforma()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para guardar la proforma.');
        }
        $this->saveProforma();
        // Actualizar estado después de guardar
        $this->checkCurrentConfigurationStatus();
        $this->loadAvailableProformas();
    }

    /**
     * Guardar en una proforma específica seleccionada por el usuario
     */
    public function guardarEnProformaSeleccionada()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para guardar la proforma.');
        }

        if (!$this->selectedProformaToAdd) {
            session()->flash('error', 'Debes seleccionar una proforma.');
            return;
        }

        // Verificar si hay desactualización de costos
        if ($this->proformaNeedsCostUpdate($this->selectedProformaToAdd)) {
            $this->showCostUpdateWarning = true;
            $this->proformaIdPendingCostUpdate = $this->selectedProformaToAdd;
            $this->pendingConfiguration = $this->buildConfiguration();
            return;
        }

        $this->saveProformaToSpecific($this->selectedProformaToAdd);
        $this->selectedProformaToAdd = null;
        $this->showProformaSelectorModal = false;
        $this->checkCurrentConfigurationStatus();
        $this->loadAvailableProformas();
    }

    /**
     * Detectar si los costos actuales difieren de los almacenados en los ítems de la proforma
     */
    private function proformaNeedsCostUpdate($proformaId)
    {
        $items = DB::table('proforma_items')->where('proforma_id', $proformaId)->get();
        $currentDirect = $this->getDirectCostPercentage();
        $currentIndirect = $this->getIndirectCostPercentage();
        $currentWaste = $this->getWastePercentage();
        $currentProfit = $this->getProfitMarginPercentage();
        foreach ($items as $item) {
            $config = json_decode($item->configuration, true);
            if (
                ($config['directCost'] ?? null) != $currentDirect ||
                ($config['indirectCost'] ?? null) != $currentIndirect ||
                ($config['wastePercentage'] ?? null) != $currentWaste ||
                ($config['profitMargin'] ?? null) != $currentProfit
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verificar si un ítem específico necesita actualización de costos
     */
    private function itemNeedsCostUpdate($item)
    {
        $config = json_decode($item->configuration, true);
        $currentDirect = $this->getDirectCostPercentage();
        $currentIndirect = $this->getIndirectCostPercentage();
        $currentWaste = $this->getWastePercentage();
        $currentProfit = $this->getProfitMarginPercentage();
        
        return (
            ($config['directCost'] ?? null) != $currentDirect ||
            ($config['indirectCost'] ?? null) != $currentIndirect ||
            ($config['wastePercentage'] ?? null) != $currentWaste ||
            ($config['profitMargin'] ?? null) != $currentProfit
        );
    }

    /**
     * Confirmar actualización de costos y agregar el producto
     */
    public function confirmarActualizarCostosYAgregar()
    {
        if (!$this->proformaIdPendingCostUpdate || !$this->pendingConfiguration) {
            $this->showCostUpdateWarning = false;
            return;
        }
        
        // Obtener la proforma antigua
        $oldProforma = DB::table('proformas')->where('id', $this->proformaIdPendingCostUpdate)->first();
        
        // Desactivar la proforma antigua
        DB::table('proformas')->where('id', $this->proformaIdPendingCostUpdate)->update([
            'is_active' => false,
            'updated_at' => now()
        ]);
        
        // Crear nueva proforma con número actualizado
        $nextNumber = $this->generateNextProformaNumber();
        $newProformaId = DB::table('proformas')->insertGetId([
            'number' => $nextNumber,
            'user_id' => auth()->id(),
            'total_price' => 0,
            'expiration_date' => now()->addDays(30),
            'is_expired' => false,
            'is_ordered' => false,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Copiar todos los ítems existentes con los nuevos costos actualizados
        $items = DB::table('proforma_items')
            ->where('proforma_id', $this->proformaIdPendingCostUpdate)
            ->where('is_active', true)
            ->get();
            
        foreach ($items as $item) {
            $config = json_decode($item->configuration, true);
            $config['directCost'] = $this->getDirectCostPercentage();
            $config['indirectCost'] = $this->getIndirectCostPercentage();
            $config['wastePercentage'] = $this->getWastePercentage();
            $config['profitMargin'] = $this->getProfitMarginPercentage();
            
            // Recalcular precio y snapshot de costos
            $newPrice = $this->recalcularPrecioItem($item->product_id, $config, $item->quantity);
            $costSnapshot = $this->recalcularCostSnapshot($config, $item->quantity);
            
            // Insertar ítem en la nueva proforma
            DB::table('proforma_items')->insert([
                'proforma_id' => $newProformaId,
                'product_id' => $item->product_id,
                'configuration' => json_encode($config),
                'quantity' => $item->quantity,
                'price' => $newPrice,
                'notes' => $item->notes,
                'is_active' => true,
                'material_cost' => $costSnapshot['material_cost'],
                'direct_cost' => $costSnapshot['direct_cost'],
                'indirect_cost' => $costSnapshot['indirect_cost'],
                'waste_cost' => $costSnapshot['waste_cost'],
                'profit_amount' => $costSnapshot['profit_amount'],
                'total_cost' => $costSnapshot['total_cost'],
                'profit_margin_percentage' => $costSnapshot['profit_margin_percentage'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Agregar el nuevo producto/configuración a la nueva proforma
        $this->createProformaItem($newProformaId, $this->pendingConfiguration);
        $this->updateProformaTotalPrice($newProformaId);
        
        $newProforma = DB::table('proformas')->where('id', $newProformaId)->first();
        session()->flash('message', '¡Nueva proforma ' . $newProforma->number . ' creada con costos actualizados!');
        
        $this->showCostUpdateWarning = false;
        $this->proformaIdPendingCostUpdate = null;
        $this->pendingConfiguration = null;
        $this->selectedProformaToAdd = null;
        $this->showProformaSelectorModal = false;
        $this->checkCurrentConfigurationStatus();
        $this->loadAvailableProformas();
    }

    /**
     * Cancelar la advertencia de actualización de costos
     */
    public function cancelarActualizarCostosYAgregar()
    {
        $this->showCostUpdateWarning = false;
        $this->proformaIdPendingCostUpdate = null;
        $this->pendingConfiguration = null;
        $this->pendingItemId = null;
    }

    /**
     * Confirmar actualización de ítem existente con nuevos costos
     */
    public function confirmarActualizarItemConNuevosCostos()
    {
        if (!$this->proformaIdPendingCostUpdate || !$this->pendingConfiguration || !$this->pendingItemId) {
            $this->showCostUpdateWarning = false;
            return;
        }
        
        // Obtener la proforma antigua
        $oldProforma = DB::table('proformas')->where('id', $this->proformaIdPendingCostUpdate)->first();
        
        // Desactivar la proforma antigua
        DB::table('proformas')->where('id', $this->proformaIdPendingCostUpdate)->update([
            'is_active' => false,
            'updated_at' => now()
        ]);
        
        // Crear nueva proforma con número actualizado
        $nextNumber = $this->generateNextProformaNumber();
        $newProformaId = DB::table('proformas')->insertGetId([
            'number' => $nextNumber,
            'user_id' => auth()->id(),
            'total_price' => 0,
            'expiration_date' => now()->addDays(30),
            'is_expired' => false,
            'is_ordered' => false,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Copiar todos los ítems existentes con los nuevos costos actualizados
        $items = DB::table('proforma_items')
            ->where('proforma_id', $this->proformaIdPendingCostUpdate)
            ->where('is_active', true)
            ->get();
            
        foreach ($items as $item) {
            $config = json_decode($item->configuration, true);
            $config['directCost'] = $this->getDirectCostPercentage();
            $config['indirectCost'] = $this->getIndirectCostPercentage();
            $config['wastePercentage'] = $this->getWastePercentage();
            $config['profitMargin'] = $this->getProfitMarginPercentage();
            
            // Si es el ítem que se está actualizando, usar la nueva configuración (cantidad actualizada)
            if ($item->id == $this->pendingItemId) {
                $config = $this->pendingConfiguration;
                $quantity = $this->quantity;
            } else {
                $quantity = $item->quantity;
            }
            
            // Recalcular precio y snapshot de costos
            $newPrice = $this->recalcularPrecioItem($item->product_id, $config, $quantity);
            $costSnapshot = $this->recalcularCostSnapshot($config, $quantity);
            
            // Insertar ítem en la nueva proforma
            DB::table('proforma_items')->insert([
                'proforma_id' => $newProformaId,
                'product_id' => $item->product_id,
                'configuration' => json_encode($config),
                'quantity' => $quantity,
                'price' => $newPrice,
                'notes' => $item->notes,
                'is_active' => true,
                'material_cost' => $costSnapshot['material_cost'],
                'direct_cost' => $costSnapshot['direct_cost'],
                'indirect_cost' => $costSnapshot['indirect_cost'],
                'waste_cost' => $costSnapshot['waste_cost'],
                'profit_amount' => $costSnapshot['profit_amount'],
                'total_cost' => $costSnapshot['total_cost'],
                'profit_margin_percentage' => $costSnapshot['profit_margin_percentage'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $this->updateProformaTotalPrice($newProformaId);
        
        $newProforma = DB::table('proformas')->where('id', $newProformaId)->first();
        session()->flash('message', '¡Nueva proforma ' . $newProforma->number . ' creada con costos actualizados!');
        
        $this->showCostUpdateWarning = false;
        $this->proformaIdPendingCostUpdate = null;
        $this->pendingConfiguration = null;
        $this->pendingItemId = null;
        $this->checkCurrentConfigurationStatus();
        $this->loadAvailableProformas();
    }

    /**
     * Recalcular el precio de un ítem con la configuración y cantidad dadas
     */
    private function recalcularPrecioItem($productId, $config, $quantity)
    {
        $product = Product::find($productId);
        if (!$product)
            return 0;
        // Simular cálculo de precio usando la configuración y cantidad
        // Puedes adaptar esto según tu lógica de cálculo real
        $basePrice = $product->price ?? 0;
        // Si tienes lógica más precisa, úsala aquí
        // Por ahora, solo recalcula con los porcentajes
        $materialCosts = $config['material_costs'] ?? [];
        $totalCost = 0;
        foreach ($materialCosts as $mat) {
            $totalCost += $mat['total_cost'] ?? 0;
        }
        $directAmount = $totalCost * (($config['directCost'] ?? 0) / 100);
        $indirectAmount = $totalCost * (($config['indirectCost'] ?? 0) / 100);
        $wasteAmount = $totalCost * (($config['wastePercentage'] ?? 0) / 100);
        $totalProductionCost = $totalCost + $directAmount + $indirectAmount + $wasteAmount;
        $profitAmount = $totalProductionCost * (($config['profitMargin'] ?? 0) / 100);
        $precisePrice = $totalProductionCost + $profitAmount;
        // Multiplicar por cantidad y agregar IVA del 15%
        $priceWithIVA = ($precisePrice * $quantity) * 1.15;
        return round($priceWithIVA, 2);
    }
    
    /**
     * Recalcular snapshot de costos desde una configuración existente
     */
    private function recalcularCostSnapshot($config, $quantity)
    {
        // Calcular costo total de materiales desde la configuración
        $materialCosts = $config['material_costs'] ?? [];
        $materialCost = 0;
        foreach ($materialCosts as $mat) {
            $materialCost += $mat['total_cost'] ?? 0;
        }
        
        // Obtener porcentajes de la configuración
        $directCostPercentage = $config['directCost'] ?? 0;
        $indirectCostPercentage = $config['indirectCost'] ?? 0;
        $wastePercentage = $config['wastePercentage'] ?? 0;
        $profitMarginPercentage = $config['profitMargin'] ?? 0;
        
        // Calcular montos
        $directCost = $materialCost * ($directCostPercentage / 100);
        $indirectCost = $materialCost * ($indirectCostPercentage / 100);
        $wasteCost = $materialCost * ($wastePercentage / 100);
        
        // Costo total de producción (sin ganancia)
        $totalCost = $materialCost + $directCost + $indirectCost + $wasteCost;
        
        // Margen de ganancia sobre el costo total
        $profitAmount = $totalCost * ($profitMarginPercentage / 100);
        
        // Multiplicar por cantidad
        return [
            'material_cost' => round($materialCost * $quantity, 2),
            'direct_cost' => round($directCost * $quantity, 2),
            'indirect_cost' => round($indirectCost * $quantity, 2),
            'waste_cost' => round($wasteCost * $quantity, 2),
            'profit_amount' => round($profitAmount * $quantity, 2),
            'total_cost' => round($totalCost * $quantity, 2),
            'profit_margin_percentage' => round($profitMarginPercentage, 2),
        ];
    }

    /**
     * Crear una nueva proforma con esta configuración
     */
    public function crearNuevaProforma()
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado. Debes iniciar sesión para crear la proforma.');
        }

        $this->saveProformaToNew();
        $this->checkCurrentConfigurationStatus();
        $this->loadAvailableProformas();
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

        // Verificar si la proforma está activa
        $proforma = DB::table('proformas')->where('id', $proformaId)->first();
        if (!$proforma || !$proforma->is_active) {
            session()->flash('error', 'La proforma no está activa. No se puede ordenar.');
            return;
        }

        // Verificar que tenga al menos un item activo
        $activeItemsCount = DB::table('proforma_items')
            ->where('proforma_id', $proformaId)
            ->where('is_active', true)
            ->count();
        if ($activeItemsCount === 0) {
            session()->flash('error', 'La proforma no tiene configuraciones activas. No se puede ordenar.');
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
            'product_created_at' => null,
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
            'width' => ['min' => 0.8, 'max' => 2, 'step' => 0.001],  // tres decimales
            'height' => ['min' => 0.8, 'max' => 2, 'step' => 0.001],  // tres decimales
            'depth' => ['min' => 0.05, 'max' => 0.15, 'step' => 0.001], // tres decimales (mm)
            'frameWidth' => ['min' => 0.02, 'max' => 0.1, 'step' => 0.001] // tres decimales (mm)
        ],
        'door' => [
            'width' => ['min' => 2, 'max' => 3, 'step' => 0.001],  // tres decimales 
            'height' => ['min' => 2, 'max' => 3, 'step' => 0.001],  // tres decimales 
            'depth' => ['min' => 0.03, 'max' => 0.1, 'step' => 0.001],
            'frameWidth' => ['min' => 0.06, 'max' => 0.15, 'step' => 0.001]
        ],
        'mesh' => [
            'width' => ['min' => 0.8, 'max' => 3, 'step' => 0.001],
            'height' => ['min' => 0.8, 'max' => 3, 'step' => 0.001],
            'depth' => ['min' => 0.01, 'max' => 0.05, 'step' => 0.001]
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
        // Verificar que el producto permita personalización
        if (!$product->allows_customization) {
            session()->flash('error', 'Este producto no está disponible para personalización.');
            return redirect()->route('home');
        }

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
        $productName = strtolower($this->product->name ?? '');

        // Usar la subcategoría directamente si existe
        if ($categoryName === 'window') {
            return 'window';
        } elseif ($categoryName === 'door') {
            return 'door';
        } elseif ($categoryName === 'mesh') {
            return 'mesh';
        }

        // Fallback: detectar por nombre del producto si no hay categoría
        if (str_contains($productName, 'malla') || str_contains($productName, 'mesh') || str_contains($productName, 'mosquito')) {
            return 'mesh';
        }
        if (str_contains($productName, 'puerta') || str_contains($productName, 'portón') || str_contains($productName, 'door')) {
            return 'door';
        }
        if (str_contains($categoryName, 'window') || str_contains($categoryName, 'ventana') || str_contains($categoryName, 'vidrio') || str_contains($productName, 'ventana')) {
            return 'window';
        } elseif (str_contains($categoryName, 'melamina') || str_contains($productName, 'closet')) {
            return 'closet';
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
            $value = max($limit['min'], min($limit['max'], (float) $value));
        }

        $this->parameters[$parameter] = $value;
        $this->calculatePrice();
        // Dispatch evento para actualizar modelo 3D
        $parametersFor3D = $this->parameters;
        if ($this->getProductType() === 'window') {
            $parametersFor3D = array_merge($this->parameters, ['depth' => 1.0]);
        }
        $productType = $this->getProductType();
        $parametersFor3D['texturePath'] = $this->getColorTexturePath($this->parameters['color']);
        if ($productType === 'closet' && (!$parametersFor3D['texturePath'] || str_contains($parametersFor3D['texturePath'], 'aluminum'))) {
            $parametersFor3D['texturePath'] = '/textures/melamina/natural/';
        }
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
            $this->quantity = (int) $value;
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
            // Multiplicar por la cantidad
            $priceWithQuantity = $precisePrice * $this->quantity;
            // Agregar IVA del 15%
            $priceWithIVA = $priceWithQuantity * 1.15;
            // Redondeo SOLO al final para el precio mostrado al usuario
            $this->calculatedPrice = round($priceWithIVA, 2);
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
                '{width}',
                '{height}',
                '{depth}',
                '{frameWidth}',
                '{area}',
                '{volume}',
                '{perimeter}'
            ],
            [
                $width,
                $height,
                $depth,
                $frameWidth,
                $area,
                $volume,
                $perimeter
            ],
            $formula
        );

        try {
            $result = eval ("return $safeFormula;");
            return is_numeric($result) ? (float) $result : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Construir el array de configuración del producto actual
     */
    private function buildConfiguration()
    {
        $parameters = $this->parameters;
        $productType = $this->getProductType();
        // Solo incluir glassColor si es ventana o puerta
        if (!in_array($productType, ['window', 'door'])) {
            unset($parameters['glassColor']);
        }
        return [
            'product_id' => $this->product->id,
            'parameters' => $parameters,
            'quantity' => $this->quantity,
            'calculated_price' => $this->calculatedPrice,
            'material_costs' => $this->materialCosts,
            'directCost' => $this->getDirectCostPercentage(),
            'indirectCost' => $this->getIndirectCostPercentage(),
            'wastePercentage' => $this->getWastePercentage(),
            'profitMargin' => $this->getProfitMarginPercentage(),
            'timestamp' => now()
        ];
    }

    /**
     * Generar el siguiente número de proforma
     */
    private function generateNextProformaNumber()
    {
        $lastId = DB::table('proformas')->max('id');
        return 'PRF-' . str_pad(($lastId + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Crear un ítem de proforma en la base de datos
     */
    private function createProformaItem($proformaId, $configuration)
    {
        // Calcular snapshot de costos
        $costSnapshot = $this->calculateCostSnapshot();
        
        DB::table('proforma_items')->insert([
            'proforma_id' => $proformaId,
            'product_id' => $this->product->id,
            'configuration' => json_encode($configuration),
            'quantity' => $this->quantity,
            'price' => $this->calculatedPrice,
            'notes' => $this->parameters['notes'] ?? null,
            'is_active' => true,
            // Snapshot de costos
            'material_cost' => $costSnapshot['material_cost'],
            'direct_cost' => $costSnapshot['direct_cost'],
            'indirect_cost' => $costSnapshot['indirect_cost'],
            'waste_cost' => $costSnapshot['waste_cost'],
            'profit_amount' => $costSnapshot['profit_amount'],
            'total_cost' => $costSnapshot['total_cost'],
            'profit_margin_percentage' => $costSnapshot['profit_margin_percentage'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    /**
     * Calcular snapshot de costos para guardar en ProformaItem
     */
    private function calculateCostSnapshot()
    {
        // Calcular costo total de materiales
        $materialCost = 0;
        foreach ($this->materialCosts as $material) {
            $materialCost += $material['total_cost'];
        }
        
        // Obtener porcentajes actuales
        $directCostPercentage = $this->getDirectCostPercentage();
        $indirectCostPercentage = $this->getIndirectCostPercentage();
        $wastePercentage = $this->getWastePercentage();
        $profitMarginPercentage = $this->getProfitMarginPercentage();
        
        // Calcular montos
        $directCost = $materialCost * ($directCostPercentage / 100);
        $indirectCost = $materialCost * ($indirectCostPercentage / 100);
        $wasteCost = $materialCost * ($wastePercentage / 100);
        
        // Costo total de producción (sin ganancia)
        $totalCost = $materialCost + $directCost + $indirectCost + $wasteCost;
        
        // Margen de ganancia sobre el costo total
        $profitAmount = $totalCost * ($profitMarginPercentage / 100);
        
        // Multiplicar por cantidad
        return [
            'material_cost' => round($materialCost * $this->quantity, 2),
            'direct_cost' => round($directCost * $this->quantity, 2),
            'indirect_cost' => round($indirectCost * $this->quantity, 2),
            'waste_cost' => round($wasteCost * $this->quantity, 2),
            'profit_amount' => round($profitAmount * $this->quantity, 2),
            'total_cost' => round($totalCost * $this->quantity, 2),
            'profit_margin_percentage' => round($profitMarginPercentage, 2),
        ];
    }

    /**
     * Verificar si existe un ítem con la misma configuración
     */
    private function findMatchingItem($proformaId)
    {
        $existingItems = DB::table('proforma_items')
            ->where('proforma_id', $proformaId)
            ->where('product_id', $this->product->id)
            ->where('is_active', true)
            ->get();

        $productType = $this->getProductType();
        foreach ($existingItems as $item) {
            $itemConfig = json_decode($item->configuration, true);
            $itemParams = $itemConfig['parameters'] ?? [];
            $paramsMatch =
                ($itemParams['width'] ?? null) == ($this->parameters['width'] ?? null) &&
                ($itemParams['height'] ?? null) == ($this->parameters['height'] ?? null) &&
                ($itemParams['depth'] ?? null) == ($this->parameters['depth'] ?? null) &&
                ($itemParams['color'] ?? null) == ($this->parameters['color'] ?? null);
            // Solo comparar glassColor si es window o door
            if (in_array($productType, ['window', 'door'])) {
                $paramsMatch = $paramsMatch && (($itemParams['glassColor'] ?? null) == ($this->parameters['glassColor'] ?? null));
            }
            if ($paramsMatch) {
                return $item;
            }
        }
        return null;
    }

    public function saveProforma($returnId = false)
    {
        try {
            if (!auth()->check()) {
                if ($returnId)
                    return null;
                session()->flash('error', 'Debes iniciar sesión para guardar la configuración.');
                return;
            }

            $configuration = $this->buildConfiguration();

            // Si ya tenemos una proforma guardada (currentProformaId), usarla
            if ($this->currentProformaId) {
                $proforma = DB::table('proformas')->where('id', $this->currentProformaId)->first();
            } else {
                $proforma = $this->getOrCreateActiveProforma(true);
            }

            if (!$proforma) {
                if ($returnId)
                    return null;
                session()->flash('error', 'No se pudo crear la proforma.');
                return;
            }

            $matchingItem = $this->findMatchingItem($proforma->id);

            if ($matchingItem) {
                // Verificar si los costos del ítem están desactualizados
                if ($this->itemNeedsCostUpdate($matchingItem)) {
                    // Mostrar advertencia de actualización de costos
                    $this->showCostUpdateWarning = true;
                    $this->proformaIdPendingCostUpdate = $proforma->id;
                    $this->pendingConfiguration = $configuration;
                    $this->pendingItemId = $matchingItem->id;
                    
                    if ($returnId) {
                        return null;
                    }
                    return;
                }
                
                // Si los costos están actualizados, actualizar el ítem normalmente
                $costSnapshot = $this->calculateCostSnapshot();
                
                DB::table('proforma_items')
                    ->where('id', $matchingItem->id)
                    ->update([
                        'configuration' => json_encode($configuration),
                        'quantity' => $this->quantity,
                        'price' => $this->calculatedPrice,
                        // Actualizar snapshot de costos
                        'material_cost' => $costSnapshot['material_cost'],
                        'direct_cost' => $costSnapshot['direct_cost'],
                        'indirect_cost' => $costSnapshot['indirect_cost'],
                        'waste_cost' => $costSnapshot['waste_cost'],
                        'profit_amount' => $costSnapshot['profit_amount'],
                        'total_cost' => $costSnapshot['total_cost'],
                        'profit_margin_percentage' => $costSnapshot['profit_margin_percentage'],
                        'updated_at' => now()
                    ]);
            } else {
                // Crear nuevo ítem
                $this->createProformaItem($proforma->id, $configuration);
            }

            $this->updateProformaTotalPrice($proforma->id);

            if ($returnId) {
                return $proforma->id;
            }

            session()->flash('message', $matchingItem
                ? '¡Configuración actualizada en la proforma!'
                : '¡Configuración agregada a la proforma exitosamente!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la configuración: ' . $e->getMessage());
            if ($returnId)
                return null;
        }
    }

    /**
     * Obtener o crear una proforma activa (no ordenada, no expirada) para el usuario actual
     */
    private function getOrCreateActiveProforma($create = false)
    {
        if (!auth()->check()) {
            return null;
        }

        // Buscar proforma activa (no ordenada, no expirada)
        $activeProforma = $this->getActiveProformasQuery()
            ->orderBy('proformas.created_at', 'desc')
            ->first();

        if ($activeProforma) {
            return $activeProforma;
        }

        // Si no existe y se solicita crear, crear una nueva
        if ($create) {
            $nextNumber = $this->generateNextProformaNumber();
            $expirationDate = now()->addDays(30);

            $proformaId = DB::table('proformas')->insertGetId([
                'number' => $nextNumber,
                'user_id' => auth()->id(),
                'total_price' => 0,
                'expiration_date' => $expirationDate,
                'is_expired' => false,
                'is_ordered' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return DB::table('proformas')->where('id', $proformaId)->first();
        }

        return null;
    }

    /**
     * Guardar configuración en una proforma específica
     */
    private function saveProformaToSpecific($proformaId)
    {
        try {
            if (!auth()->check()) {
                session()->flash('error', 'Debes iniciar sesión.');
                return;
            }

            // Verificar que la proforma pertenece al usuario
            $proforma = DB::table('proformas')
                ->where('id', $proformaId)
                ->where('user_id', auth()->id())
                ->first();

            if (!$proforma) {
                session()->flash('error', 'Proforma no encontrada.');
                return;
            }

            $configuration = $this->buildConfiguration();
            $this->createProformaItem($proformaId, $configuration);
            $this->updateProformaTotalPrice($proformaId);

            session()->flash('message', '¡Configuración agregada a la proforma ' . $proforma->number . '!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Crear una nueva proforma con esta configuración
     */
    private function saveProformaToNew()
    {
        try {
            if (!auth()->check()) {
                session()->flash('error', 'Debes iniciar sesión.');
                return;
            }

            $user = auth()->user();
            if (!$this->userHasCompleteProfile($user)) {
                session()->flash('error', 'Debes completar tus datos personales antes de guardar una proforma.');
                return;
            }

            $configuration = $this->buildConfiguration();

            // Crear nueva proforma
            $nextNumber = $this->generateNextProformaNumber();
            $expirationDate = now()->addDays(30);

            $proformaId = DB::table('proformas')->insertGetId([
                'number' => $nextNumber,
                'user_id' => auth()->id(),
                'total_price' => $this->calculatedPrice,
                'expiration_date' => $expirationDate,
                'is_expired' => false,
                'is_ordered' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->createProformaItem($proformaId, $configuration);

            session()->flash('message', '¡Nueva proforma ' . $nextNumber . ' creada exitosamente!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear proforma: ' . $e->getMessage());
        }
    }
    /**
     * Verifica si el usuario tiene todos los datos personales requeridos
     */
    private function userHasCompleteProfile($user)
    {
        if (!$user)
            return false;
        $required = [
            'name',
            'email',
            'phone',
            'address',
            'province',
            'city',
        ];
        foreach ($required as $field) {
            if (empty($user->$field)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Actualizar el precio total de una proforma sumando todos sus ítems
     */
    private function updateProformaTotalPrice($proformaId)
    {
        $totalPrice = DB::table('proforma_items')
            ->where('proforma_id', $proformaId)
            ->where('is_active', true)
            ->sum('price');

        DB::table('proformas')
            ->where('id', $proformaId)
            ->update([
                'total_price' => $totalPrice,
                'updated_at' => now()
            ]);
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

    /**
     * Obtener todos los ítems de la proforma activa
     */
    public function getProformaItems()
    {
        if (!$this->currentProformaId) {
            return collect([]);
        }

        $items = DB::table('proforma_items')
            ->join('products', 'proforma_items.product_id', '=', 'products.id')
            ->where('proforma_items.proforma_id', $this->currentProformaId)
            ->select(
                'proforma_items.*',
                'products.name as product_name',
                'products.image as product_image'
            )
            ->get();

        return $items->map(function ($item) {
            $config = json_decode($item->configuration, true);
            $item->parsed_config = $config;
            return $item;
        });
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
        $productType = $this->getProductType();
        if ($color && $color->texture_path) {
            return $color->texture_path;
        }
        // Si es closet y no hay texture_path, usar melamina por defecto
        if ($productType === 'closet') {
            return '/textures/melamina/natural/';
        }
        // Por defecto aluminio
        return '/textures/aluminum/natural/';
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
        $proformaItems = $this->getProformaItems();
        $proformaTotalPrice = $this->currentProformaId
            ? DB::table('proformas')->where('id', $this->currentProformaId)->value('total_price')
            : 0;

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
            'user' => $user,
            'proformaItems' => $proformaItems,
            'proformaTotalPrice' => $proformaTotalPrice,
            'userProfileComplete' => $this->userHasCompleteProfile($user),
        ])->layout('layouts.guest', [
                    'title' => 'Configurador 3D - ' . $this->product->name,
                    'description' => 'Personaliza ' . $this->product->name . ' en tiempo real con nuestro configurador 3D interactivo.'
                ]);
    }
}
