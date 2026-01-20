<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'unit_measure',
        'unit_price',
        'piece_size',
        'piece_price',
        'is_by_piece',
        'has_dimensions',
        'width',
        'height',
        'calculated_area',
        'stock_quantity',
        'min_stock_alert',
        'last_purchase_date',
        'last_used_date',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'piece_size' => 'decimal:3',
        'piece_price' => 'decimal:2',
        'is_by_piece' => 'boolean',
        'has_dimensions' => 'boolean',
        'width' => 'decimal:3',
        'height' => 'decimal:3',
        'calculated_area' => 'decimal:6',
        'category_id' => 'integer',
        'stock_quantity' => 'integer',
        'min_stock_alert' => 'decimal:3',
        'last_purchase_date' => 'datetime',
        'last_used_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relación muchos a muchos con productos
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_material')
            ->withPivot([
                'calculation_formula',
                'notes'
            ])
            ->withTimestamps();
    }

    /**
     * Many-to-many relationship with colors
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'material_color')
            ->withPivot('category')
            ->withTimestamps();
    }

    /**
     * Relación con movimientos de inventario
     */
    public function movements()
    {
        return $this->hasMany(MaterialMovement::class);
    }

    /**
     * Relación con retazos/sobrantes
     */
    public function remainders()
    {
        return $this->hasMany(MaterialRemainder::class);
    }

    /**
     * Relación de pertenencia a una categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Calcula el área cuando se actualizan las dimensiones
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($material) {
            if ($material->has_dimensions && $material->width && $material->height) {
                $material->calculated_area = $material->width * $material->height;
            } else {
                $material->calculated_area = null;
            }
        });
    }

    /**
     * Calcula el precio por unidad
     */
    public function getPricePerUnitAttribute(): float
    {
        if ($this->has_dimensions && $this->calculated_area > 0) {
            // Para materiales por dimensiones: precio por m²
            return $this->unit_price;
        } elseif ($this->is_by_piece && $this->piece_size > 0) {
            // Para materiales por pieza: dividir precio de pieza entre su tamaño
            return $this->piece_price / $this->piece_size;
        }

        // Para materiales por unidad: usar precio unitario directo
        return $this->unit_price;
    }

    /**
     * Obtiene el área total disponible
     */
    public function getTotalAreaAttribute(): float
    {
        return $this->calculated_area ?? 0;
    }

    /**
     * Calcula el costo REAL para una cantidad específica usada
     * (No cobra pieza completa, solo lo que se usa)
     */
    public function calculateCost(float $usedQuantity, float $wastePercentage = 0): float
    {
        $totalQuantity = $usedQuantity * (1 + ($wastePercentage / 100));

        if ($this->is_by_piece) {
            // Calcular costo proporcional basado en lo que se usa
            $pricePerUnit = $this->price_per_unit;
            return $totalQuantity * $pricePerUnit;
        }

        // Para materiales por unidad (tornillos, etc.)
        return $totalQuantity * $this->unit_price;
    }

    /**
     * Calcula cuántas piezas completas se necesitan comprar
     * (Para control de inventario, no para costeo)
     */
    public function calculatePiecesNeeded(float $usedQuantity, float $wastePercentage = 0): int
    {
        if (!$this->is_by_piece) {
            return 0; // No aplica para materiales por unidad
        }

        $totalQuantity = $usedQuantity * (1 + ($wastePercentage / 100));
        return ceil($totalQuantity / $this->piece_size);
    }

    /**
     * Calcula el costo de compra total (piezas completas)
     * (Para control de inventario y compras)
     */
    public function calculatePurchaseCost(float $usedQuantity, float $wastePercentage = 0): float
    {
        if (!$this->is_by_piece) {
            return $this->calculateCost($usedQuantity, $wastePercentage);
        }

        $piecesNeeded = $this->calculatePiecesNeeded($usedQuantity, $wastePercentage);
        return $piecesNeeded * $this->piece_price;
    }

    /**
     * Get colors available for a specific category
     */
    public function getColorsForCategory(string $category): BelongsToMany
    {
        return $this->colors()->wherePivot('category', $category);
    }

    /**
     * Calculate cost with color increment
     */
    public function calculateCostWithColor(float $usedQuantity, Color $color, float $wastePercentage = 0): float
    {
        $baseCost = $this->calculateCost($usedQuantity, $wastePercentage);
        $increment = $color->percentage_increment;

        return $baseCost * (1 + ($increment / 100));
    }

    // ==================== MÉTODOS DE INVENTARIO ====================

    /**
     * Obtiene el total de material disponible
     * Para materiales por pieza: (stock_quantity × piece_size) + suma de todos los retazos disponibles
     * Para materiales por unidad: stock_quantity
     */
    public function getTotalAvailableAttribute(): float
    {
        if ($this->is_by_piece) {
            // Stock de piezas completas
            $fullPiecesTotal = $this->stock_quantity * $this->piece_size;

            // Suma de todos los retazos disponibles
            $remaindersTotal = $this->remainders()
                ->available()
                ->sum('remaining_length');

            // Ejemplo: 10 piezas × 6.4m + (2.3m + 1.5m + 0.8m retazos) = 68.6m disponibles
            return $fullPiecesTotal + $remaindersTotal;
        }

        // Para materiales por unidad (tornillos, etc.)
        return $this->stock_quantity;
    }

    /**
     * Verifica si hay suficiente stock para una cantidad requerida
     */
    public function hasEnoughStock(float $quantityNeeded): bool
    {
        return $this->total_available >= $quantityNeeded;
    }

    /**
     * Deduce stock cuando se usa material en producción
     * Retorna el ID del retazo usado (si aplica), o null si se usó pieza nueva o material por unidad
     * 
     * IMPORTANTE: Para materiales por pieza (is_by_piece=true), cada corte debe ser
     * de UNA pieza continua. NO se pueden combinar retazos de diferentes cortes.
     * 
     * Algoritmo "Best Fit":
     * 1. Buscar el retazo más pequeño que sea suficiente (optimización)
     * 2. Si no hay retazo suficiente, tomar pieza nueva del stock
     * 3. Registrar el nuevo retazo si sobra material
     * 
     * Nota: Este método NO registra el movimiento automáticamente.
     * Usa MaterialMovement::recordMovement() para registrar la transacción completa.
     * 
     * @return array ['success' => bool, 'remainder_id' => int|null]
     */
    public function deductStock(float $quantityUsed, ?int $materialMovementId = null): array
    {
        if (!$this->hasEnoughStock($quantityUsed)) {
            return ['success' => false, 'remainder_id' => null];
        }

        $usedRemainderId = null;

        if ($this->is_by_piece) {
            // ESTRATEGIA BEST-FIT: Buscar el retazo más pequeño que sirva
            $bestFitRemainder = $this->remainders()
                ->available()
                ->sufficientFor($quantityUsed)
                ->orderBy('remaining_length', 'asc')
                ->first();

            if ($bestFitRemainder) {
                // Guardar el ID del retazo ANTES de modificarlo
                $usedRemainderId = $bestFitRemainder->id;

                // CASO 1: Tenemos un retazo que sirve
                if ($bestFitRemainder->remaining_length == $quantityUsed) {
                    // Uso exacto: marcar retazo como usado completamente
                    $unit = $this->unit_measure == 'metros_cuadrados' ? 'm²' : 'm';
                    $bestFitRemainder->markAsUsed("Usado exactamente: {$quantityUsed}{$unit}");
                } else {
                    // Sobra material: ACTUALIZAR el retazo existente con la nueva longitud
                    $leftover = $bestFitRemainder->remaining_length - $quantityUsed;
                    $unit = $this->unit_measure == 'metros_cuadrados' ? 'm²' : 'm';
                    $bestFitRemainder->remaining_length = $leftover;
                    $bestFitRemainder->notes = "Reducido de " . ($leftover + $quantityUsed) . "{$unit} a {$leftover}{$unit} (usado {$quantityUsed}{$unit})";
                    $bestFitRemainder->save();
                }
            } else {
                // CASO 2: No hay retazo suficiente, tomar pieza nueva
                $this->stock_quantity -= 1;

                // Calcular sobrante de la pieza nueva
                $leftover = $this->piece_size - $quantityUsed;

                // SIEMPRE crear un retazo para esta pieza, incluso si se usa completa
                // Esto permite tener el árbol genealógico completo desde el inicio
                $unit = $this->unit_measure == 'metros_cuadrados' ? 'm²' : 'm';
                $newRemainder = MaterialRemainder::createRemainder(
                    $this->id,
                    $leftover > 0.01 ? $leftover : 0, // Si se usa toda la pieza, crear retazo con 0
                    $materialMovementId,
                    $leftover > 0.01 
                        ? "Sobrante de pieza nueva ({$this->piece_size}{$unit}) después de usar {$quantityUsed}{$unit}"
                        : "Pieza nueva ({$this->piece_size}{$unit}) usada completamente"
                );
                
                // Retornar el ID del retazo recién creado
                $usedRemainderId = $newRemainder->id;
                
                // Si se usó toda la pieza, marcar el retazo como usado inmediatamente
                if ($leftover <= 0.01) {
                    $unit = $this->unit_measure == 'metros_cuadrados' ? 'm²' : 'm';
                    $newRemainder->markAsUsed("Pieza nueva ({$this->piece_size}{$unit}) usada completamente");
                }
            }
        } else {
            // Para materiales por unidad (tornillos, ruedas, etc), simplemente restar
            $this->stock_quantity -= $quantityUsed;
        }

        $this->last_used_date = now();
        $this->save();

        return ['success' => true, 'remainder_id' => $usedRemainderId];
    }

    /**
     * Añade stock cuando se compra o recibe material
     * Nota: Este método NO registra el movimiento automáticamente.
     * Usa MaterialMovement::recordMovement() para registrar la transacción completa.
     */
    public function addStock(float $quantityAdded, bool $isCompletePieces = true): void
    {
        if ($this->is_by_piece && $isCompletePieces) {
            // Si se compran piezas completas, añadir al stock de piezas
            $this->stock_quantity += $quantityAdded;
        } else {
            // Si es material por unidad o se añade cantidad parcial
            $this->stock_quantity += $quantityAdded;
        }

        $this->last_purchase_date = now();
        $this->save();
    }

    /**
     * Verifica si el material está por debajo del nivel mínimo de alerta
     */
    public function isLowStock(): bool
    {
        if ($this->min_stock_alert <= 0) {
            return false; // No hay alerta configurada
        }

        return $this->total_available <= $this->min_stock_alert;
    }

    /**
     * Obtiene el porcentaje de stock restante respecto al mínimo
     */
    public function getStockPercentageAttribute(): float
    {
        if ($this->min_stock_alert <= 0) {
            return 100; // Sin alerta configurada, asumimos 100%
        }

        return ($this->total_available / $this->min_stock_alert) * 100;
    }

    /**
     * Scope para materiales con stock bajo
     * Nota: Usa el atributo total_available que ya incluye retazos
     */
    public function scopeLowStock($query)
    {
        // Filtrar en PHP porque total_available es un atributo calculado
        return $query->where('is_active', true)
            ->where('min_stock_alert', '>', 0)
            ->get()
            ->filter(function ($material) {
                return $material->total_available <= $material->min_stock_alert;
            });
    }

    /**
     * Scope para obtener materiales activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener materiales sin stock
     * Verifica que no haya piezas completas ni retazos disponibles
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('is_active', true)
            ->where('stock_quantity', 0)
            ->whereDoesntHave('remainders', function ($q) {
                $q->where('status', 'available');
            });
    }
}
