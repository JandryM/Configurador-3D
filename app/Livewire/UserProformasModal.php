<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class UserProformasModal extends Component
{
    public $showModal = false;
    public $proformas = [];
    public $selectedProforma = null;
    public $showProformaModal = false;
    public $selectedProformaId = null;
    public $paginaProformas = 1;
    public $proformasPorPagina = 5;
    public $totalProformas = 0;
    public $selectedProformas = [];
    public $selectAll = false;
    public $confirmOrderId = null;

    public function setConfirmOrderId($id)
    {
        $this->confirmOrderId = $id;
    }

    public function getIsAllSelectedProperty()
    {
        $availableIds = collect($this->proformas)
            ->filter(fn($p) => !$p['is_ordered'])
            ->pluck('id')
            ->toArray();
        
        return count($availableIds) > 0 && count($this->selectedProformas) === count($availableIds);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Seleccionar todas las disponibles
            $this->selectedProformas = collect($this->proformas)
                ->filter(fn($p) => !$p['is_ordered'])
                ->pluck('id')
                ->toArray();
        } else {
            // Deseleccionar todas
            $this->selectedProformas = [];
        }
    }

    public function updatedSelectedProformas()
    {
        // Sincronizar selectAll con el estado de selectedProformas
        $this->selectAll = $this->isAllSelected;
    }

    protected $listeners = ['openProformasModal'];

    public function mount()
    {
        $this->loadProformas();
    }

    public function openProformasModal()
    {
        $this->showModal = true;
        $this->loadProformas();
    }

    public function loadProformas()
    {
        $this->proformas = [];
        if (!Auth::check()) {
            return;
        }

        $this->totalProformas = DB::table('proformas')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->count();

        $offset = ($this->paginaProformas - 1) * $this->proformasPorPagina;

        $proformasData = DB::table('proformas')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->offset($offset)
            ->limit($this->proformasPorPagina)
            ->get();

        $this->proformas = $proformasData->map(function ($proforma) {
            // Obtener solo los ítems activos de esta proforma
            $items = DB::table('proforma_items')
                ->join('products', 'proforma_items.product_id', '=', 'products.id')
                ->where('proforma_items.proforma_id', $proforma->id)
                ->where('proforma_items.is_active', true)
                ->select(
                    'proforma_items.*',
                    'products.name as product_name',
                    'products.image as product_image'
                )
                ->get();

            // Parsear la configuración de cada ítem
            $parsedItems = $items->map(function ($item) {
                $config = json_decode($item->configuration, true);
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_image' => $item->product_image,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'notes' => $item->notes,
                    'parameters' => $config['parameters'] ?? [],
                    'material_costs' => $config['material_costs'] ?? [],
                    'created_at' => $item->created_at,
                ];
            });

            // Calcular el total de productos (sumar cantidades)
            $totalQuantity = $items->sum('quantity');

            return [
                'id' => $proforma->id,
                'number' => $proforma->number,
                'total_price' => $proforma->total_price,
                'items' => $parsedItems->toArray(),
                'items_count' => $items->count(),
                'total_quantity' => $totalQuantity,
                'is_ordered' => $proforma->is_ordered,
                'created_at' => $proforma->created_at,
                'expiration_date' => $proforma->expiration_date,
                'is_expired' => $proforma->is_expired,
            ];
        })->toArray();
    }

            /**
         * Eliminar (desactivar) un ítem de configuración de una proforma
         */
        public function deleteProformaItem($itemId)
        {
            if (!Auth::check()) {
                session()->flash('error', 'Debes iniciar sesión para eliminar configuraciones.');
                return;
            }

            $item = DB::table('proforma_items')->where('id', $itemId)->first();
            if (!$item) {
                session()->flash('error', 'Configuración no encontrada.');
                return;
            }

            // Verificar que la proforma pertenezca al usuario
            $proforma = DB::table('proformas')->where('id', $item->proforma_id)->first();
            if (!$proforma || $proforma->user_id !== Auth::id()) {
                session()->flash('error', 'No tienes permiso para eliminar esta configuración.');
                return;
            }

            // Solo permitir si la proforma no está ordenada ni expirada
            if ($proforma->is_ordered || $proforma->is_expired) {
                session()->flash('error', 'No se puede eliminar configuraciones de una proforma ordenada o expirada.');
                return;
            }

            DB::table('proforma_items')->where('id', $itemId)->update(['is_active' => false, 'updated_at' => now()]);
            // Actualizar el total de la proforma después de eliminar el ítem
            $this->updateProformaTotalPrice($proforma->id);

            // Si ya no quedan ítems activos, marcar la proforma como inactiva y cerrar el modal de detalles
            $activeItemsCount = DB::table('proforma_items')
                ->where('proforma_id', $proforma->id)
                ->where('is_active', true)
                ->count();
            if ($activeItemsCount === 0) {
                DB::table('proformas')
                    ->where('id', $proforma->id)
                    ->update(['is_active' => false, 'updated_at' => now()]);
                session()->flash('success', 'La proforma ha sido eliminada porque no quedan configuraciones.');
                $this->closeProformaModal();
            } else {
                session()->flash('success', 'Configuración eliminada exitosamente.');
                // Si está viendo el modal de la proforma, actualizar la selección
                if ($this->showProformaModal && $this->selectedProformaId == $proforma->id) {
                    $this->showProforma($proforma->id);
                }
            }

            // Recargar proformas para reflejar el cambio
            $this->loadProformas();
        }

                /**
             * Actualizar el precio total de una proforma sumando solo los ítems activos
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

    public function showProforma($proformaId)
    {
        $proforma = collect($this->proformas)->firstWhere('id', $proformaId);
        
        if ($proforma) {
            $this->selectedProforma = $proforma;
            $this->selectedProformaId = $proformaId;
            $this->showProformaModal = true;
        }
    }

    public function closeProformaModal()
    {
        $this->showProformaModal = false;
        $this->selectedProforma = null;
        $this->selectedProformaId = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->closeProformaModal();
        $this->confirmOrderId = null;
    }

    public function actualizarProformas($pagina = null)
    {
        if ($pagina !== null) {
            $this->paginaProformas = $pagina;
        }
        $this->loadProformas();
    }

    public function anteriorPaginaProformas()
    {
        if ($this->paginaProformas > 1) {
            $this->paginaProformas--;
            $this->loadProformas();
        }
    }

    public function siguientePaginaProformas()
    {
        $totalPaginas = ceil($this->totalProformas / $this->proformasPorPagina);
        if ($this->paginaProformas < $totalPaginas) {
            $this->paginaProformas++;
            $this->loadProformas();
        }
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

    public function downloadProformaPdf($proformaId)
    {
        $proforma = DB::table('proformas')->where('id', $proformaId)->first();
        
        if (!$proforma || $proforma->user_id !== Auth::id()) {
            session()->flash('error', 'No tienes permiso para descargar esta proforma.');
            return;
        }

        $items = $this->getProformaItemsForPdf($proformaId);

        if (empty($items)) {
            session()->flash('error', 'La proforma está vacía.');
            return;
        }

        $user = Auth::user();
        $total_price = $proforma->total_price;
        $number = $proforma->number;
        $expiration_date = $proforma->expiration_date;
        $is_expired = $proforma->is_expired;
        $isPdf = true;

        $pdf = Pdf::loadView('livewire.proformas.proforma', compact(
            'items',
            'user',
            'total_price',
            'number',
            'expiration_date',
            'is_expired',
            'isPdf'
        ));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'proforma_' . $number . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Verifica si la proforma está activa y tiene al menos un ítem activo
     */
    private function validateProformaForOrder($proformaId)
    {
        $proforma = DB::table('proformas')
            ->where('id', $proformaId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$proforma) {
            return 'Proforma no encontrada.';
        }
        if (!$proforma->is_active) {
            return 'La proforma no existe. No se puede ordenar.';
        }
        $activeItemsCount = DB::table('proforma_items')
            ->where('proforma_id', $proformaId)
            ->where('is_active', true)
            ->count();
        if ($activeItemsCount === 0) {
            return 'La proforma no tiene configuraciones activas. No se puede ordenar.';
        }
        return null;
    }

    public function orderProforma($proformaId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión para ordenar la proforma.');
            return;
        }

        $validationError = $this->validateProformaForOrder($proformaId);
        if ($validationError) {
            session()->flash('error', $validationError);
            return;
        }

        $proforma = DB::table('proformas')
            ->where('id', $proformaId)
            ->where('user_id', Auth::id())
            ->first();

        // Verificar si ya está ordenada
        $existingOrder = DB::table('orders')
            ->where('proforma_id', $proformaId)
            ->first();

        if ($existingOrder) {
            session()->flash('error', 'Esta proforma ya ha sido ordenada.');
            return;
        }

        // Verificar si la proforma está expirada
        if ($proforma->is_expired || now()->greaterThan($proforma->expiration_date)) {
            // Marcar como expirada si no lo está
            if (!$proforma->is_expired) {
                DB::table('proformas')
                    ->where('id', $proformaId)
                    ->update(['is_expired' => true, 'updated_at' => now()]);
            }
            session()->flash('error', 'Esta proforma ha expirado. Por favor, genera una nueva configuración.');
            return;
        }

        // Generar número único de orden
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
        DB::table('proformas')
            ->where('id', $proformaId)
            ->update(['is_ordered' => true, 'updated_at' => now()]);

        // Remover de la selección si estaba seleccionada
        if (in_array($proformaId, $this->selectedProformas)) {
            $this->selectedProformas = array_values(array_diff($this->selectedProformas, [$proformaId]));
        }

        session()->flash('success', '¡Orden creada exitosamente con el número ' . $nextOrderNumber . '!');
        // Cerrar modal de confirmación
        $this->confirmOrderId = null;
        
        // Recargar proformas para actualizar el estado
        $this->loadProformas();
        $this->closeProformaModal();
    }

    public function toggleProformaSelection($proformaId)
    {
        if (in_array($proformaId, $this->selectedProformas)) {
            $this->selectedProformas = array_values(array_diff($this->selectedProformas, [$proformaId]));
        } else {
            $this->selectedProformas[] = $proformaId;
        }
    }

    public function clearSelection()
    {
        $this->selectedProformas = [];
        $this->selectAll = false;
    }



    public function deleteSelectedProformas()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión para eliminar proformas.');
            return;
        }

        if (empty($this->selectedProformas)) {
            session()->flash('error', 'No has seleccionado ninguna proforma para eliminar.');
            return;
        }

        // Verificar que todas las proformas seleccionadas pertenecen al usuario y no están ordenadas
        $proformasToDelete = DB::table('proformas')
            ->whereIn('id', $this->selectedProformas)
            ->where('user_id', Auth::id())
            ->where('is_ordered', false)
            ->pluck('id')
            ->toArray();

        if (empty($proformasToDelete)) {
            session()->flash('error', 'No se pueden eliminar las proformas seleccionadas.');
            return;
        }

        // Soft delete: marcar como inactivas
        $deletedCount = DB::table('proformas')
            ->whereIn('id', $proformasToDelete)
            ->update(['is_active' => false, 'updated_at' => now()]);
        
        session()->flash('success', $deletedCount . ' ' . ($deletedCount == 1 ? 'proforma eliminada' : 'proformas eliminadas') . ' exitosamente.');
        
        // Limpiar selección
        $this->selectedProformas = [];
        
        // Recargar proformas
        $this->loadProformas();
    }

    public function render()
    {
        return view('livewire.user-proformas-modal');
    }
}
