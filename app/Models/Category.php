<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relación uno a muchos con productos
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relación muchos a muchos con materiales
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'category_material')
                    ->withTimestamps();
    }

    /**
     * Relación muchos a muchos con colores
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'category_color')
                    ->withTimestamps();
    }

    /**
     * Scope para categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
