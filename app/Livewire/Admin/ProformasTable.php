<?php
namespace App\Livewire\Admin;

use App\Livewire\Traits\WithCustomPagination;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;

class ProformasTable extends Component
{
    use WithCustomPagination;

    public $showProformaModal = false;
    public $selectedProforma = null;
    
    // Filtros (null = sin filtro, true = filtro positivo, 'inverse' = filtro negativo)
    public $search = '';
    public $filterOrdered = null;
    public $filterExpired = null;

    public function mount()
    {
        // Inicializar paginación con 5 elementos por página
        $this->perPage = 5;
    }

    private function getAllProformas()
    {
        return DB::table('proformas')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($row) {
                $user = User::find($row->user_id);
                // Obtener solo los items activos de esta proforma
                $items = DB::table('proforma_items')
                    ->where('proforma_id', $row->id)
                    ->where('is_active', true)
                    ->get()
                    ->map(function ($item) {
                        $product = Product::find($item->product_id);
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
                    });
                // Sumar la cantidad total de productos en todas las configuraciones activas
                $totalQuantity = $items->sum('quantity');
                // Calcular el total_price sumando solo los ítems activos
                $totalPrice = $items->sum('price');
                return [
                    'id' => $row->id,
                    'number' => $row->number,
                    'client' => $user ? $user->name : 'Usuario eliminado',
                    'date' => $row->created_at,
                    'total_price' => $totalPrice,
                    'items_count' => $items->count(),
                    'total_quantity' => $totalQuantity,
                    'items' => $items->toArray(),
                    'user' => $user,
                    'expiration_date' => $row->expiration_date,
                    'is_expired' => $row->is_expired,
                ];
            });
    }

    public function render()
    {
        $allProformas = $this->getAllProformas();
        
        // Aplicar filtros
        $filteredProformas = $allProformas;

        // Siempre excluir proformas inactivas
        $filteredProformas = $filteredProformas->filter(fn($p) => !empty($p['items']) && $p['items_count'] > 0);

        // Filtro de búsqueda
        if ($this->search !== '') {
            $search = strtolower($this->search);
            $filteredProformas = $filteredProformas->filter(function ($proforma) use ($search) {
                return str_contains(strtolower($proforma['number']), $search) ||
                       str_contains(strtolower($proforma['client']), $search);
            });
        }
        
        // Filtro de ordenadas (3 estados: null, true, 'inverse')
        if ($this->filterOrdered === true) {
            $filteredProformas = $filteredProformas->filter(function ($proforma) {
                return DB::table('orders')->where('proforma_id', $proforma['id'])->exists();
            });
        } elseif ($this->filterOrdered === 'inverse') {
            $filteredProformas = $filteredProformas->filter(function ($proforma) {
                return !DB::table('orders')->where('proforma_id', $proforma['id'])->exists();
            });
        }
        
        // Filtro de expiradas (3 estados: null, true, 'inverse')
        if ($this->filterExpired === true) {
            $filteredProformas = $filteredProformas->filter(fn($p) => $p['is_expired']);
        } elseif ($this->filterExpired === 'inverse') {
            $filteredProformas = $filteredProformas->filter(fn($p) => !$p['is_expired']);
        }

        // Filtro de inactivas (eliminado)
        // if ($this->filterInactive) {
        //     $filteredProformas = $filteredProformas->filter(fn($p) => empty($p['items']) || $p['items_count'] == 0);
        // }
        
        // Guardar el total para la paginación
        $this->total = $filteredProformas->count();
        
        // Paginar los resultados
        $proformas = $filteredProformas
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->values()
            ->toArray();
        
        return view('livewire.admin.proforma.proformas-table', [
            'proformas' => $proformas,
            'allProformas' => $allProformas,
        ])->layout('partials.sidebar');
    }

    public function showProforma($proformaId)
    {
        $allProformas = $this->getAllProformas();
        $this->selectedProforma = $allProformas->firstWhere('id', $proformaId);
        $this->showProformaModal = true;
    }

    public function closeModal()
    {
        $this->showProformaModal = false;
        $this->selectedProforma = null;
    }

    public function downloadProformaPdf($proformaId)
    {
        $allProformas = $this->getAllProformas();
        $proforma = $allProformas->firstWhere('id', $proformaId);
        if (!$proforma) {
            abort(404, 'Proforma no encontrada.');
        }
        
        $user = $proforma['user'];
        $items = $proforma['items'];
        $total_price = $proforma['total_price'];
        $number = $proforma['number'];
        $expiration_date = $proforma['expiration_date'] ?? null;
        $is_expired = $proforma['is_expired'] ?? false;
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('livewire.proformas.proforma-admin', compact(
            'items',
            'user',
            'total_price',
            'number',
            'expiration_date',
            'is_expired'
        ));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'proforma_' . $number . '_' . now()->format('Ymd_His') . '.pdf');
    }

    public function toggleFilterOrdered()
    {
        if ($this->filterOrdered === null) {
            $this->filterOrdered = true; // Primera vez: mostrar ordenadas
        } elseif ($this->filterOrdered === true) {
            $this->filterOrdered = 'inverse'; // Segunda vez: mostrar no ordenadas
        } else {
            $this->filterOrdered = null; // Tercera vez: sin filtro
        }
        $this->resetPage();
    }
    
    public function toggleFilterExpired()
    {
        if ($this->filterExpired === null) {
            $this->filterExpired = true; // Primera vez: mostrar expiradas
        } elseif ($this->filterExpired === true) {
            $this->filterExpired = 'inverse'; // Segunda vez: mostrar no expiradas
        } else {
            $this->filterExpired = null; // Tercera vez: sin filtro
        }
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
}
