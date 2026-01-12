<?php

namespace App\Livewire\Admin\Inventory;

use App\Livewire\Traits\WithCustomPagination;
use App\Models\Material;
use App\Models\MaterialMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class InventoryTable extends Component
{
    use WithCustomPagination;

    // Propiedades públicas
    public $showAddStockModal = false;
    public $showRemainderModal = false;
    public $showEditModal = false;
    public $selectedMaterial = null;
    public $editingMaterial = null;
    public $quantity = 0;

    // Filtros y búsqueda
    public $filterLowStock = false;
    public $filterOutOfStock = false;
    public $filterByType = 'all'; // all, by_piece, by_unit
    public $search = '';
    public $category_id = '';

    // Listeners
    protected $listeners = ['refreshInventory' => '$refresh'];

    public function mount()
    {
        // Inicializar paginación con 10 elementos por página
        $this->perPage = 5;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterByType()
    {
        $this->resetPage();
    }

    public function updatingFilterLowStock()
    {
        $this->resetPage();
    }

    public function updatingFilterOutOfStock()
    {
        $this->resetPage();
    }

    public function editMaterial(Material $material)
    {
        $this->editingMaterial = $material;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingMaterial = null;
    }

    #[On('materialUpdated')]
    public function materialUpdated()
    {
        $this->closeEditModal();
        session()->flash('success', 'Material actualizado exitosamente.');
    }

    public function openAddStockModal($materialId)
    {
        $this->selectedMaterial = Material::with('remainders')->find($materialId);
        $this->quantity = 0;
        $this->showAddStockModal = true;
    }

    public function closeAddStockModal()
    {
        $this->showAddStockModal = false;
        $this->selectedMaterial = null;
        $this->quantity = 0;
        $this->resetValidation();
    }

    public function addStock()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
        ], [
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
        ]);

        try {
            // Guardar stock anterior
            $stockBefore = $this->selectedMaterial->stock_quantity;

            // Agregar stock al material (el método ya actualiza stock_quantity y guarda)
            $this->selectedMaterial->addStock($this->quantity, true);

            // Recargar el material para obtener el stock actualizado
            $this->selectedMaterial->refresh();

            // Crear registro de movimiento (compra)
            MaterialMovement::create([
                'material_id' => $this->selectedMaterial->id,
                'quantity' => $this->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $this->selectedMaterial->stock_quantity,
                'order_id' => null, // No hay orden asociada para compras
                'user_id' => Auth::id(),
            ]);

            $unitLabel = $this->selectedMaterial->unit == 'm' ? 'piezas' : 'unidades';
            session()->flash('success', "Se agregaron {$this->quantity} {$unitLabel} de {$this->selectedMaterial->name} al inventario.");

            $this->closeAddStockModal();
            // No llamamos dispatch para evitar que se recargue la página y se pierda el mensaje flash
            // El componente se actualizará automáticamente al cerrar el modal
        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar stock: ' . $e->getMessage());
        }
    }

    public function openRemainderModal($materialId)
    {
        $this->selectedMaterial = Material::with([
            'remainders' => function ($query) {
                $query->where('status', 'available')->orderBy('created_at', 'desc');
            }
        ])->find($materialId);

        $this->showRemainderModal = true;
    }

    public function closeRemainderModal()
    {
        $this->showRemainderModal = false;
        $this->selectedMaterial = null;
    }

    public function toggleLowStockFilter()
    {
        $this->filterLowStock = !$this->filterLowStock;
        if ($this->filterLowStock) {
            $this->filterOutOfStock = false;
        }
    }

    public function toggleOutOfStockFilter()
    {
        $this->filterOutOfStock = !$this->filterOutOfStock;
        if ($this->filterOutOfStock) {
            $this->filterLowStock = false;
        }
    }

    public function clearFilters()
    {
        $this->filterLowStock = false;
        $this->filterOutOfStock = false;
        $this->filterByType = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function getFilteredMaterialsProperty()
    {
        $query = Material::query()
            ->with([
                'remainders' => function ($query) {
                    $query->where('status', 'available');
                },
                'category'
            ])
            ->where('is_active', true);

        // Filtro por categoría (incluye hijas)
        if (!empty($this->category_id)) {
            $category = \App\Models\Category::find($this->category_id);
            if ($category) {
                $categoryIds = array_merge([$category->id], $category->getAllChildrenIds());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Aplicar búsqueda por nombre o descripción
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('description', 'ilike', '%' . $this->search . '%');
            });
        }

        // Aplicar filtro por tipo
        if ($this->filterByType !== 'all') {
            if ($this->filterByType === 'by_piece') {
                $query->where('is_by_piece', true);
            } else if ($this->filterByType === 'by_unit') {
                $query->where('is_by_piece', false);
            }
        }

        // Aplicar filtro de bajo stock
        if ($this->filterLowStock) {
            $query->where(function ($q) {
                $q->whereRaw('stock_quantity <= min_stock_alert')
                    ->whereRaw('stock_quantity > 0');
            });
        }

        // Aplicar filtro de sin stock
        if ($this->filterOutOfStock) {
            $query->where('stock_quantity', 0)
                ->whereDoesntHave('remainders', function ($q) {
                    $q->where('status', 'available');
                });
        }

        // Guardar el total para la paginación
        $this->total = $query->count();

        return $query->orderBy('name')
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

        /**
     * Obtiene la genealogía de movimientos agrupados por retazo
     * Muestra el historial de cada retazo desde su creación hasta su uso completo
     */
    public function getMaterialTrace(int $materialId)
    {
        $material = Material::find($materialId);
        if (!$material) {
            return [];
        }

        // Obtener todos los retazos del material (incluye los creados por movimientos)
        $remainders = \App\Models\MaterialRemainder::where('material_id', $materialId)
            ->with(['materialMovement', 'movements.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener movimientos directos del stock (sin retazo asociado)
        $stockMovements = $material->movements()
            ->whereNull('material_remainder_id')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = [];

        // Primero, agrupar los movimientos del stock original
        if ($stockMovements->count() > 0) {
            $stockData = [
                'type' => 'stock',
                'remainder_id' => null,
                'created_at' => null,
                'movements' => [],
                'movements_count' => 0,
                'total_used' => 0,
            ];

            foreach ($stockMovements as $movement) {
                $stockData['movements'][] = [
                    'type' => $movement->order_id ? 'order' : 'purchase',
                    'description' => $movement->order_id ? 'Orden #' . $movement->order_id : 'Compra de material',
                    'quantity' => $movement->quantity,
                    'remainder_after' => 0, // Stock no genera retazos aquí
                    'date' => $movement->created_at,
                    'user' => $movement->user?->name,
                ];
                $stockData['movements_count']++;
                $stockData['total_used'] += abs($movement->quantity);
            }

            $grouped[] = $stockData;
        }

        // Luego, agrupar por retazos
        foreach ($remainders as $remainder) {
            $remainderData = [
                'type' => 'remainder',
                'remainder_id' => $remainder->id,
                'created_at' => $remainder->created_at,
                'movements' => [],
                'movements_count' => 0,
                'total_used' => 0,
            ];

            // Obtener todos los movimientos que usaron este retazo
            foreach ($remainder->movements as $movement) {
                $remainderData['movements'][] = [
                    'type' => $movement->order_id ? 'order' : 'purchase',
                    'description' => $movement->order_id ? 'Orden #' . $movement->order_id : 'Ajuste de inventario',
                    'quantity' => $movement->quantity,
                    'remainder_after' => $remainder->remaining_length, // Longitud actual del retazo
                    'date' => $movement->created_at,
                    'user' => $movement->user?->name,
                ];
                $remainderData['movements_count']++;
                $remainderData['total_used'] += abs($movement->quantity);
            }

            // Solo agregar si tiene movimientos
            if ($remainderData['movements_count'] > 0) {
                $grouped[] = $remainderData;
            }
        }

        return $grouped;
    }

    public function render()
    {
        return view('livewire.admin.inventory.inventory-table', [
            'materials' => $this->filteredMaterials,
        ])->layout('partials.sidebar');
    }
}
