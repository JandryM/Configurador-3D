<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'proforma_id',
        'status',
        'payment_proof',
        'product_created_at',
        'estimated_finish_at',
    ];

    protected $casts = [
        'product_created_at' => 'datetime',
        'estimated_finish_at' => 'datetime',
    ];

    /**
     * Get the proforma that owns the order.
     */
    public function proforma(): BelongsTo
    {
        return $this->belongsTo(Proforma::class);
    }

    /**
     * Get the client name from the associated proforma's user.
     */
    public function getClientNameAttribute()
    {
        return $this->proforma->user->name ?? 'N/A';
    }

    /**
     * Get the material movements associated with the order.
     */
    public function materialMovements()
    {
        return $this->hasMany(MaterialMovement::class);
    }
}
