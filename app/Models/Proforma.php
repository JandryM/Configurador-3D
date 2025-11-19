<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'user_id',
        'total_price',
        'is_ordered',
        'expiration_date',
        'is_expired',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'is_ordered' => 'boolean',
        'is_expired' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(ProformaItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Verificar si la proforma ha expirado y actualizarla si es necesario
     */
    public function checkAndUpdateExpiration()
    {
        if (!$this->is_expired && now()->greaterThan($this->expiration_date)) {
            $this->update(['is_expired' => true]);
            return true;
        }
        return $this->is_expired;
    }

    /**
     * Calcular y actualizar el precio total basado en los ítems
     */
    public function updateTotalPrice()
    {
        $total = $this->items()->sum('price');
        $this->update(['total_price' => $total]);
        return $total;
    }
}
