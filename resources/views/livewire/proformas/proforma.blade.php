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

<div class="proforma-section proforma-total text-gray-800">
    <span style="font-size:18px;">Precio Final del Producto:</span>
    <span style="font-size:22px; font-weight:bold;">${{ number_format($calculatedPrice, 2) }}</span>
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
