<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCustomization extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'custom_name',
        'custom_description',
        'custom_price',
        'modifications',
        'custom_image',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'custom_price' => 'decimal:2',
        'modifications' => 'array'
    ];

    /**
     * Relación con el producto base
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación con el usuario que hizo la personalización
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
