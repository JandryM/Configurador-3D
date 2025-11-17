@php use Illuminate\Support\Str; @endphp
<style>
    .proforma-header { text-align: center; margin-bottom: 20px; }
    .proforma-section { margin-bottom: 20px; }
    .proforma-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .proforma-table th, .proforma-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
    .proforma-table th { background: #f5f5f5; }
    .proforma-total { font-weight: bold; font-size: 16px; }
    .cost-breakdown { background: #f9fafb; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
    .highlight-row { background: #fef3c7; font-weight: bold; }
    .item-card { background: #f9fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3b82f6; }
</style>

@php
    // Detectar si es multi-item o single-item
    $isMultiItem = isset($items) && is_array($items) && count($items) > 0;
    $displayNumber = $number ?? 'PRF-' . str_pad($proforma_id ?? 0, 3, '0', STR_PAD_LEFT);
@endphp

<div class="proforma-header text-gray-800">
    <h2>Proforma de Producto{{ $isMultiItem && count($items) > 1 ? 's' : '' }} - Vista Administrativa</h2>
    <p class="font-bold text-lg mb-1">{{ $displayNumber }}</p>
    <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    @if(isset($user) && $user)
        <div class="mt-2 text-sm text-left" style="margin: 0 auto; max-width: 400px;">
            <strong>Cliente:</strong> {{ $user->name ?? $user->email ?? 'Usuario' }}<br>
            @if(!empty($user->email))<strong>Email:</strong> {{ $user->email }}<br>@endif
            @if(!empty($user->phone))<strong>Teléfono:</strong> {{ $user->phone }}<br>@endif
            @if(!empty($user->address))<strong>Dirección:</strong> {{ $user->address }}<br>@endif
            @if(!empty($user->province))<strong>Provincia:</strong> {{ $user->province }}<br>@endif
            @if(!empty($user->city))<strong>Ciudad:</strong> {{ $user->city }}<br>@endif
        </div>
    @endif
</div>

@if($isMultiItem)
    <!-- Vista Multi-Item -->
    <div class="proforma-section text-gray-800">
        <h4 class="font-semibold mb-2">Información General de la Proforma</h4>
        <table class="proforma-table text-gray-800">
            <tbody>
                <tr>
                    <th width="40%">Número de Proforma</th>
                    <td>{{ $displayNumber }}</td>
                </tr>
                <tr>
                    <th>Total de Ítems</th>
                    <td>{{ count($items) }} {{ count($items) == 1 ? 'producto' : 'productos' }}</td>
                </tr>
                <tr>
                    <th>Fecha de Expiración</th>
                    <td>{{ isset($expiration_date) ? \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                <tr>
                    <th>¿Expirada?</th>
                    <td>
                        @if(isset($is_expired))
                            <span style="color: {{ $is_expired ? '#dc2626' : '#059669' }}; font-weight: bold;">
                                {{ $is_expired ? 'Sí' : 'No' }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Ítems de la Proforma -->
    @foreach($items as $index => $item)
    <div class="item-card">
        <h4 class="font-bold text-lg mb-3 text-gray-800">Configuración #{{ $index + 1 }}: {{ $item['product_name'] }}</h4>
        
        <!-- Datos del Producto -->
        <div class="proforma-section text-gray-800">
            <h5 class="font-semibold mb-2">Datos del Producto</h5>
            <table class="proforma-table text-gray-800">
                <tbody>
                    <tr>
                        <th width="40%">Nombre del Producto</th>
                        <td>{{ $item['product_name'] }}</td>
                    </tr>
                    @if(isset($item['parameters']['color']))
                    <tr>
                        <th>Color Aluminio</th>
                        <td>{{ $item['parameters']['color'] }}</td>
                    </tr>
                    @endif
                    @if(isset($item['parameters']['glassColor']))
                    <tr>
                        <th>Color Vidrio</th>
                        <td>{{ $item['parameters']['glassColor'] }}</td>
                    </tr>
                    @endif
                    @if(isset($item['parameters']['width']) && isset($item['parameters']['height']))
                    <tr>
                        <th>Dimensiones</th>
                        <td>
                            Ancho: {{ $item['parameters']['width'] }} m, Alto: {{ $item['parameters']['height'] }} m
                            @if(isset($item['parameters']['depth']))
                                , Profundidad: {{ $item['parameters']['depth'] }} m
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Cantidad</th>
                        <td>{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</td>
                    </tr>
                    <tr>
                        <th>Precio Unitario</th>
                        <td>${{ number_format($item['price'] / max(1, $item['quantity']), 2) }}</td>
                    </tr>
                    <tr class="highlight-row">
                        <th>Subtotal Ítem</th>
                        <td>${{ number_format($item['price'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Materiales -->
        @if(!empty($item['materialCosts']))
        @php
            $totalMateriales = collect($item['materialCosts'])->sum('total_cost');
        @endphp
        <div class="proforma-section text-gray-800">
            <h5 class="font-semibold mb-2">Desglose de Materiales</h5>
            <table class="proforma-table text-gray-800">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio Unit.</th>
                        <th>Inc. Color</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($item['materialCosts'] as $mat)
                    <tr>
                        <td>{{ $mat['name'] ?? '-' }}</td>
                        <td>{{ number_format($mat['quantity'] ?? 0, 2) }}</td>
                        <td>{{ $mat['unit'] ?? '-' }}</td>
                        <td>${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                        <td>${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                        <td>${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="highlight-row">
                    <td colspan="5" class="text-right"><strong>Subtotal Materiales:</strong></td>
                    <td><strong>${{ number_format($totalMateriales, 2) }}</strong></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Análisis de Costos -->
        @php
            $directCost = $item['directCost'] ?? 0;
            $indirectCost = $item['indirectCost'] ?? 0;
            $wastePercentage = $item['wastePercentage'] ?? 0;
            $profitMargin = $item['profitMargin'] ?? 0;
            $directAmount = $directCost ? $totalMateriales * ($directCost / 100) : 0;
            $indirectAmount = $indirectCost ? $totalMateriales * ($indirectCost / 100) : 0;
            $wasteAmount = $wastePercentage ? $totalMateriales * ($wastePercentage / 100) : 0;
            $subtotal = $totalMateriales + $directAmount + $indirectAmount + $wasteAmount;
            $profitAmount = $profitMargin ? $subtotal * ($profitMargin / 100) : 0;
        @endphp
        
        <div class="proforma-section text-gray-800">
            <h5 class="font-semibold mb-2">Análisis de Costos</h5>
            <div class="cost-breakdown">
                <table class="proforma-table text-gray-800">
                    <tbody>
                        <tr>
                            <th width="60%">Costo de Materiales</th>
                            <td class="text-right">${{ number_format($totalMateriales, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Costos Directos / Mano de Obra ({{ $directCost }}%)</th>
                            <td class="text-right">${{ number_format($directAmount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Costos Indirectos ({{ $indirectCost }}%)</th>
                            <td class="text-right">${{ number_format($indirectAmount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Material de Desperdicio ({{ $wastePercentage }}%)</th>
                            <td class="text-right">${{ number_format($wasteAmount, 2) }}</td>
                        </tr>
                        <tr class="highlight-row">
                            <th>Costo Total de Producción</th>
                            <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Margen de Ganancia ({{ $profitMargin }}%)</th>
                            <td class="text-right text-green-700">${{ number_format($profitAmount, 2) }}</td>
                        </tr>
                        <tr class="highlight-row" style="background: #dcfce7;">
                            <th>Precio Final</th>
                            <td class="text-right">${{ number_format($subtotal + $profitAmount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Notas del Ítem -->
        @if(!empty($item['notes']))
        <div class="proforma-section text-gray-800">
            <h5 class="font-semibold mb-2">Notas</h5>
            <div style="background: #f9fafb; padding: 10px; border-radius: 4px; border-left: 4px solid #3b82f6;">
                <p>{{ $item['notes'] }}</p>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <!-- Total de la Proforma -->
    <div class="proforma-section proforma-total text-gray-800" style="background: #dbeafe; padding: 15px; border-radius: 8px; text-align: center;">
        <span style="font-size:18px;">Total de la Proforma:</span>
        <span style="font-size:24px; font-weight:bold; color: #1e40af;">${{ number_format($total_price ?? 0, 2) }}</span>
        <p style="font-size:12px; margin-top: 5px; color: #64748b;">Incluye {{ count($items) }} {{ count($items) == 1 ? 'producto' : 'productos' }}</p>
    </div>

@else
    <!-- Vista Single-Item (Legacy) -->

<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Datos Generales del Producto</h4>
    <table class="proforma-table text-gray-800">
        <tbody>
            <tr>
                <th>Nombre del Producto</th>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <th>Color</th>
                <td>{{ $parameters['color'] ?? '-' }}</td>
            </tr>
            <tr>
                <th>Dimensiones</th>
                <td>
                    Ancho: {{ $parameters['width'] ?? '-' }} m,
                    Alto: {{ $parameters['height'] ?? '-' }} m
                    @if(isset($parameters['depth']))
                        , Profundidad: {{ $parameters['depth'] }} m
                    @endif
                </td>
            </tr>
            <tr>
                <th>Cantidad</th>
                <td>{{ $quantity ?? 1 }} {{ ($quantity ?? 1) == 1 ? 'unidad' : 'unidades' }}</td>
            </tr>
            <tr>
                <th>Precio Unitario</th>
                <td>${{ number_format($calculatedPrice / max(1, $quantity ?? 1), 2) }}</td>
            </tr>
            <tr>
                <th>Fecha de Expiración</th>
                <td>{{ isset($expiration_date) ? \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>¿Expirada?</th>
                <td>
                    @if(isset($is_expired))
                        <span style="color: {{ $is_expired ? '#dc2626' : '#059669' }}; font-weight: bold;">
                            {{ $is_expired ? 'Sí' : 'No' }}
                        </span>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Parámetros Detallados</h4>
    <table class="proforma-table text-gray-800">
        <tbody>
        @php
            $paramLabels = [
                'width' => 'Ancho (m)',
                'height' => 'Alto (m)',
                'depth' => 'Profundidad (m)',
                'frameWidth' => 'Ancho del Marco',
                'color' => 'Color del Marco',
                'glassColor' => 'Color del Vidrio',
                'notes' => 'Notas',
            ];
        @endphp
        @forelse($parameters as $key => $value)
            @if($key !== 'notes')
                <tr>
                    <th>{{ $paramLabels[$key] ?? Str::headline($key) }}</th>
                    <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                </tr>
            @endif
        @empty
            <tr><td colspan="2" class="text-center text-gray-400">Sin parámetros</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@php
    $totalMateriales = collect($materialCosts)->sum('total_cost');
    $directCost = $directCost ?? 0;
    $indirectCost = $indirectCost ?? 0;
    $wastePercentage = $wastePercentage ?? 0;
    $profitMargin = $profitMargin ?? 0;
    $directAmount = $directCost ? $totalMateriales * ($directCost / 100) : 0;
    $indirectAmount = $indirectCost ? $totalMateriales * ($indirectCost / 100) : 0;
    $wasteAmount = $wastePercentage ? $totalMateriales * ($wastePercentage / 100) : 0;
    $subtotal = $totalMateriales + $directAmount + $indirectAmount + $wasteAmount;
    $profitAmount = $profitMargin ? $subtotal * ($profitMargin / 100) : 0;
@endphp

<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Desglose Detallado de Materiales</h4>
    <table class="proforma-table text-gray-800">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Precio Unit.</th>
                <th>Inc. Color</th>
                <th>Costo Total</th>
            </tr>
        </thead>
        <tbody>
        @forelse($materialCosts as $mat)
            <tr>
                <td>{{ $mat['name'] ?? '-' }}</td>
                <td>{{ number_format($mat['quantity'] ?? 0, 2) }}</td>
                <td>{{ $mat['unit'] ?? '-' }}</td>
                <td>${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                <td>${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                <td>${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-gray-400">Sin materiales</td></tr>
        @endforelse
        @if(!empty($materialCosts))
            <tr class="highlight-row">
                <td colspan="5" class="text-right"><strong>Subtotal Materiales:</strong></td>
                <td><strong>${{ number_format($totalMateriales, 2) }}</strong></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Análisis de Costos</h4>
    <div class="cost-breakdown">
        <table class="proforma-table text-gray-800">
            <tbody>
                <tr>
                    <th width="60%">Costo de Materiales</th>
                    <td class="text-right">${{ number_format($totalMateriales, 2) }}</td>
                </tr>
                <tr>
                    <th>Costos Directos / Mano de Obra ({{ $directCost }}%)</th>
                    <td class="text-right">${{ number_format($directAmount, 2) }}</td>
                </tr>
                <tr>
                    <th>Costos Indirectos ({{ $indirectCost }}%)</th>
                    <td class="text-right">${{ number_format($indirectAmount, 2) }}</td>
                </tr>
                <tr>
                    <th>Material de Desperdicio ({{ $wastePercentage }}%)</th>
                    <td class="text-right">${{ number_format($wasteAmount, 2) }}</td>
                </tr>
                <tr class="highlight-row">
                    <th>Costo Total de Producción</th>
                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Margen de Ganancia ({{ $profitMargin }}%)</th>
                    <td class="text-right text-green-700">${{ number_format($profitAmount, 2) }}</td>
                </tr>
                <tr class="highlight-row" style="background: #dcfce7;">
                    <th>Precio Final al Cliente</th>
                    <td class="text-right">${{ number_format($subtotal + $profitAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="proforma-section proforma-total text-gray-800" style="background: #dbeafe; padding: 15px; border-radius: 8px; text-align: center;">
    <span style="font-size:18px;">Precio Final al Cliente:</span>
    <span style="font-size:24px; font-weight:bold; color: #1e40af;">${{ number_format($calculatedPrice, 2) }}</span>
</div>

@if(!empty($notes))
<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Notas del Cliente</h4>
    <div style="background: #f9fafb; padding: 10px; border-radius: 4px; border-left: 4px solid #3b82f6;">
        <p>{{ $notes }}</p>
    </div>
</div>
@endif
@endif

@if(isset($showDownloadButton) && $showDownloadButton)
<div class="flex justify-end mt-4">
    <button type="button" wire:click="downloadProformaPdf" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
</div>
@endif
