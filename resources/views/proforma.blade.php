@php use Illuminate\Support\Str; @endphp
<style>
    .proforma-header { text-align: center; margin-bottom: 20px; }
    .proforma-section { margin-bottom: 20px; }
    .proforma-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .proforma-table th, .proforma-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
    .proforma-table th { background: #f5f5f5; }
    .proforma-total { font-weight: bold; font-size: 16px; }
</style>

<div class="proforma-header text-gray-800">
    <h2>Proforma de Producto</h2>
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
    <h4 class="font-semibold mb-2">Parámetros Seleccionados</h4>
    <table class="proforma-table text-gray-800">
        <tbody>
        @php
            $paramLabels = [
                'width' => 'Ancho',
                'height' => 'Alto',
                'depth' => 'Profundidad',
                'frameWidth' => 'Ancho del Marco',
                'color' => 'Color del Marco',
                'glassColor' => 'Color del Vidrio',
                'notes' => 'Notas',
                // Agrega aquí más traducciones si tienes más parámetros
            ];
        @endphp
        @if(empty($parameters))
            <tr><td colspan="2" class="text-center text-gray-400">Sin parámetros</td></tr>
        @else
            @foreach($parameters as $key => $value)
                <tr>
                    <th>{{ $paramLabels[$key] ?? Str::headline($key) }}</th>
                    <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>


@php
    $totalMateriales = collect($materialCosts)->sum('total_cost');
    $directCost = $directCost ?? null;
    $indirectCost = $indirectCost ?? null;
    $directAmount = $directCost ? $totalMateriales * ($directCost / 100) : 0;
    $indirectAmount = $indirectCost ? $totalMateriales * ($indirectCost / 100) : 0;
@endphp

<div class="proforma-section text-gray-800">
    <h4 class="font-semibold mb-2">Desglose de Materiales</h4>
    <table class="proforma-table text-gray-800">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Unidad</th>
                <th>Precio Unitario</th>
                <th>Incremento Color</th>
                <th>Costo Total</th>
            </tr>
        </thead>
        <tbody>
        @if(empty($materialCosts))
            <tr><td colspan="6" class="text-center text-gray-400">Sin materiales</td></tr>
        @else
            @foreach($materialCosts as $mat)
                <tr>
                    <td>{{ $mat['name'] ?? '-' }}</td>
                    <td>{{ $mat['quantity'] ?? '-' }}</td>
                    <td>{{ $mat['unit'] ?? '-' }}</td>
                    <td>${{ isset($mat['unit_price']) ? number_format($mat['unit_price'], 2) : '-' }}</td>
                    <td>${{ isset($mat['color_increase']) ? number_format($mat['color_increase'], 2) : '-' }}</td>
                    <td>${{ isset($mat['total_cost']) ? number_format($mat['total_cost'], 2) : '-' }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="mt-2">
        <div><strong>Total Materiales:</strong> ${{ number_format($totalMateriales, 2) }}</div>
        @if($directCost)
            <div><strong>Mano de Obra ({{ $directCost }}%):</strong> ${{ number_format($directAmount, 2) }}</div>
        @endif
        @if($indirectCost)
            <div><strong>Costos Indirectos ({{ $indirectCost }}%):</strong> ${{ number_format($indirectAmount, 2) }}</div>
        @endif
    </div>
</div>

<div class="proforma-section proforma-total text-gray-800">
    Precio Total: ${{ number_format($calculatedPrice, 2) }}
</div>

@if(!empty($notes))
<div class="proforma-section">
    <strong>Notas del Cliente:</strong>
    <p>{{ $notes }}</p>
</div>
@endif

@if(isset($showDownloadButton) && $showDownloadButton)
<div class="flex justify-end mt-4">
    <button type="button" wire:click="downloadProformaPdf" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
</div>
@endif
