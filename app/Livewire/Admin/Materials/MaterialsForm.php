<?php

namespace App\Livewire\Admin\Materials;

use App\Models\Material;
use Livewire\Component;

class MaterialsForm extends Component
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
    public $area = 0; // Para cuando el usuario ingresa área directamente
    public $dimension_input_type = 'width_height'; // 'width_height' o 'direct_area'
    public $price_type = 'per_unit'; // 'per_unit' o 'total_piece'
    public $material_type = 'units'; // 'units', 'pieces', 'dimensions'

    public $categories = [];

    // Unidades de medida por tipo
    public function getAvailableUnitsProperty()
    {
        if ($this->has_dimensions) {
            return [
                'metros_cuadrados' => 'metros cuadrados',
                'metros cúbicos' => 'metros cúbicos',
                'placas' => 'placas',
                'hojas' => 'hojas'
            ];
        } elseif ($this->is_by_piece) {
            return [
                'metro_lineal' => 'metros lineales',
                'metros_cuadrados' => 'metros cuadrados',
                'piezas' => 'piezas',
                'placas' => 'placas',
                'tubos' => 'tubos',
                'rollos' => 'rollos'
            ];
        } else {
            return [
                'unidad' => 'unidad',
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
            'width' => ($this->has_dimensions && $this->dimension_input_type === 'width_height') ? 'required|numeric|min:0.001' : 'nullable',
            'height' => ($this->has_dimensions && $this->dimension_input_type === 'width_height') ? 'required|numeric|min:0.001' : 'nullable',
            'area' => ($this->has_dimensions && $this->dimension_input_type === 'direct_area') ? 'required|numeric|min:0.001' : 'nullable',
            'is_by_piece' => 'boolean',
            'has_dimensions' => 'boolean'
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre del material es obligatorio.',
        'name.string' => 'El nombre debe ser un texto.',
        'name.max' => 'El nombre no debe exceder 255 caracteres.',

        'category_id.required' => 'La categoría es obligatoria.',
        'category_id.exists' => 'La categoría seleccionada no es válida.',

        'description.string' => 'La descripción debe ser un texto.',

        'unit_measure.required' => 'La unidad de medida es obligatoria.',
        'unit_measure.string' => 'La unidad de medida debe ser un texto.',
        'unit_measure.max' => 'La unidad de medida no debe exceder 100 caracteres.',

        'unit_price.required' => 'El precio unitario es obligatorio.',
        'unit_price.numeric' => 'El precio unitario debe ser un número.',
        'unit_price.min' => 'El precio unitario no puede ser negativo.',
        'unit_price.required_if' => 'El precio unitario es obligatorio para materiales por unidad.',

        'piece_size.required' => 'El tamaño de pieza es obligatorio.',
        'piece_size.numeric' => 'El tamaño de pieza debe ser un número.',
        'piece_size.min' => 'El tamaño de pieza debe ser mayor a 0.',

        'piece_price.required' => 'El precio de pieza es obligatorio.',
        'piece_price.numeric' => 'El precio de pieza debe ser un número.',
        'piece_price.min' => 'El precio de pieza no puede ser negativo.',

        'width.required' => 'El ancho es obligatorio.',
        'width.numeric' => 'El ancho debe ser un número.',
        'width.min' => 'El ancho debe ser mayor a 0.',

        'height.required' => 'El alto es obligatorio.',
        'height.numeric' => 'El alto debe ser un número.',
        'height.min' => 'El alto debe ser mayor a 0.',

        'area.required' => 'El área es obligatoria.',
        'area.numeric' => 'El área debe ser un número.',
        'area.min' => 'El área debe ser mayor a 0.',

        'is_by_piece.boolean' => 'El campo "por pieza" debe ser verdadero o falso.',
        'has_dimensions.boolean' => 'El campo "con dimensiones" debe ser verdadero o falso.',
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
            $this->piece_size = $material->piece_size;
            $this->piece_price = $material->piece_price;
            $this->is_by_piece = $material->is_by_piece;
            $this->has_dimensions = $material->has_dimensions;
            $this->width = $material->width;
            $this->height = $material->height;
            
            // Cargar unit_price según el tipo de material
            if ($material->has_dimensions) {
                // Para materiales por dimensiones, mostrar el precio total de la pieza
                $this->unit_price = $material->piece_price;
            } elseif ($material->is_by_piece) {
                // Para materiales por piezas, mostrar el precio de la pieza
                $this->unit_price = $material->piece_price;
            } else {
                // Para materiales por unidades, mostrar el precio unitario
                $this->unit_price = $material->unit_price;
            }
            
            // Determinar tipo de entrada de dimensiones
            if ($material->width && $material->height) {
                $this->dimension_input_type = 'width_height';
                $this->area = $material->width * $material->height;
            } elseif ($material->piece_size > 0 && $material->has_dimensions) {
                $this->dimension_input_type = 'direct_area';
                $this->area = $material->piece_size;
            }
            
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
                $this->unit_measure = 'metro_lineal';
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
            $this->unit_measure = 'metros_cuadrados';
        } else {
            $this->width = 0;
            $this->height = 0;
            $this->area = 0;
        }
        
        // Limpiar errores de validación
        $this->resetErrorBag();
    }

    public function setUnitDefaults()
    {
        // Auto-seleccionar unidad por defecto según el tipo
        if (!$this->is_by_piece && !$this->has_dimensions && empty($this->unit_measure)) {
            $this->unit_measure = 'unidad';
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
        $this->area = 0;
        
        // Configurar según el tipo seleccionado
        switch ($this->material_type) {
            case 'units':
                // Por unidades individuales
                $this->unit_measure = 'unidad';
                break;
            case 'pieces':
                // Por piezas completas
                $this->is_by_piece = true;
                $this->unit_measure = 'metro_lineal';
                break;
            case 'dimensions':
                // Por dimensiones
                $this->has_dimensions = true;
                $this->unit_measure = 'metros_cuadrados';
                $this->dimension_input_type = 'width_height'; // Por defecto ancho x alto
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

    public function updatedDimensionInputType()
    {
        // Limpiar campos cuando cambia el tipo de entrada
        if ($this->dimension_input_type === 'width_height') {
            $this->area = 0;
        } else {
            $this->width = 0;
            $this->height = 0;
        }
        
        $this->resetErrorBag(['width', 'height', 'area']);
    }

    public function save()
    {
        try {
            $this->validate();

            // Ajustar piece_size a 3 decimales si es por pieza
            if ($this->is_by_piece && $this->piece_size) {
                $this->piece_size = number_format($this->piece_size, 3, '.', '');
            }

            // Calcular unit_price según el tipo de material
            $calculatedUnitPrice = $this->unit_price;
            
            if ($this->is_by_piece && $this->piece_size > 0) {
                // Para materiales por pieza: precio unitario = precio_pieza / tamaño_pieza
                $calculatedUnitPrice = $this->piece_price / $this->piece_size;
            } elseif ($this->has_dimensions && $this->calculatedArea > 0) {
                // Para materiales por dimensiones: SIEMPRE calcular precio por m²
                // El usuario ingresa el precio total de la pieza y nosotros calculamos el precio por m²
                // Área = ancho × alto (ejemplo: 10m × 20m = 200m²)
                // Precio por m² = precio_total / área (ejemplo: $60 / 200m² = $0.30 por m²)
                $calculatedUnitPrice = $this->unit_price / $this->calculatedArea;
            }

            $materialData = [
                'name' => trim($this->name),
                'category_id' => $this->category_id,
                'description' => trim($this->description) ?: null,
                'unit_measure' => $this->unit_measure,
                'unit_price' => (float)$calculatedUnitPrice,
                'piece_size' => $this->is_by_piece ? (float)$this->piece_size : ($this->has_dimensions ? (float)$this->calculatedArea : 0),
                'piece_price' => $this->is_by_piece ? (float)$this->piece_price : ($this->has_dimensions ? (float)$this->unit_price : 0),
                'is_by_piece' => (bool)$this->is_by_piece,
                'has_dimensions' => (bool)$this->has_dimensions,
                'width' => ($this->has_dimensions && $this->dimension_input_type === 'width_height') ? (float)$this->width : null,
                'height' => ($this->has_dimensions && $this->dimension_input_type === 'width_height') ? (float)$this->height : null,
            ];

            if ($this->materialId) {
                Material::findOrFail($this->materialId)->update($materialData);
                $this->dispatch('materialUpdated');
            } else {
                Material::create($materialData);
                $this->dispatch('materialCreated');
            }
            
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
        if ($this->has_dimensions) {
            if ($this->dimension_input_type === 'width_height' && $this->width > 0 && $this->height > 0) {
                return $this->width * $this->height;
            } elseif ($this->dimension_input_type === 'direct_area' && $this->area > 0) {
                return $this->area;
            }
        }
        return 0;
    }

    public function getPricePerSquareMeterProperty()
    {
        if ($this->has_dimensions && $this->calculatedArea > 0 && $this->unit_price > 0) {
            // Para materiales por dimensiones: precio por m² = precio_total / área
            return $this->unit_price / $this->calculatedArea;
        }
        return 0;
    }

    public function getTotalPiecePriceProperty()
    {
        if ($this->has_dimensions) {
            // Para materiales por dimensiones: el precio total es lo que ingresó el usuario
            return $this->unit_price;
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.admin.materials.material-form');
    }
}