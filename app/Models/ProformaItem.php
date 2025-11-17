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
