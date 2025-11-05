<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class UserProformasModal extends Component
{
    public $showModal = false;
    public $proformas = [];
    public $selectedProforma = null;
    public $showProformaModal = false;

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
        
        if (Auth::check()) {
            $proformasData = DB::table('proformas')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
                
            $this->proformas = $proformasData->map(function ($proforma) {
                $configuration = json_decode($proforma->configuration, true);
                $product = Product::find($proforma->product_id);
                
                return [
                    'id' => $proforma->id,
                    'number' => $proforma->number,
                    'product' => $product,
                    'parameters' => $configuration['parameters'] ?? [],
                    'materialCosts' => $configuration['material_costs'] ?? [],
                    'calculatedPrice' => $proforma->price,
                    'notes' => $configuration['parameters']['notes'] ?? null,
                    'directCost' => null,
                    'indirectCost' => null,
                    'created_at' => $proforma->created_at,
                ];
            })->toArray();
        }
    }

    public function showProforma($proformaId)
    {
        $proforma = collect($this->proformas)->firstWhere('id', $proformaId);
        
        if ($proforma) {
            $this->selectedProforma = $proforma;
            $this->showProformaModal = true;
        }
    }

    public function closeProformaModal()
    {
        $this->showProformaModal = false;
        $this->selectedProforma = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->closeProformaModal();
    }

    public function downloadProformaPdf($proformaId)
    {
        $proformaData = DB::table('proformas')->where('id', $proformaId)->first();
        
        if (!$proformaData || $proformaData->user_id !== Auth::id()) {
            session()->flash('error', 'No tienes permiso para descargar esta proforma.');
            return;
        }

        $configuration = json_decode($proformaData->configuration, true);
        $product = Product::find($proformaData->product_id);

        $pdf = Pdf::loadView('livewire.proformas.proforma', [
            'product' => $product,
            'parameters' => $configuration['parameters'] ?? [],
            'materialCosts' => $configuration['material_costs'] ?? [],
            'calculatedPrice' => $proformaData->price,
            'notes' => $configuration['parameters']['notes'] ?? null,
            'directCost' => null,
            'indirectCost' => null,
            'user' => Auth::user(),
            'isPdf' => true,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'proforma_' . $proformaData->number . '_' . now()->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.user-proformas-modal');
    }
}
