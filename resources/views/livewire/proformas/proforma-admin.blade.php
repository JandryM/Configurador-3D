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
</style>

<div class="proforma-header text-gray-800">
    <h2>Proforma de Producto - Vista Administrativa</h2>
    <p class="font-bold text-lg mb-1">{{ $product->name }}</p>
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
    $directAmount = $directCost ? $totalMateriales * ($directCost / 100) : 0;
    $indirectAmount = $indirectCost ? $totalMateriales * ($indirectCost / 100) : 0;
    $subtotal = $totalMateriales + $directAmount + $indirectAmount;
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
                <tr class="highlight-row">
                    <th>Costo Total de Producción</th>
                    <td class="text-right">${{ number_format($subtotal, 2) }}</td>
                </tr>
                @if($calculatedPrice > $subtotal)
                <tr>
                    <th>Margen / Utilidad</th>
                    <td class="text-right text-green-700">
                        ${{ number_format($calculatedPrice - $subtotal, 2) }}
                        ({{ number_format((($calculatedPrice - $subtotal) / $subtotal) * 100, 1) }}%)
                    </td>
                </tr>
                @endif
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

@if(isset($showDownloadButton) && $showDownloadButton)
<div class="flex justify-end mt-4">
    <button type="button" wire:click="downloadProformaPdf" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
</div>
@endif
