<?php
namespace App\Livewire;


use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class UserProformas extends Component
{
    public $proformas = [];
    public $selectedProforma = null;
    public $showProformaModal = false;

    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            $this->proformas = DB::table('proformas')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($row) {
                    $config = json_decode($row->configuration, true);
                    $product = Product::find($row->product_id);
                    // Obtener costos directos e indirectos si no están en la configuración
                    $directCost = $config['directCost'] ?? DB::table('product_cost_settings')
                        ->where('product_id', $row->product_id)
                        ->value('direct_cost_percentage');
                    $indirectCost = $config['indirectCost'] ?? DB::table('global_cost_settings')
                        ->orderByDesc('id')
                        ->value('indirect_cost_percentage');
                    return [
                        'id' => $row->id,
                        'product' => $product,
                        'parameters' => $config['parameters'] ?? [],
                        'materialCosts' => $config['material_costs'] ?? [],
                        'calculatedPrice' => $config['calculated_price'] ?? $row->price,
                        'notes' => $config['parameters']['notes'] ?? null,
                        'directCost' => $directCost ?? 0,
                        'indirectCost' => $indirectCost ?? 0,
                        'created_at' => $row->created_at,
                    ];
                });
        }
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

    public function render()
    {
        return view('livewire.user-proformas');
    }

    public function downloadProformaPdf($proformaId)
    {
        $proforma = collect($this->proformas)->firstWhere('id', $proformaId);
        if (!$proforma) {
            abort(404, 'Proforma no encontrada.');
        }

        $product = $proforma['product'];
        $parameters = $proforma['parameters'];
        $materialCosts = $proforma['materialCosts'];
        $calculatedPrice = $proforma['calculatedPrice'];
        $notes = $proforma['notes'] ?? null;
        $directCost = $proforma['directCost'] ?? null;
        $indirectCost = $proforma['indirectCost'] ?? null;
        $user = auth()->user();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('proforma', compact('product', 'parameters', 'materialCosts', 'calculatedPrice', 'notes', 'directCost', 'indirectCost', 'user'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'proforma_' . now()->format('Ymd_His') . '.pdf');
    }
}
