<?php

namespace App\Livewire\Admin\Materials;

use App\Models\Material;
use Livewire\Component;

class MaterialsCreate extends Component
{
    public $name = '';
    public $category_id = '';
    public $description = '';
    public $unit_measure = '';
    public $unit_price = 0;
    public $piece_size = 0;
    public $piece_price = 0;
    public $is_by_piece = false;
    public $has_dimensions = false;
    public $width = 0;
    public $height = 0;
    public $price_type = 'per_unit';
    public $material_type = 'units';
    public $categories = [];

    public function getAvailableUnitsProperty()
    {
        if ($this->has_dimensions) {
            return [
                'metros cuadrados' => 'metros cuadrados',
                'metros cúbicos' => 'metros cúbicos',
                'placas' => 'placas',
                'hojas' => 'hojas'
            ];
        } elseif ($this->is_by_piece) {
            return [
                'metros lineales' => 'metros lineales',
                'metros cuadrados' => 'metros cuadrados',
                'piezas' => 'piezas',
                'placas' => 'placas',
                'tubos' => 'tubos',
                'rollos' => 'rollos'
            ];
        } else {
            return [
                'unidades' => 'unidades',
                'piezas' => 'piezas',
                'kilogramos' => 'kilogramos',
                'gramos' => 'gramos',
                'litros' => 'litros',
                'mililitros' => 'mililitros',
                'cajas' => 'cajas',
                'paquetes' => 'paquetes'
            ];
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'unit_measure' => 'required|string|max:100',
            'unit_price' => ($this->is_by_piece || $this->has_dimensions) ? 'nullable' : 'required|numeric|min:0',
            'piece_size' => $this->is_by_piece ? 'required|numeric|min:0.001' : 'nullable',
            'piece_price' => $this->is_by_piece ? 'required|numeric|min:0' : 'nullable',
            'width' => $this->has_dimensions ? 'required|numeric|min:0.001' : 'nullable',
            'height' => $this->has_dimensions ? 'required|numeric|min:0.001' : 'nullable',
            'is_by_piece' => 'boolean',
            'has_dimensions' => 'boolean'
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del material es obligatorio.',
        'unit_measure.required' => 'La unidad de medida es obligatoria.',
        'unit_price.required_if' => 'El precio unitario es obligatorio para materiales por unidad.',
        'piece_size.required_if' => 'El tamaño de pieza es obligatorio para materiales por pieza.',
        'piece_price.required_if' => 'El precio de pieza es obligatorio para materiales por pieza.',
    ];

    public function mount()
    {
        $this->categories = \App\Models\Category::orderBy('name')->get();
        $this->material_type = 'units';
        $this->setUnitDefaults();
    }

    public function updatedIsByPiece()
    {
        if ($this->is_by_piece) {
            $this->unit_price = 0;
            $this->has_dimensions = false;
            $this->width = 0;
            $this->height = 0;
            if (empty($this->unit_measure)) {
                $this->unit_measure = 'metros lineales';
            }
        } else {
            $this->piece_size = 0;
            $this->piece_price = 0;
        }
        $this->resetErrorBag();
    }

    public function updatedHasDimensions()
    {
        if ($this->has_dimensions) {
            $this->is_by_piece = false;
            $this->piece_size = 0;
            $this->piece_price = 0;
            $this->unit_measure = 'metros cuadrados';
        } else {
            $this->width = 0;
            $this->height = 0;
        }
        $this->resetErrorBag();
    }

    public function setUnitDefaults()
    {
        if (!$this->is_by_piece && !$this->has_dimensions && empty($this->unit_measure)) {
            $this->unit_measure = 'unidades';
        }
    }

    public function updatedMaterialType()
    {
        $this->is_by_piece = false;
        $this->has_dimensions = false;
        $this->piece_size = 0;
        $this->piece_price = 0;
        $this->width = 0;
        $this->height = 0;
        switch ($this->material_type) {
            case 'units':
                $this->unit_measure = 'unidades';
                break;
            case 'pieces':
                $this->is_by_piece = true;
                $this->unit_measure = 'metros lineales';
                break;
            case 'dimensions':
                $this->has_dimensions = true;
                $this->unit_measure = 'metros cuadrados';
                break;
        }
        $this->resetErrorBag();
    }

    public function updatedPriceType()
    {
        $this->resetErrorBag(['unit_price']);
    }

    public function save()
    {
        try {
            $this->validate();
            if ($this->is_by_piece && $this->piece_size) {
                $this->piece_size = number_format($this->piece_size, 3, '.', '');
            }
            $materialData = [
                'name' => trim($this->name),
                'category_id' => $this->category_id,
                'description' => trim($this->description) ?: null,
                'unit_measure' => $this->unit_measure,
                'unit_price' => ($this->is_by_piece || $this->has_dimensions) ? (float)$this->unit_price : (float)$this->unit_price,
                'piece_size' => $this->is_by_piece ? (float)$this->piece_size : 0,
                'piece_price' => $this->is_by_piece ? (float)$this->piece_price : 0,
                'is_by_piece' => (bool)$this->is_by_piece,
                'has_dimensions' => (bool)$this->has_dimensions,
                'width' => $this->has_dimensions ? (float)$this->width : null,
                'height' => $this->has_dimensions ? (float)$this->height : null,
            ];
            Material::create($materialData);
            $this->dispatch('materialCreated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el material: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.materials.create');
    }
}
