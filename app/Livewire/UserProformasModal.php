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

        $proformasData = DB::table('proformas')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $this->proformas = $proformasData->map(function ($proforma) {
            // Obtener todos los ítems de esta proforma
            $items = DB::table('proforma_items')
                ->join('products', 'proforma_items.product_id', '=', 'products.id')
                ->where('proforma_items.proforma_id', $proforma->id)
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

    public function orderProforma($proformaId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Debes iniciar sesión para ordenar la proforma.');
            return;
        }

        $proforma = DB::table('proformas')
            ->where('id', $proformaId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$proforma) {
            session()->flash('error', 'Proforma no encontrada.');
            return;
        }

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
        
        session()->flash('message', '¡Orden creada exitosamente con el número ' . $nextOrderNumber . '!');
        
        // Recargar proformas para actualizar el estado
        $this->loadProformas();
        $this->closeProformaModal();
    }

    public function render()
    {
        return view('livewire.user-proformas-modal');
    }
}
