<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'unit_measure',
        'unit_price',
        'piece_size',
        'piece_price',
        'is_by_piece',
        'has_dimensions',
        'width',
        'height',
        'calculated_area'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'piece_size' => 'decimal:3',
        'piece_price' => 'decimal:2',
        'is_by_piece' => 'boolean',
        'has_dimensions' => 'boolean',
        'width' => 'decimal:3',
        'height' => 'decimal:3',
        'calculated_area' => 'decimal:6'
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_material')
                    ->withPivot([
                        'quantity', 
                        'used_quantity', 
                        'waste_percentage', 
                        'calculation_formula', 
                        'calculated_cost', 
                        'notes'
                    ])
                    ->withTimestamps();
    }

    /**
     * Calcula el área cuando se actualizan las dimensiones
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($material) {
            if ($material->has_dimensions && $material->width && $material->height) {
                $material->calculated_area = $material->width * $material->height;
            } else {
                $material->calculated_area = null;
            }
        });
    }

    /**
     * Calcula el precio por unidad
     */
    public function getPricePerUnitAttribute(): float
    {
        if ($this->has_dimensions && $this->calculated_area > 0) {
            // Para materiales por dimensiones: precio por m²
            return $this->unit_price;
        } elseif ($this->is_by_piece && $this->piece_size > 0) {
            // Para materiales por pieza: dividir precio de pieza entre su tamaño
            return $this->piece_price / $this->piece_size;
        }
        
        // Para materiales por unidad: usar precio unitario directo
        return $this->unit_price;
    }

    /**
     * Obtiene el área total disponible
     */
    public function getTotalAreaAttribute(): float
    {
        return $this->calculated_area ?? 0;
    }

    /**
     * Calcula el costo REAL para una cantidad específica usada
     * (No cobra pieza completa, solo lo que se usa)
     */
    public function calculateCost(float $usedQuantity, float $wastePercentage = 0): float
    {
        $totalQuantity = $usedQuantity * (1 + ($wastePercentage / 100));
        
        if ($this->is_by_piece) {
            // Calcular costo proporcional basado en lo que se usa
            $pricePerUnit = $this->price_per_unit;
            return $totalQuantity * $pricePerUnit;
        }
        
        // Para materiales por unidad (tornillos, etc.)
        return $totalQuantity * $this->unit_price;
    }

    /**
     * Calcula cuántas piezas completas se necesitan comprar
     * (Para control de inventario, no para costeo)
     */
    public function calculatePiecesNeeded(float $usedQuantity, float $wastePercentage = 0): int
    {
        if (!$this->is_by_piece) {
            return 0; // No aplica para materiales por unidad
        }
        
        $totalQuantity = $usedQuantity * (1 + ($wastePercentage / 100));
        return ceil($totalQuantity / $this->piece_size);
    }

    /**
     * Calcula el costo de compra total (piezas completas)
     * (Para control de inventario y compras)
     */
    public function calculatePurchaseCost(float $usedQuantity, float $wastePercentage = 0): float
    {
        if (!$this->is_by_piece) {
            return $this->calculateCost($usedQuantity, $wastePercentage);
        }
        
        $piecesNeeded = $this->calculatePiecesNeeded($usedQuantity, $wastePercentage);
        return $piecesNeeded * $this->piece_price;
    }
}
