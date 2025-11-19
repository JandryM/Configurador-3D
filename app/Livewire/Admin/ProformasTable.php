<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;

class ProformasTable extends Component
{
    public $proformas = [];
    public $showProformaModal = false;
    public $selectedProforma = null;

    public function mount()
    {
        $this->proformas = DB::table('proformas')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($row) {
                $user = User::find($row->user_id);
                
                // Obtener todos los items de esta proforma
                $items = DB::table('proforma_items')
                    ->where('proforma_id', $row->id)
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
                
                // Sumar la cantidad total de productos en todas las configuraciones
                $totalQuantity = $items->sum('quantity');
                
                return [
                    'id' => $row->id,
                    'number' => $row->number,
                    'client' => $user ? $user->name : 'Usuario eliminado',
                    'date' => $row->created_at,
                    'total_price' => $row->total_price,
                    'items_count' => $items->count(),
                    'total_quantity' => $totalQuantity,
                    'items' => $items->toArray(),
                    'user' => $user,
                    'expiration_date' => $row->expiration_date,
                    'is_expired' => $row->is_expired,
                ];
            });
    }

    public function showProforma($proformaId)
    {
        $this->selectedProforma = collect($this->proformas)->firstWhere('id', $proformaId);
        $this->showProformaModal = true;
    }

    public function closeModal()
    {
        $this->showProformaModal = false;
        $this->selectedProforma = null;
    }

    public function downloadProformaPdf($proformaId)
    {
        $proforma = collect($this->proformas)->firstWhere('id', $proformaId);
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

    public function render()
    {
        return view('livewire.admin.proforma.proformas-table')->layout('partials.sidebar');
    }
}
