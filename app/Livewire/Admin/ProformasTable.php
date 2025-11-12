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
                $config = json_decode($row->configuration, true) ?? [];
                $product = Product::find($row->product_id);
                $user = User::find($row->user_id);
                // Asegurar estructura y valores por defecto
                $parameters = isset($config['parameters']) && is_array($config['parameters']) ? $config['parameters'] : [];
                $materialCosts = isset($config['material_costs']) && is_array($config['material_costs']) ? $config['material_costs'] : [];
                $calculatedPrice = $config['calculated_price'] ?? $row->price;
                $notes = $parameters['notes'] ?? null;
                // Obtener costos directos e indirectos si no están en la configuración
                $directCost = $config['directCost'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $row->product_id)
                    ->value('direct_cost_percentage') ?? 0;
                $currentIndirectSetting = \App\Models\GlobalCostSetting::current()->first();
                $indirectCost = $config['indirectCost'] ?? ($currentIndirectSetting ? $currentIndirectSetting->indirect_cost_percentage : 0);
                $wastePercentage = $config['wastePercentage'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $row->product_id)
                    ->value('waste_percentage') ?? 0;
                $profitMargin = $config['profitMargin'] ?? DB::table('product_cost_settings')
                    ->where('product_id', $row->product_id)
                    ->value('profit_margin_percentage') ?? 0;
                return [
                    'id' => $row->id,
                    'number' => 'PRF-' . str_pad($row->id, 3, '0', STR_PAD_LEFT),
                    'client' => $user ? $user->name : 'Usuario eliminado',
                    'date' => $row->created_at,
                    'amount' => $row->price,
                    'quantity' => $row->quantity ?? 1,
                    'product' => $product,
                    'parameters' => $parameters,
                    'materialCosts' => $materialCosts,
                    'calculatedPrice' => $calculatedPrice,
                    'notes' => $notes,
                    'directCost' => $directCost,
                    'indirectCost' => $indirectCost,
                    'wastePercentage' => $wastePercentage,
                    'profitMargin' => $profitMargin,
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
        $product = $proforma['product'];
        $parameters = $proforma['parameters'];
        $quantity = $proforma['quantity'] ?? 1;
        $materialCosts = $proforma['materialCosts'];
        $calculatedPrice = $proforma['calculatedPrice'];
        $notes = $proforma['notes'] ?? null;
        $directCost = $proforma['directCost'] ?? null;
        $indirectCost = $proforma['indirectCost'] ?? null;
        $wastePercentage = $proforma['wastePercentage'] ?? null;
        $profitMargin = $proforma['profitMargin'] ?? null;
        $user = $proforma['user'];
        $expiration_date = $proforma['expiration_date'] ?? null;
        $is_expired = $proforma['is_expired'] ?? null;
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('livewire.proformas.proforma-admin', compact('product', 'parameters', 'quantity', 'materialCosts', 'calculatedPrice', 'notes', 'directCost', 'indirectCost', 'wastePercentage', 'profitMargin', 'user', 'expiration_date', 'is_expired'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'proforma_' . now()->format('Ymd_His') . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.proforma.proformas-table')->layout('partials.sidebar');
    }
}
