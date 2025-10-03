<?php

namespace App\Livewire\Admin\Products;

use App\Models\Material;
use App\Models\Product;
use Livewire\Component;

class ProductMaterials extends Component
{
    public $product;
    public $materials = [];
    public $selectedMaterialId = '';
    public $quantity = '';
    public $waste_percentage = 5;
    public $notes = '';
    public $showAddForm = false;
    public $editingMaterialId = null;

    protected $rules = [
        'selectedMaterialId' => 'required|exists:materials,id',
        'quantity' => 'required|numeric|min:0.001',
        'waste_percentage' => 'required|numeric|min:0|max:100',
        'notes' => 'nullable|string|max:1000'
    ];

    protected $messages = [
        'selectedMaterialId.required' => 'Selecciona un material.',
        'selectedMaterialId.exists' => 'El material seleccionado no es válido.',
        'quantity.required' => 'La cantidad es obligatoria.',
        'waste_percentage.required' => 'El porcentaje de desperdicio es obligatorio.',
    ];

    public function mount($product = null)
    {
        $this->product = $product;
        if ($product) {
            $this->loadMaterials();
        }
    }

    public function loadMaterials()
    {
        if ($this->product) {
            $this->materials = $this->product->materials()->withPivot([
                'quantity', 'used_quantity', 'waste_percentage', 
                'calculation_formula', 'calculated_cost', 'notes'
            ])->get();
        } else {
            $this->materials = collect();
        }
    }

    public function addMaterial()
    {
        $this->resetForm();
        $this->showAddForm = true;
    }

    public function editMaterial($materialId)
    {
        $material = $this->product->materials()->wherePivot('material_id', $materialId)->first();
        if ($material) {
            $this->selectedMaterialId = $material->id;
            $this->quantity = $material->pivot->quantity;
            $this->waste_percentage = $material->pivot->waste_percentage;
            $this->notes = $material->pivot->notes;
            $this->editingMaterialId = $materialId;
            $this->showAddForm = true;
        }
    }

    public function saveMaterial()
    {
        $this->validate();

        $material = Material::find($this->selectedMaterialId);
        $calculatedCost = $material->calculateCost($this->quantity, $this->waste_percentage);

        // Generar fórmula automática basada en el tipo de material y categoría
        $calculationFormula = $this->generateAutoFormula($material);

        $pivotData = [
            'quantity' => $this->quantity,
            'used_quantity' => $this->quantity, // Simplificado: cantidad = cantidad usada
            'waste_percentage' => $this->waste_percentage,
            'calculation_formula' => $calculationFormula,
            'calculated_cost' => $calculatedCost,
            'notes' => $this->notes
        ];

        if ($this->editingMaterialId) {
            // Actualizar
            $this->product->materials()->updateExistingPivot($this->selectedMaterialId, $pivotData);
            session()->flash('message', 'Material actualizado exitosamente.');
        } else {
            // Crear nuevo
            $this->product->materials()->attach($this->selectedMaterialId, $pivotData);
            session()->flash('message', 'Material agregado exitosamente.');
        }

        $this->resetForm();
        $this->loadMaterials();
    }

    private function generateAutoFormula($material)
    {
        $category = $this->product->category;
        $materialName = $material->name;
        
        // Fórmulas automáticas por categoría y tipo de material
        $formulas = [
            'Aluminio y Vidrio' => [
                'Riel Aluminio' => 'Perímetro de ventana',
                'Marco Aluminio Vertical' => 'Altura de ventana × 2',
                'Vidrio Templado 6mm' => 'Área de ventana',
                'Tornillos Acero Inoxidable' => 'Cantidad por puntos de fijación',
                'Silicón Estructural' => 'Perímetro × factor de sellado'
            ],
            'Melamina' => [
                'Plancha Melamina 18mm' => 'Área total de piezas',
                'Canto PVC' => 'Perímetro de cantos visibles',
                'Bisagras' => 'Cantidad por puertas'
            ],
            'Gypsum' => [
                'Plancha Gypsum 12mm' => 'Área de superficie a cubrir',
                'Perfil Metálico' => 'Perímetro de estructura'
            ]
        ];

        return $formulas[$category][$materialName] ?? 'Cantidad requerida para ' . $materialName;
    }

    public function removeMaterial($materialId)
    {
        $this->product->materials()->detach($materialId);
        $this->loadMaterials();
        session()->flash('message', 'Material eliminado exitosamente.');
    }

    public function resetForm()
    {
        $this->selectedMaterialId = '';
        $this->quantity = '';
        $this->waste_percentage = 5;
        $this->notes = '';
        $this->showAddForm = false;
        $this->editingMaterialId = null;
        $this->resetErrorBag();
    }

    public function updatedSelectedMaterialId()
    {
        if ($this->selectedMaterialId) {
            $material = Material::find($this->selectedMaterialId);
            if ($material) {
                // Auto-completar algunos campos basados en el material
                $this->quantity = $material->is_by_piece ? $material->piece_size : 1;
            }
        }
    }

    public function calculateCost()
    {
        if ($this->selectedMaterialId && $this->quantity) {
            $material = Material::find($this->selectedMaterialId);
            return $material->calculateCost($this->quantity, $this->waste_percentage);
        }
        return 0;
    }

    public function getTotalMaterialsCostProperty()
    {
        return $this->materials->sum(function ($material) {
            return $material->pivot->calculated_cost ?? 0;
        });
    }

    public function render()
    {
        // Filtrar materiales por la categoría del producto
        $availableMaterials = collect();
        
        if ($this->product && $this->product->category) {
            $availableMaterials = Material::where('category', $this->product->category)
                                        ->orderBy('name')
                                        ->get();
        }
        
        return view('livewire.admin.products.product-materials', [
            'availableMaterials' => $availableMaterials
        ]);
    }
}
