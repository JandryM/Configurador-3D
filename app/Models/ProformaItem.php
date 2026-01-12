<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proforma_id',
        'product_id',
        'configuration',
        'quantity',
        'price',
        'notes',
        // Snapshot de costos calculados
        'material_cost',
        'direct_cost',
        'indirect_cost',
        'waste_cost',
        'profit_amount',
        'total_cost',
        'profit_margin_percentage',
    ];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
