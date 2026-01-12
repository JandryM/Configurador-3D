<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRemainder extends Model
{
    protected $fillable = [
        'material_id',
        'remaining_length',
        'material_movement_id',
        'status',
        'notes',
        'used_at'
    ];

    protected $casts = [
        'remaining_length' => 'decimal:3',
        'used_at' => 'datetime',
    ];

    /**
     * Relación con el material
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Relación con el movimiento que generó este retazo
     */
    public function materialMovement(): BelongsTo
    {
        return $this->belongsTo(MaterialMovement::class);
    }

    /**
     * Relación con todos los movimientos que usaron este retazo
     * (historial de cortes sobre esta pieza)
     */
    public function movements()
    {
        return $this->hasMany(MaterialMovement::class, 'material_remainder_id');
    }

    /**
     * Scope para obtener solo retazos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope para obtener retazos de un material específico
     */
    public function scopeForMaterial($query, int $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    /**
     * Scope para buscar retazos suficientes para una cantidad específica
     */
    public function scopeSufficientFor($query, float $length)
    {
        return $query->where('remaining_length', '>=', $length);
    }

    /**
     * Scope para ordenar por mejor ajuste (el más pequeño que sirva)
     */
    public function scopeBestFit($query, float $length)
    {
        return $query->where('remaining_length', '>=', $length)
            ->orderBy('remaining_length', 'asc');
    }

    /**
     * Marcar retazo como usado
     */
    public function markAsUsed(?string $notes = null): void
    {
        $this->status = 'used';
        $this->used_at = now();

        if ($notes) {
            $this->notes = $this->notes ? $this->notes . ' | ' . $notes : $notes;
        }

        $this->save();
    }

    /**
     * Reducir la longitud del retazo cuando se usa parcialmente
     */
    public function reduceLength(float $usedLength): void
    {
        $this->remaining_length -= $usedLength;
        $this->save();
    }

    /**
     * Crear un nuevo retazo
     */
    public static function createRemainder(
        int $materialId,
        float $remainingLength,
        ?int $materialMovementId = null,
        ?string $notes = null
    ): self {
        return self::create([
            'material_id' => $materialId,
            'remaining_length' => $remainingLength,
            'material_movement_id' => $materialMovementId,
            'status' => 'available',
            'notes' => $notes
        ]);
    }
}
