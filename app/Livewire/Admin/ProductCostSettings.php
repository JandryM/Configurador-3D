<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ProductCostSetting;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductCostSettings extends Component
{
    public $product_id;
    public $direct_cost_percentage;
    public $waste_percentage;
    public $profit_margin_percentage;
    public $notes;

    public $products;
    public $currentSetting;
    public $settingsHistory = [];

    // Modal de confirmación
    public $showConfirmModal = false;
    public $pendingAction = null; // 'create' o 'update'

    public function mount()
    {
        $this->loadProducts();
        $this->loadCurrentSetting();
        $this->initializeForm();
    }

    public function loadProducts()
    {
        $this->products = Product::where('product_type', 'customizable')
            ->where('allows_customization', true)
            ->orderBy('name')
            ->get();
    }

    public function loadCurrentSetting()
    {
        if ($this->product_id) {
            $this->currentSetting = ProductCostSetting::where('product_id', $this->product_id)
                ->where('is_active', true)
                ->with('user')
                ->first();
            
            $this->settingsHistory = ProductCostSetting::where('product_id', $this->product_id)
                ->where('is_active', false)
                ->with('user')
                ->orderByDesc('created_at')
                ->get();
        } else {
            $this->currentSetting = null;
            $this->settingsHistory = [];
        }
    }

    public function updatedProductId()
    {
        $this->loadCurrentSetting();
        
        // Si hay una configuración activa, cargar sus datos para mostrar
        if ($this->currentSetting) {
            $this->direct_cost_percentage = $this->currentSetting->direct_cost_percentage;
            $this->waste_percentage = $this->currentSetting->waste_percentage;
            $this->profit_margin_percentage = $this->currentSetting->profit_margin_percentage;
            $this->notes = $this->currentSetting->notes;
        } else {
            $this->initializeForm();
        }
    }

    private function initializeForm()
    {
        $this->direct_cost_percentage = 0;
        $this->waste_percentage = 0;
        $this->profit_margin_percentage = 0;
        $this->notes = '';
    }

    public function openConfirmModal($action)
    {
        $this->pendingAction = $action;
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
        $this->pendingAction = null;
    }

    public function confirmAction()
    {
        if ($this->pendingAction === 'create') {
            $this->performCreate();
        }
        
        $this->closeConfirmModal();
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'direct_cost_percentage' => 'required|numeric|min:0|max:100',
            'waste_percentage' => 'required|numeric|min:0|max:100',
            'profit_margin_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Solo administradores y dueños pueden crear configuraciones
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para crear configuraciones de costos.');
            return;
        }

        $this->openConfirmModal('create');
    }

    private function performCreate()
    {
        $user = auth()->user();

        // Desactivar configuraciones activas previas para el producto
        ProductCostSetting::where('product_id', $this->product_id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Crear nueva configuración
        ProductCostSetting::create([
            'product_id' => $this->product_id,
            'user_id' => $user->id,
            'is_active' => true,
            'direct_cost_percentage' => $this->direct_cost_percentage,
            'waste_percentage' => $this->waste_percentage,
            'profit_margin_percentage' => $this->profit_margin_percentage,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Configuración de costos guardada correctamente.');
        
        $this->loadCurrentSetting();
        $this->initializeForm();
        
        // Notificar al dashboard sobre la actualización
        $this->dispatch('costos-productos-actualizados');
    }

    public function render()
    {
        return view('livewire.admin.product-cost-settings')->layout('partials.sidebar');
    }
}
