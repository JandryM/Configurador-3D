<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'category', // Mantener temporalmente para compatibilidad
        'product_type',
        'base_dimensions',
        'base_cost',
        'allows_customization',
        'image',
        'user_id',
        'is_gallery_visible',
        'model_3d_file',
        'model_3d_textures',
        'model_3d_materials',
        'model_3d_settings',
        'has_3d_model',
        'model_scale',
        'height',
        'width'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'base_cost' => 'decimal:2',
        'base_dimensions' => 'array',
        'model_3d_textures' => 'array',
        'model_3d_materials' => 'array',
        'model_3d_settings' => 'array',
        'is_gallery_visible' => 'boolean',
        'allows_customization' => 'boolean',
        'has_3d_model' => 'boolean',
        'model_scale' => 'decimal:4'
    ];

    /**
     * Relación con el usuario que creó el producto (administrador)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con la categoría del producto
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación muchos a muchos con colores
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_color')
                    ->withTimestamps();
    }

    /**
     * Relación con las personalizaciones del producto
     */
    public function customizations(): HasMany
    {
        return $this->hasMany(ProductCustomization::class);
    }

    /**
     * Relación muchos a muchos con materiales
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'product_material')
                    ->withPivot([
                        'calculation_formula',
                        'notes'
                    ])
                    ->withTimestamps();
    }

    /**
     * Calcula el costo total de materiales para este producto
     */
    public function calculateMaterialsCost(): float
    {
        $totalCost = 0;
        
        foreach ($this->materials as $material) {
            if ($material->pivot->calculated_cost) {
                $totalCost += $material->pivot->calculated_cost;
            } else {
                // Calcular si no está precalculado
                $usedQuantity = $material->pivot->used_quantity ?? $material->pivot->quantity;
                $wastePercentage = $material->pivot->waste_percentage ?? 0;
                $totalCost += $material->calculateCost($usedQuantity, $wastePercentage);
            }
        }
        
        return $totalCost;
    }

    /**
     * Scope para productos visibles en galería
     */
    public function scopeGalleryVisible($query)
    {
        return $query->where('is_gallery_visible', true);
    }

    /**
     * Scope para productos de galería (precio fijo)
     */
    public function scopeGalleryProducts($query)
    {
        return $query->where('product_type', 'gallery');
    }

    /**
     * Scope para productos personalizables (costeo por orden)
     */
    public function scopeCustomizableProducts($query)
    {
        return $query->where('product_type', 'customizable');
    }

    /**
     * Verifica si es un producto de galería
     */
    public function isGalleryProduct(): bool
    {
        return $this->product_type === 'gallery';
    }

    /**
     * Verifica si es un producto personalizable
     */
    public function isCustomizableProduct(): bool
    {
        return $this->product_type === 'customizable';
    }

    /**
     * Obtiene el precio basado en el tipo de producto
     */
    public function getDisplayPrice(): float
    {
        if ($this->isGalleryProduct()) {
            return $this->price ?? 0;
        }
        
        // Para productos personalizables, calcular el costo base más materiales
        $baseCost = $this->base_cost ?? 0;
        $materialsCost = $this->calculateMaterialsCost();
        
        return $baseCost + $materialsCost;
    }
}
