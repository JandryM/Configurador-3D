<?php

namespace App\Livewire\Admin;

use App\Models\Material;
use Livewire\Component;

class MaterialForm extends Component
{
    public $materialId;
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
    public $price_type = 'per_unit'; // 'per_unit' o 'total_piece'
    public $material_type = 'units'; // 'units', 'pieces', 'dimensions'

    public $categories = [];

    // Unidades de medida por tipo
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

    public function mount($material = null)
    {
        $this->categories = \App\Models\Category::orderBy('name')->get();
        if ($material) {
            $this->materialId = $material->id;
            $this->name = $material->name;
            $this->category_id = $material->category_id;
            $this->description = $material->description;
            $this->unit_measure = $material->unit_measure;
            $this->unit_price = $material->unit_price;
            $this->piece_size = $material->piece_size;
            $this->piece_price = $material->piece_price;
            $this->is_by_piece = $material->is_by_piece;
            $this->has_dimensions = $material->has_dimensions;
            $this->width = $material->width;
            $this->height = $material->height;
            
            // Determinar material_type basado en los flags existentes
            if ($this->has_dimensions) {
                $this->material_type = 'dimensions';
            } elseif ($this->is_by_piece) {
                $this->material_type = 'pieces';
            } else {
                $this->material_type = 'units';
            }
            
            // Determinar tipo de precio basado en los valores existentes
            if ($material->has_dimensions && $material->calculated_area > 0) {
                $this->price_type = ($material->unit_price > 0) ? 'per_unit' : 'total_piece';
            }
        } else {
            // Establecer valores por defecto para nuevo material
            $this->material_type = 'units';
            $this->setUnitDefaults();
        }
    }

    public function updatedIsByPiece()
    {
        // Limpiar campos no necesarios cuando cambia el tipo
        if ($this->is_by_piece) {
            $this->unit_price = 0;
            $this->has_dimensions = false;
            $this->width = 0;
            $this->height = 0;
            // Auto-seleccionar unidad por defecto para piezas
            if (empty($this->unit_measure)) {
                $this->unit_measure = 'metros lineales';
            }
        } else {
            $this->piece_size = 0;
            $this->piece_price = 0;
        }
        
        // Limpiar errores de validación
        $this->resetErrorBag();
    }

    public function updatedHasDimensions()
    {
        // Limpiar campos no necesarios cuando cambia el tipo
        if ($this->has_dimensions) {
            $this->is_by_piece = false;
            $this->piece_size = 0;
            $this->piece_price = 0;
            // Auto-seleccionar metros cuadrados para dimensiones
            $this->unit_measure = 'metros cuadrados';
        } else {
            $this->width = 0;
            $this->height = 0;
        }
        
        // Limpiar errores de validación
        $this->resetErrorBag();
    }

    public function setUnitDefaults()
    {
        // Auto-seleccionar unidad por defecto según el tipo
        if (!$this->is_by_piece && !$this->has_dimensions && empty($this->unit_measure)) {
            $this->unit_measure = 'unidades';
        }
    }

    public function updatedMaterialType()
    {
        // Resetear todos los flags
        $this->is_by_piece = false;
        $this->has_dimensions = false;
        
        // Limpiar campos relacionados
        $this->piece_size = 0;
        $this->piece_price = 0;
        $this->width = 0;
        $this->height = 0;
        
        // Configurar según el tipo seleccionado
        switch ($this->material_type) {
            case 'units':
                // Por unidades individuales
                $this->unit_measure = 'unidades';
                break;
                
            case 'pieces':
                // Por piezas completas
                $this->is_by_piece = true;
                $this->unit_measure = 'metros lineales';
                break;
                
            case 'dimensions':
                // Por dimensiones
                $this->has_dimensions = true;
                $this->unit_measure = 'metros cuadrados';
                break;
        }
        
        // Limpiar errores de validación
        $this->resetErrorBag();
    }

    public function updatedPriceType()
    {
        // No hacer nada especial, los computed properties manejarán los cálculos
        $this->resetErrorBag(['unit_price']);
    }

    public function save()
    {
        try {
            $this->validate();

            // Ajustar piece_size a 3 decimales si es por pieza
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

            if ($this->materialId) {
                Material::findOrFail($this->materialId)->update($materialData);
            } else {
                Material::create($materialData);
            }

            $this->dispatch('materialSaved');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Los errores de validación se manejan automáticamente
            throw $e;
        } catch (\Exception $e) {
            // Para otros errores, mostrar mensaje
            session()->flash('error', 'Error al guardar el material: ' . $e->getMessage());
        }
    }

    public function getPricePerUnitProperty()
    {
        if ($this->is_by_piece && $this->piece_size > 0) {
            return $this->piece_price / $this->piece_size;
        }
        return $this->unit_price;
    }

    public function getCalculatedAreaProperty()
    {
        if ($this->has_dimensions && $this->width > 0 && $this->height > 0) {
            return $this->width * $this->height;
        }
        return 0;
    }

    public function getPricePerSquareMeterProperty()
    {
        if ($this->has_dimensions && $this->calculatedArea > 0) {
            if ($this->price_type === 'total_piece') {
                // Si es precio total, calcular precio por m²
                return $this->unit_price / $this->calculatedArea;
            } else {
                // Si es precio por m², usar directamente
                return $this->unit_price;
            }
        }
        return 0;
    }

    public function getTotalPiecePriceProperty()
    {
        if ($this->has_dimensions && $this->calculatedArea > 0) {
            if ($this->price_type === 'per_unit') {
                // Si es precio por m², calcular total
                return $this->unit_price * $this->calculatedArea;
            } else {
                // Si es precio total, usar directamente
                return $this->unit_price;
            }
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.admin.material-form');
    }
}
