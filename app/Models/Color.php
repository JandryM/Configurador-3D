<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    protected $fillable = [
        'color_name',
        'percentage_increment',
        'texture_path',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'percentage_increment' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Many-to-many relationship with materials
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_color')
                    ->withPivot('category')
                    ->withTimestamps();
    }

    /**
     * Many-to-many relationship with categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_color')
                    ->withTimestamps();
    }

    /**
     * Many-to-many relationship with products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_color')
                    ->withTimestamps();
    }

    /**
     * Scope for active colors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered colors
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('color_name', 'asc');
    }

    /**
     * Scope colors by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->whereHas('materials', function($q) use ($category) {
            $q->wherePivot('category', $category);
        });
    }

    /**
     * Get full texture path URL
     */
    public function getTextureUrlAttribute(): ?string
    {
        if (!$this->texture_path) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($this->texture_path, 'http')) {
            return $this->texture_path;
        }

        // Build relative URL
        return asset($this->texture_path);
    }
}
