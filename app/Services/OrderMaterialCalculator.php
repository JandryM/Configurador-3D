<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class OrderMaterialCalculator
{
    /**
     * Calcula los materiales necesarios para una orden completa
     * 
     * @param int $orderId ID de la orden
     * @return array ['materials' => [...], 'insufficient' => [...], 'canProduce' => bool]
     */
    public function calculateOrderMaterials(int $orderId): array
    {
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            return ['materials' => [], 'insufficient' => [], 'canProduce' => false, 'error' => 'Orden no encontrada'];
        }

        // Obtener todos los ítems activos de la proforma
        $items = DB::table('proforma_items')
            ->where('proforma_id', $order->proforma_id)
            ->where('is_active', true)
            ->get();

        $materialsNeeded = [];
        $canProduce = true;
        $insufficientMaterials = [];

        foreach ($items as $item) {
            $product = Product::find($item->product_id);

            if (!$product) {
                continue;
            }

            $configuration = json_decode($item->configuration, true) ?? [];
            $parameters = $configuration['parameters'] ?? [];
            $quantity = $item->quantity ?? 1;

            // Calcular materiales para este ítem
            $itemMaterials = $this->calculateItemMaterials($product, $parameters, $quantity);

            // Acumular materiales y mantener información de cortes individuales
            foreach ($itemMaterials as $materialId => $materialData) {
                if (!isset($materialsNeeded[$materialId])) {
                    $materialsNeeded[$materialId] = $materialData;
                    $materialsNeeded[$materialId]['cuts'] = []; // Array de cortes individuales
                } else {
                    $materialsNeeded[$materialId]['quantity_needed'] += $materialData['quantity_needed'];
                }

                // Almacenar cada corte individual para optimizar uso de retazos
                if (isset($materialData['cuts'])) {
                    foreach ($materialData['cuts'] as $cut) {
                        $materialsNeeded[$materialId]['cuts'][] = $cut;
                    }
                }
            }
        }

        // Verificar disponibilidad de stock para cada material
        foreach ($materialsNeeded as $materialId => &$materialData) {
            $material = Material::find($materialId);

            if (!$material) {
                continue;
            }

            $available = $material->total_available;
            $needed = $materialData['quantity_needed'];

            $materialData['available'] = $available;
            $materialData['has_enough'] = $material->hasEnoughStock($needed);

            if (!$materialData['has_enough']) {
                $canProduce = false;

                // Calcular desglose de inventario para materiales por pieza
                $stockPieces = 0;
                $remaindersTotal = 0;

                if ($material->is_by_piece) {
                    $stockPieces = $material->stock_quantity;
                    $remaindersTotal = $material->remainders()
                        ->where('status', 'available')
                        ->sum('remaining_length');
                }

                $insufficientMaterials[] = [
                    'material' => $material,
                    'needed' => $needed,
                    'available' => $available,
                    'missing' => $needed - $available
                ];
            }
        }

        return [
            'materials' => $materialsNeeded,
            'insufficient' => $insufficientMaterials,
            'canProduce' => $canProduce,
            'order' => $order
        ];
    }

    /**
     * Calcula los materiales necesarios para un ítem específico de la proforma
     */
    private function calculateItemMaterials(Product $product, array $parameters, int $quantity): array
    {
        $materials = [];

        // Validar que las dimensiones sean válidas
        $width = $parameters['width'] ?? 1.0;
        $height = $parameters['height'] ?? 1.0;

        if ($width <= 0 || $height <= 0) {
            return [];
        }

        foreach ($product->materials as $material) {
            $quantityPerUnit = $this->calculateMaterialQuantity($material, $parameters);

            // Para materiales por pieza, mantener los cortes individuales
            // Esto permite optimizar el uso de retazos
            $cuts = [];
            if ($material->is_by_piece) {
                // Cada unidad del producto puede tener múltiples cortes
                // Por ejemplo: ventana necesita 2m arriba + 2m abajo = 2 cortes de 2m cada uno
                $cutsPerUnit = $this->calculateCutsPerUnit($material, $parameters);

                for ($i = 0; $i < $quantity; $i++) {
                    foreach ($cutsPerUnit as $cut) {
                        $cuts[] = $cut;
                    }
                }
            }

            // Cantidad total necesaria
            $quantityNeeded = $quantityPerUnit * $quantity;

            $materials[$material->id] = [
                'material' => $material,
                'quantity_needed' => $quantityNeeded,
                'cuts' => $cuts
            ];
        }

        return $materials;
    }

    /**
     * Calcula los cortes individuales necesarios por unidad de producto
     * Esto permite optimizar el uso de retazos
     */
    private function calculateCutsPerUnit(Material $material, array $parameters): array
    {
        $cuts = [];

        // Obtener la fórmula de la tabla pivote
        $pivot = DB::table('product_material')
            ->where('material_id', $material->id)
            ->first();

        $formula = $pivot->calculation_formula ?? null;

        if (!$formula) {
            // Sin fórmula específica, asumir un solo corte con la cantidad calculada
            $width = $parameters['width'] ?? 1.0;
            $height = $parameters['height'] ?? 1.0;
            $area = $width * $height;

            $cuts[] = [
                'length' => $material->has_dimensions ? $area : ($width * $height),
                'description' => 'Corte calculado',
                'can_combine' => false
            ];
            return $cuts;
        }

        // Analizar la fórmula para detectar múltiples cortes
        // Fórmulas comunes: "2 * {width}" o "{width} * 2" significa 2 cortes de ancho
        //                   "2 * {height}" significa 2 cortes de alto
        //                   "2 * ({width} + {height})" significa perímetro con 2 cortes

        $width = $parameters['width'] ?? 1.0;
        $height = $parameters['height'] ?? 1.0;

        // CASO ESPECIAL: CAUCHO - Fórmula: (2 * (({width}/2) + {height})) * 2
        // El caucho se calcula por hoja: ancho se divide entre 2, alto mantiene la medida completa
        // Permite combinar retazos por cada lado de la hoja
        if (preg_match('/\(\s*2\s*\*\s*\(\s*\(\s*\{\s*width\s*\}\s*\/\s*2\s*\)\s*\+\s*\{\s*height\s*\}\s*\)\s*\)\s*\*\s*2/', $formula)) {
            $leafWidth = $width / 2;  // Ancho de cada hoja (se divide entre 2)
            $leafHeight = $height;     // Alto de cada hoja (mantiene la medida completa)

            // 2 hojas, cada una con perímetro (arriba, abajo, izquierda, derecha)
            for ($leaf = 1; $leaf <= 2; $leaf++) {
                // Por cada hoja: 1 arriba (ancho/2), 1 abajo (ancho/2), 2 laterales (alto completo)
                $cuts[] = [
                    'length' => $leafWidth,
                    'description' => "Hoja {$leaf} - Horizontal superior ({$leafWidth}m)",
                    'can_combine' => true,  // Permite combinar retazos
                    'group' => "leaf_{$leaf}_top"
                ];
                $cuts[] = [
                    'length' => $leafWidth,
                    'description' => "Hoja {$leaf} - Horizontal inferior ({$leafWidth}m)",
                    'can_combine' => true,
                    'group' => "leaf_{$leaf}_bottom"
                ];
                $cuts[] = [
                    'length' => $leafHeight,
                    'description' => "Hoja {$leaf} - Vertical izquierdo ({$leafHeight}m)",
                    'can_combine' => true,
                    'group' => "leaf_{$leaf}_left"
                ];
                $cuts[] = [
                    'length' => $leafHeight,
                    'description' => "Hoja {$leaf} - Vertical derecho ({$leafHeight}m)",
                    'can_combine' => true,
                    'group' => "leaf_{$leaf}_right"
                ];
            }
        }
        // CASO ESPECIAL: FELPA - Fórmula: {width}
        // La felpa va en la parte superior de cada hoja (ancho/2 por hoja)
        // Permite combinar retazos por hoja
        elseif (preg_match('/^\{\s*width\s*\}$/', trim($formula))) {
            $leafWidth = $width / 2;  // Ancho de cada hoja (se divide entre 2)

            // 2 hojas, cada una necesita felpa en la parte superior
            for ($leaf = 1; $leaf <= 2; $leaf++) {
                $cuts[] = [
                    'length' => $leafWidth,
                    'description' => "Hoja {$leaf} - Felpa superior ({$leafWidth}m)",
                    'can_combine' => true,  // Permite combinar retazos
                    'group' => "leaf_{$leaf}_top"
                ];
            }
        }
        // CASO ESPECIAL: VERTICAL CERRADO DE HOJA - Fórmula: {height} * 4
        // Son los laterales de cierre de cada hoja (NO se pueden combinar como aluminio)
        // Cada hoja tiene 2 laterales (izquierdo y derecho)
        elseif (preg_match('/\{\s*height\s*\}\s*\*\s*4|4\s*\*\s*\{\s*height\s*\}/', $formula)) {
            // 2 hojas, cada una con 2 laterales verticales
            for ($leaf = 1; $leaf <= 2; $leaf++) {
                $cuts[] = [
                    'length' => $height,
                    'description' => "Hoja {$leaf} - Vertical izquierdo ({$height}m)",
                    'can_combine' => false,  // NO se puede combinar (pieza continua)
                    'group' => "leaf_{$leaf}_left"
                ];
                $cuts[] = [
                    'length' => $height,
                    'description' => "Hoja {$leaf} - Vertical derecho ({$height}m)",
                    'can_combine' => false,
                    'group' => "leaf_{$leaf}_right"
                ];
            }
        }
        // CASO ESPECIAL: HORIZONTAL CERRADO DE HOJA - Fórmula: ({width}/2) * 4
        // Son los horizontales de cierre de cada hoja (NO se pueden combinar como aluminio)
        // Cada hoja tiene 2 horizontales (superior e inferior)
        elseif (preg_match('/\(\s*\{\s*width\s*\}\s*\/\s*2\s*\)\s*\*\s*4|4\s*\*\s*\(\s*\{\s*width\s*\}\s*\/\s*2\s*\)/', $formula)) {
            $leafWidth = $width / 2;  // Ancho de cada hoja (se divide entre 2)

            // 2 hojas, cada una con 2 horizontales
            for ($leaf = 1; $leaf <= 2; $leaf++) {
                $cuts[] = [
                    'length' => $leafWidth,
                    'description' => "Hoja {$leaf} - Horizontal superior ({$leafWidth}m)",
                    'can_combine' => false,  // NO se puede combinar (pieza continua)
                    'group' => "leaf_{$leaf}_top"
                ];
                $cuts[] = [
                    'length' => $leafWidth,
                    'description' => "Hoja {$leaf} - Horizontal inferior ({$leafWidth}m)",
                    'can_combine' => false,
                    'group' => "leaf_{$leaf}_bottom"
                ];
            }
        }
        // CASO ESPECIAL: VIDRIO - Fórmula: (({width}/2 - {frameWidth}*2) * ({height} - {frameWidth}*2)) * 2
        // Cada hoja necesita una plancha de vidrio completa (NO se puede combinar ni fragmentar)
        // El frameWidth (marco) se descuenta del área útil de cada hoja
        elseif (preg_match('/\(\s*\(\s*\{\s*width\s*\}\s*\/\s*2\s*-\s*\{\s*frameWidth\s*\}\s*\*\s*2\s*\)\s*\*\s*\(\s*\{\s*height\s*\}\s*-\s*\{\s*frameWidth\s*\}\s*\*\s*2\s*\)\s*\)\s*\*\s*2/', $formula)) {
            $frameWidth = $parameters['frameWidth'] ?? 0.05;

            // Calcular dimensiones del vidrio por hoja (descontando marco)
            $glassWidth = ($width / 2) - ($frameWidth * 2);   // Ancho útil por hoja
            $glassHeight = $height - ($frameWidth * 2);        // Alto útil
            $glassArea = $glassWidth * $glassHeight;           // Área por plancha

            // 2 hojas, cada una necesita una plancha de vidrio completa
            for ($leaf = 1; $leaf <= 2; $leaf++) {
                $cuts[] = [
                    'length' => $glassArea,
                    'description' => "Hoja {$leaf} - Vidrio ({$glassWidth}m × {$glassHeight}m = {$glassArea}m²)",
                    'can_combine' => false,  // NO se puede combinar (plancha completa)
                    'group' => "leaf_{$leaf}_glass",
                    'dimensions' => [
                        'width' => $glassWidth,
                        'height' => $glassHeight,
                        'area' => $glassArea
                    ]
                ];
            }
        }
        // Aluminio u otros materiales de perímetro completo
        elseif (preg_match('/2\s*\*\s*\{\s*width\s*\}|\{\s*width\s*\}\s*\*\s*2/', $formula)) {
            // 2 cortes horizontales (arriba y abajo) - NO se pueden combinar
            $cuts[] = ['length' => $width, 'description' => 'Corte horizontal superior', 'can_combine' => false];
            $cuts[] = ['length' => $width, 'description' => 'Corte horizontal inferior', 'can_combine' => false];
        } elseif (preg_match('/2\s*\*\s*\{\s*height\s*\}|\{\s*height\s*\}\s*\*\s*2/', $formula)) {
            // 2 cortes verticales (izquierda y derecha) - NO se pueden combinar
            $cuts[] = ['length' => $height, 'description' => 'Corte vertical izquierdo', 'can_combine' => false];
            $cuts[] = ['length' => $height, 'description' => 'Corte vertical derecho', 'can_combine' => false];
        } elseif (preg_match('/2\s*\*\s*\(\s*\{\s*width\s*\}\s*\+\s*\{\s*height\s*\}\s*\)/', $formula)) {
            // Perímetro completo: 2 cortes de ancho + 2 cortes de alto - NO se pueden combinar
            $cuts[] = ['length' => $width, 'description' => 'Corte horizontal superior', 'can_combine' => false];
            $cuts[] = ['length' => $width, 'description' => 'Corte horizontal inferior', 'can_combine' => false];
            $cuts[] = ['length' => $height, 'description' => 'Corte vertical izquierdo', 'can_combine' => false];
            $cuts[] = ['length' => $height, 'description' => 'Corte vertical derecho', 'can_combine' => false];
        } else {
            // Fórmula no reconocida, calcular como un solo corte
            $totalLength = $this->evaluateFormulaSafely($formula, $parameters);
            $cuts[] = ['length' => $totalLength, 'description' => 'Corte calculado por fórmula', 'can_combine' => false];
        }

        return $cuts;
    }

    /**
     * Calcula la cantidad de un material específico según la fórmula definida
     */
    private function calculateMaterialQuantity(Material $material, array $parameters): float
    {
        // Obtener la fórmula de la tabla pivote product_material
        $pivot = DB::table('product_material')
            ->where('material_id', $material->id)
            ->first();

        $formula = $pivot->calculation_formula ?? null;

        if (!$formula) {
            // Si no hay fórmula, calcular área o volumen
            $width = $parameters['width'] ?? 0;
            $height = $parameters['height'] ?? 0;
            $depth = $parameters['depth'] ?? 1.0;
            $area = $width * $height;
            $volume = $area * $depth;

            return $material->has_dimensions ? $area : $volume;
        }

        return $this->evaluateFormulaSafely($formula, $parameters);
    }

    /**
     * Evalúa una fórmula de forma segura
     */
    private function evaluateFormulaSafely(string $formula, array $parameters): float
    {
        // Extraer parámetros con la precisión original
        $width = $parameters['width'] ?? 0;
        $height = $parameters['height'] ?? 0;
        $depth = $parameters['depth'] ?? 0;
        $frameWidth = $parameters['frameWidth'] ?? 0.05;

        // Variables calculadas sin redondeo para mantener máxima precisión
        $area = $width * $height;
        $volume = $area * $depth;
        $perimeter = 2 * ($width + $height);

        // Reemplazar variables
        $safeFormula = str_replace(
            [
                '{width}',
                '{height}',
                '{depth}',
                '{frameWidth}',
                '{area}',
                '{volume}',
                '{perimeter}'
            ],
            [
                $width,
                $height,
                $depth,
                $frameWidth,
                $area,
                $volume,
                $perimeter
            ],
            $formula
        );

        try {
            $result = eval ("return $safeFormula;");
            return is_numeric($result) ? (float) $result : 0;
        } catch (\Throwable $e) {
            \Log::error('Error evaluando fórmula de material', [
                'formula' => $formula,
                'safe_formula' => $safeFormula,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Deduce el stock de materiales para una orden cuando pasa a producción
     * 
     * OPTIMIZACIÓN DE RETAZOS:
     * Para materiales por pieza, deduce cada corte individualmente.
     * Esto permite que el sistema use retazos disponibles en combinación con piezas nuevas.
     * 
     * Ejemplo: Si necesitas 4m (2 cortes de 2m) y tienes 2m en retazos:
     *  - Corte 1 (2m): Se toma del retazo disponible
     *  - Corte 2 (2m): Se toma de una pieza nueva
     *  Resultado: Optimiza uso de retazos en lugar de desperdiciarlos
     */
    public function deductOrderMaterials(int $orderId, int $userId): array
    {
        $calculation = $this->calculateOrderMaterials($orderId);

        if (!$calculation['canProduce']) {
            return [
                'success' => false,
                'error' => 'Stock insuficiente',
                'insufficient' => $calculation['insufficient']
            ];
        }

        $deducted = [];
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($calculation['materials'] as $materialId => $materialData) {
                $material = Material::find($materialId);

                if (!$material) {
                    continue;
                }

                // OPTIMIZACIÓN: Si el material es por pieza y tiene cortes individuales,
                // deducir cada corte por separado para maximizar uso de retazos
                if ($material->is_by_piece && !empty($materialData['cuts'])) {
                    $cutsProcessed = [];

                    // Para caucho (can_combine), agrupar cortes por hoja y aplicar estrategia de priorización
                    $canCombineAny = collect($materialData['cuts'])->contains('can_combine', true);

                    if ($canCombineAny) {
                        // ESTRATEGIA PRIORIZADA PARA CAUCHO CON VALIDACIÓN DE CONSISTENCIA:
                        // Evita cortes fragmentados innecesarios

                        // 1. Calcular perímetro total por hoja (4 lados)
                        $cutsByLeaf = [];
                        foreach ($materialData['cuts'] as $cut) {
                            $group = $cut['group'] ?? 'unknown';
                            preg_match('/leaf_(\d+)_/', $group, $matches);
                            $leafNumber = $matches[1] ?? 1;

                            if (!isset($cutsByLeaf[$leafNumber])) {
                                $cutsByLeaf[$leafNumber] = [
                                    'cuts' => [],
                                    'total_length' => 0
                                ];
                            }

                            $cutsByLeaf[$leafNumber]['cuts'][] = $cut;
                            $cutsByLeaf[$leafNumber]['total_length'] += $cut['length'];
                        }

                        // Obtener todos los retazos disponibles una sola vez para análisis global
                        $material->refresh();
                        $availableRemainders = $material->remainders()
                            ->available()
                            ->orderBy('remaining_length', 'desc')
                            ->get();

                        // Procesar cada hoja con estrategia de priorización
                        foreach ($cutsByLeaf as $leafNumber => $leafData) {
                            $leafPerimeter = $leafData['total_length'];
                            $leafCuts = $leafData['cuts'];

                            // Actualizar lista de retazos disponibles
                            $material->refresh();
                            $availableRemainders = $material->remainders()
                                ->available()
                                ->orderBy('remaining_length', 'desc')
                                ->get();

                            // PRIORIDAD 1: Buscar retazo que cubra el perímetro completo de la hoja (con tolerancia de ±1cm)
                            $completeRemainder = $availableRemainders
                                ->where('remaining_length', '>=', $leafPerimeter - 0.01)
                                ->where('remaining_length', '<=', $leafPerimeter + 0.01)
                                ->sortBy('remaining_length') // Más ajustado primero
                                ->first();

                            if ($completeRemainder) {
                                // Usar retazo completo para toda la hoja
                                $parentMovementId = $completeRemainder->material_movement_id ?? null;
                                $movement = \App\Models\MaterialMovement::recordMovement(
                                    material: $material,
                                    quantity: $leafPerimeter,
                                    orderId: $orderId,
                                    userId: $userId,
                                    parentMovementId: $parentMovementId,
                                    materialRemainderId: $completeRemainder->id,
                                    remainderQuantityUsed: $leafPerimeter
                                );

                                $cutsProcessed[] = [
                                    'leaf' => $leafNumber,
                                    'total_length' => $leafPerimeter,
                                    'description' => "Hoja {$leafNumber} - Perímetro completo",
                                    'strategy' => 'complete_remainder',
                                    'remainder_id' => $completeRemainder->id,
                                    'pieces_used' => 1,
                                    'pieces_detail' => [
                                        [
                                            'length' => $leafPerimeter,
                                            'from_remainder' => true,
                                            'remainder_id' => $completeRemainder->id
                                        ]
                                    ]
                                ];

                                continue; // Pasar a la siguiente hoja
                            }

                            // PRIORIDAD 2: Evaluar si usar retazos disponibles creará fragmentación innecesaria
                            // Estrategia: Solo usar retazos si podemos cubrir lados completos sin crear fragmentos aislados

                            // Analizar retazos disponibles y determinar la mejor estrategia
                            $bestStrategy = $this->analyzeBestRemainderStrategy(
                                $availableRemainders,
                                $leafCuts,
                                $leafPerimeter
                            );

                            if ($bestStrategy['type'] === 'use_remainders_strategically') {
                                // Usar retazos de manera estratégica: asignar retazos a lados completos
                                // y el resto con piezas nuevas que cubran múltiples lados contiguos
                                $combinedCuts = [];
                                $sidesAssignment = $bestStrategy['assignment'];

                                foreach ($sidesAssignment as $assignment) {
                                    if ($assignment['source'] === 'remainder') {
                                        // Usar retazo para este(os) lado(s)
                                        $remainder = $material->remainders()->find($assignment['remainder_id']);
                                        $parentMovementId = $remainder ? $remainder->material_movement_id : null;
                                        $movement = \App\Models\MaterialMovement::recordMovement(
                                            material: $material,
                                            quantity: $assignment['length'],
                                            orderId: $orderId,
                                            userId: $userId,
                                            parentMovementId: $parentMovementId,
                                            materialRemainderId: $assignment['remainder_id'],
                                            remainderQuantityUsed: $assignment['length']
                                        );

                                        $combinedCuts[] = [
                                            'length' => $assignment['length'],
                                            'from_remainder' => true,
                                            'remainder_id' => $assignment['remainder_id'],
                                            'sides_covered' => $assignment['sides'],
                                            'strategy' => 'strategic_remainder'
                                        ];
                                    } else {
                                        // Usar pieza nueva para lado(s) contiguos
                                        $movement = \App\Models\MaterialMovement::recordMovement(
                                            material: $material,
                                            quantity: $assignment['length'],
                                            orderId: $orderId,
                                            userId: $userId
                                        );

                                        $combinedCuts[] = [
                                            'length' => $assignment['length'],
                                            'from_remainder' => false,
                                            'sides_covered' => $assignment['sides'],
                                            'strategy' => 'continuous_new_piece'
                                        ];
                                    }
                                }

                                $cutsProcessed[] = [
                                    'leaf' => $leafNumber,
                                    'total_length' => $leafPerimeter,
                                    'description' => "Hoja {$leafNumber} - Estrategia optimizada sin fragmentación",
                                    'strategy' => 'strategic_no_fragmentation',
                                    'pieces_used' => count($combinedCuts),
                                    'pieces_detail' => $combinedCuts
                                ];

                                continue; // Pasar a la siguiente hoja
                            }

                            // PRIORIDAD 3: Si no hay retazos o la estrategia es usar pieza nueva completa
                            if ($bestStrategy['type'] === 'new_piece_complete') {
                                // Usar pieza nueva para toda la hoja (más eficiente que fragmentar)
                                $movement = \App\Models\MaterialMovement::recordMovement(
                                    material: $material,
                                    quantity: $leafPerimeter,
                                    orderId: $orderId,
                                    userId: $userId
                                );

                                $cutsProcessed[] = [
                                    'leaf' => $leafNumber,
                                    'total_length' => $leafPerimeter,
                                    'description' => "Hoja {$leafNumber} - Pieza nueva completa",
                                    'strategy' => 'new_piece_complete',
                                    'pieces_used' => 1,
                                    'pieces_detail' => [
                                        [
                                            'length' => $leafPerimeter,
                                            'from_remainder' => false
                                        ]
                                    ]
                                ];

                                continue; // Pasar a la siguiente hoja
                            }

                            // PRIORIDAD 4: Combinar múltiples retazos pequeños por lado (última opción)
                            $leafCombinedCuts = [];
                            foreach ($leafCuts as $cut) {
                                $cutLength = $cut['length'];
                                $cutDescription = $cut['description'] ?? "Corte de {$cutLength}m";
                                $group = $cut['group'] ?? null;

                                $remainingLength = $cutLength;
                                $sideCombinedCuts = [];

                                while ($remainingLength > 0.01) {
                                    $material->refresh();

                                    // Buscar el retazo por ID entre todos los retazos, no solo los disponibles
                                    $bestRemainder = $material->remainders()
                                        ->where('remaining_length', '<=', $remainingLength + 0.01)
                                        ->orderBy('remaining_length', 'desc')
                                        ->first();

                                    if ($bestRemainder) {
                                        $usedLength = min($bestRemainder->remaining_length, $remainingLength);
                                        $parentMovementId = $bestRemainder->material_movement_id ?? null;
                                        $movement = \App\Models\MaterialMovement::recordMovement(
                                            material: $material,
                                            quantity: $usedLength,
                                            orderId: $orderId,
                                            userId: $userId,
                                            parentMovementId: $parentMovementId,
                                            materialRemainderId: $bestRemainder->id,
                                            remainderQuantityUsed: $usedLength
                                        );

                                        $sideCombinedCuts[] = [
                                            'length' => $usedLength,
                                            'from_remainder' => true,
                                            'remainder_id' => $bestRemainder->id
                                        ];

                                        $remainingLength -= $usedLength;
                                    } else {
                                        $movement = \App\Models\MaterialMovement::recordMovement(
                                            material: $material,
                                            quantity: $remainingLength,
                                            orderId: $orderId,
                                            userId: $userId
                                        );

                                        $sideCombinedCuts[] = [
                                            'length' => $remainingLength,
                                            'from_remainder' => false
                                        ];

                                        $remainingLength = 0;
                                    }
                                }

                                $leafCombinedCuts[] = [
                                    'side' => $group,
                                    'length' => $cutLength,
                                    'description' => $cutDescription,
                                    'pieces' => $sideCombinedCuts
                                ];
                            }

                            $cutsProcessed[] = [
                                'leaf' => $leafNumber,
                                'total_length' => $leafPerimeter,
                                'description' => "Hoja {$leafNumber} - Múltiples retazos por lado",
                                'strategy' => 'multiple_small_remainders',
                                'sides_detail' => $leafCombinedCuts
                            ];
                        }

                    } else {
                        // ALUMINIO: NO se pueden combinar retazos, debe ser de una sola pieza
                        foreach ($materialData['cuts'] as $cut) {
                            $cutLength = $cut['length'];
                            $cutDescription = $cut['description'] ?? "Corte de {$cutLength}{$material->unit_measure}";

                            $movement = \App\Models\MaterialMovement::recordMovement(
                                material: $material,
                                quantity: $cutLength,
                                orderId: $orderId,
                                userId: $userId
                            );

                            $cutsProcessed[] = [
                                'length' => $cutLength,
                                'description' => $cutDescription,
                                'movement_id' => $movement->id,
                                'combined' => false
                            ];
                        }
                    }

                    $deducted[] = [
                        'material' => $material->name,
                        'quantity' => $materialData['quantity_needed'],
                        'cuts_count' => count($cutsProcessed),
                        'cuts' => $cutsProcessed,
                        'optimized' => true
                    ];

                } else {
                    // Para materiales por unidad o sin cortes específicos,
                    // deducir la cantidad total de una vez
                    $movement = \App\Models\MaterialMovement::recordMovement(
                        material: $material,
                        quantity: $materialData['quantity_needed'],
                        orderId: $orderId,
                        userId: $userId
                    );

                    $deducted[] = [
                        'material' => $material->name,
                        'quantity' => $materialData['quantity_needed'],
                        'movement_id' => $movement->id,
                        'optimized' => false
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
                'deducted' => $deducted
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'error' => 'Error al deducir materiales: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Analiza la mejor estrategia para usar retazos sin crear fragmentación innecesaria
     * 
     * Evita escenarios como:
     * - Usar 3m de retazo en partes separadas que requieran 4m adicionales de corte
     * - Mejor usar 2m de retazo + 5m de pieza continua
     * 
     * @param \Illuminate\Support\Collection $availableRemainders Retazos disponibles
     * @param array $leafCuts Cortes necesarios para la hoja (4 lados)
     * @param float $leafPerimeter Perímetro total de la hoja
     * @return array Estrategia recomendada
     */
    private function analyzeBestRemainderStrategy($availableRemainders, array $leafCuts, float $leafPerimeter): array
    {
        if ($availableRemainders->isEmpty()) {
            return ['type' => 'new_piece_complete'];
        }

        // Organizar lados por longitud para análisis
        $sides = [];
        foreach ($leafCuts as $cut) {
            $sides[] = [
                'length' => $cut['length'],
                'description' => $cut['description'],
                'group' => $cut['group'] ?? ''
            ];
        }

        // Ordenar lados por longitud (más largos primero para optimizar)
        usort($sides, fn($a, $b) => $b['length'] <=> $a['length']);

        // Evaluar diferentes estrategias
        $strategies = [];

        // ESTRATEGIA 1: Asignar retazos a lados completos sin crear fragmentos
        $strategy1 = $this->evaluateStrategicRemainderAssignment($availableRemainders, $sides, $leafPerimeter);
        if ($strategy1) {
            $strategies[] = $strategy1;
        }

        // Seleccionar la mejor estrategia (menor número de cortes y sin fragmentación)
        if (!empty($strategies)) {
            usort($strategies, function ($a, $b) {
                // Priorizar: menos cortes y mayor uso de retazos
                $aCuts = $a['cuts_count'];
                $bCuts = $b['cuts_count'];

                if ($aCuts !== $bCuts) {
                    return $aCuts <=> $bCuts;
                }

                // Si mismo número de cortes, priorizar mayor uso de retazos
                $aRemainders = $a['remainder_usage'];
                $bRemainders = $b['remainder_usage'];

                return $bRemainders <=> $aRemainders;
            });

            return $strategies[0];
        }

        // Si no hay estrategia válida, usar pieza nueva completa
        return ['type' => 'new_piece_complete'];
    }

    /**
     * Evalúa asignación estratégica de retazos a lados completos
     * 
     * Reglas:
     * 1. Asignar retazos solo a lados completos que puedan cubrir
     * 2. Los lados restantes deben poder cubrirse con una pieza continua
     * 3. Evitar fragmentación: no usar retazo si deja lados aislados que requieran cortes extras
     * 4. MAXIMIZAR uso de retazos: Si mismo número de cortes, usar más del retazo
     */
    private function evaluateStrategicRemainderAssignment($availableRemainders, array $sides, float $leafPerimeter): ?array
    {
        $totalSides = count($sides);

        // Intentar diferentes combinaciones de asignación
        $bestAssignment = null;
        $minCuts = PHP_INT_MAX;
        $maxRemainderUsage = 0;

        // Evaluar: usar 1 retazo para múltiples lados contiguos
        foreach ($availableRemainders as $remainder) {
            $remainderLength = $remainder->remaining_length;

            // Probar TODAS las combinaciones posibles de lados contiguos
            // Esto maximiza el uso del retazo disponible

            // Combinación 1: 1 lado
            foreach ($sides as $index => $side) {
                $sideLength = $side['length'];

                if (abs($remainderLength - $sideLength) <= 0.01) {
                    $remainingSides = array_filter($sides, fn($key) => $key !== $index, ARRAY_FILTER_USE_KEY);
                    $remainingLength = array_sum(array_column($remainingSides, 'length'));

                    $this->evaluateAndUpdateBestAssignment(
                        $bestAssignment,
                        $minCuts,
                        $maxRemainderUsage,
                        2,
                        $sideLength,
                        $remainder->id,
                        [['source' => 'remainder', 'length' => $sideLength, 'remainder_id' => $remainder->id, 'sides' => [$side['description']]]],
                        [['source' => 'new_piece', 'length' => $remainingLength, 'sides' => array_column($remainingSides, 'description')]]
                    );
                }
            }

            // Combinación 2: 2 lados contiguos
            for ($i = 0; $i < $totalSides - 1; $i++) {
                $twoSidesLength = $sides[$i]['length'] + $sides[$i + 1]['length'];

                if (abs($remainderLength - $twoSidesLength) <= 0.01) {
                    $remainingSides = array_filter($sides, fn($key) => $key !== $i && $key !== $i + 1, ARRAY_FILTER_USE_KEY);
                    $remainingLength = array_sum(array_column($remainingSides, 'length'));

                    $this->evaluateAndUpdateBestAssignment(
                        $bestAssignment,
                        $minCuts,
                        $maxRemainderUsage,
                        2,
                        $twoSidesLength,
                        $remainder->id,
                        [['source' => 'remainder', 'length' => $twoSidesLength, 'remainder_id' => $remainder->id, 'sides' => [$sides[$i]['description'], $sides[$i + 1]['description']]]],
                        [['source' => 'new_piece', 'length' => $remainingLength, 'sides' => array_column($remainingSides, 'description')]]
                    );
                }
            }

            // Combinación 3: 3 lados contiguos
            for ($i = 0; $i < $totalSides - 2; $i++) {
                $threeSidesLength = $sides[$i]['length'] + $sides[$i + 1]['length'] + $sides[$i + 2]['length'];

                if (abs($remainderLength - $threeSidesLength) <= 0.01) {
                    $remainingSides = array_filter($sides, fn($key) => $key !== $i && $key !== $i + 1 && $key !== $i + 2, ARRAY_FILTER_USE_KEY);
                    $remainingLength = array_sum(array_column($remainingSides, 'length'));

                    $this->evaluateAndUpdateBestAssignment(
                        $bestAssignment,
                        $minCuts,
                        $maxRemainderUsage,
                        2,
                        $threeSidesLength,
                        $remainder->id,
                        [['source' => 'remainder', 'length' => $threeSidesLength, 'remainder_id' => $remainder->id, 'sides' => [$sides[$i]['description'], $sides[$i + 1]['description'], $sides[$i + 2]['description']]]],
                        [['source' => 'new_piece', 'length' => $remainingLength, 'sides' => array_column($remainingSides, 'description')]]
                    );
                }
            }
        }

        // Si encontramos una asignación válida con 2 cortes, es mejor que fragmentar
        if ($bestAssignment && $minCuts === 2) {
            return $bestAssignment;
        }

        // Si no hay buena asignación, mejor usar pieza nueva completa
        return null;
    }

    /**
     * Evalúa y actualiza la mejor asignación de retazos
     * Prioriza: 1) Menos cortes, 2) Mayor uso de retazos
     */
    private function evaluateAndUpdateBestAssignment(
        ?array &$bestAssignment,
        int &$minCuts,
        float &$maxRemainderUsage,
        int $cutsCount,
        float $remainderUsage,
        int $remainderId,
        array $remainderParts,
        array $newPieceParts
    ): void {
        $shouldUpdate = false;

        // Prioridad 1: Menos cortes
        if ($cutsCount < $minCuts) {
            $shouldUpdate = true;
        }
        // Prioridad 2: Mismo número de cortes pero más uso de retazos
        elseif ($cutsCount === $minCuts && $remainderUsage > $maxRemainderUsage) {
            $shouldUpdate = true;
        }

        if ($shouldUpdate) {
            $minCuts = $cutsCount;
            $maxRemainderUsage = $remainderUsage;

            $bestAssignment = [
                'type' => 'use_remainders_strategically',
                'cuts_count' => $cutsCount,
                'remainder_usage' => $remainderUsage,
                'assignment' => array_merge($remainderParts, $newPieceParts)
            ];
        }
    }
}