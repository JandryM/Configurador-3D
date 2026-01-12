<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMovement extends Model
{
    protected $fillable = [
        'material_id',
        'quantity',
        'stock_before',
        'stock_after',
        'order_id',
        'user_id',
        'material_remainder_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    /**
     * Relación con el material
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Relación con la orden
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el usuario que realizó el movimiento
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los retazos generados por este movimiento
     */
    public function remainders()
    {
        return $this->hasMany(MaterialRemainder::class);
    }

    /**
     * Relación con el retazo usado en este movimiento
     */
    public function usedRemainder(): BelongsTo
    {
        return $this->belongsTo(MaterialRemainder::class, 'material_remainder_id');
    }


    /**
     * Registra un movimiento de inventario durante producción
     */
    /**
     * Registra un movimiento de inventario durante producción
     * @param Material $material
     * @param float $quantity
     * @param int|null $orderId
     * @param int|null $userId
     * @param int|null $parentMovementId
     * @param int|null $materialRemainderId
     * @param float|null $remainderQuantityUsed
     * @return self
     */
    public static function recordMovement(
        Material $material,
        float $quantity,
        ?int $orderId = null,
        ?int $userId = null,
        ?int $parentMovementId = null,
        ?int $materialRemainderId = null,
        ?float $remainderQuantityUsed = null
    ): self {
        // Capturar el estado antes del movimiento
        $stockBefore = $material->stock_quantity;

        // Crear el registro del movimiento ANTES de aplicar los cambios
        // para que los retazos puedan referenciar este movimiento
        $movement = self::create([
            'material_id' => $material->id,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockBefore, // Se actualizará después
            'order_id' => $orderId,
            'user_id' => $userId,
            'material_remainder_id' => $materialRemainderId,
        ]);

        // Deducir material y obtener el ID del retazo usado (si aplica)
        $deductResult = $material->deductStock($quantity, $movement->id);
        
        if (!$deductResult['success']) {
            $movement->delete(); // Eliminar el movimiento si falla
            throw new \Exception('Stock insuficiente para deducir');
        }

        // Refrescar el material para obtener los valores actualizados
        $material->refresh();

        // Si se usó un retazo y no se pasó manualmente, actualizar con el ID del retazo usado
        $finalRemainderId = $materialRemainderId ?? $deductResult['remainder_id'];
        
        // Actualizar el movimiento con los valores finales
        $movement->update([
            'stock_after' => $material->stock_quantity,
            'material_remainder_id' => $finalRemainderId
        ]);

        return $movement;
    }

}
