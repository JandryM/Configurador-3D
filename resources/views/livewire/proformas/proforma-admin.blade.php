@php use Illuminate\Support\Str; @endphp

@php
    $isPdf = $isPdf ?? false;
    $isMultiItem = isset($items) && is_array($items) && count($items) > 0;
    $displayNumber = $number ?? 'PRF-' . str_pad($proforma_id ?? 0, 3, '0', STR_PAD_LEFT);
    $totalQuantity = $isMultiItem ? collect($items)->sum('quantity') : ($quantity ?? 1);
    $displaySubtotalSinIva = $isMultiItem
        ? collect($items)->sum(fn($item) => ((float) ($item['price'] ?? 0)) / 1.15)
        : ((float) ($calculatedPrice ?? $total_price ?? 0)) / 1.15;
    $displayIva = $displaySubtotalSinIva * 0.15;
    $displayTotalConIva = $displaySubtotalSinIva + $displayIva;
    $formatMaterialQuantity = function ($quantity, $unit) {
        $normalizedUnit = strtolower(trim((string) $unit));

        if (in_array($normalizedUnit, ['unidad', 'unidades'])) {
            return number_format((float) ($quantity ?? 0), 0, ',', '.');
        }

        return number_format((float) ($quantity ?? 0), 3, ',', '.');
    };

    $formatMaterialUnit = function ($unit) {
        $normalizedUnit = strtolower(trim((string) $unit));

        return match ($normalizedUnit) {
            'metro_lineal' => 'metros lineales',
            'metros_cuadrados' => 'metros cuadrados',
            'unidad' => 'unidades',
            default => str_replace('_', ' ', $unit ?: '-'),
        };
    };
@endphp

@if($isPdf)
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
        font-family: 'Arial', sans-serif; 
        color: #2d3748; 
        line-height: 1.4;
        background: #ffffff;
    }
    
    .invoice-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        position: relative;
    }
    
    /* Header con onda decorativa */
    .invoice-header {
        background: #0a4d68;
        padding: 10px 25px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .invoice-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 300px;
        height: 150px;
        background: #7dd3fc;
        opacity: 0.3;
        border-radius: 50%;
    }
    
    .header-content {
        display: table;
        width: 100%;
        position: relative;
        z-index: 2;
    }
    
    .company-logo {
        display: table-cell;
        vertical-align: middle;
        width: 80px;
    }
    
    .company-logo img {
        height: 40px;
        max-width: 60px;
        object-fit: contain;
        display: block;
    }
    
    .invoice-title {
        display: table-cell;
        vertical-align: middle;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: white;
        padding-left: 10px;
    }
    
    /* Info section */
    .invoice-info {
        padding: 5px 25px 3px 25px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .invoice-to {
        flex: 1;
        margin-top: 0;
    }
    
    .invoice-to h4 {
        font-size: 11px;
        color: #718096;
        margin-bottom: 3px;
        margin-top: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .invoice-to p {
        font-size: 12px;
        color: #2d3748;
        margin: 2px 0;
        line-height: 1.4;
    }
    
    .invoice-to .client-name {
        font-weight: 700;
        font-size: 13px;
        color: #1a202c;
    }
    
    .invoice-meta {
        text-align: right;
        margin-top: 0;
    }
    
    .invoice-badge {
        display: inline-block;
        background: #7dd3fc;
        color: #0a4d68;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .invoice-date {
        font-size: 11px;
        color: #718096;
        margin: 1px 0;
    }
    
    .invoice-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        margin-top: 3px;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-expired {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Decorative triangles */
    .triangle-decoration {
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 8px solid #7dd3fc;
        margin: 0 3px;
        opacity: 0.6;
    }
    
    /* Tabla de productos */
    .products-table {
        width: calc(100% - 50px);
        margin: 8px 25px;
        border-collapse: collapse;
    }
    
    .products-table thead {
        background: #0a4d68;
        color: white;
    }
    
    .products-table th {
        padding: 8px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .products-table th:first-child {
        border-radius: 8px 0 0 0;
    }
    
    .products-table th:last-child {
        border-radius: 0 8px 0 0;
        text-align: right;
    }
    
    .products-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
    }
    
    .products-table td {
        padding: 10px 12px;
        font-size: 12px;
        color: #4a5568;
    }
    
    .products-table td:last-child {
        text-align: right;
        font-weight: 600;
        color: #2d3748;
    }
    
    .products-table .qty-col {
        text-align: center;
        color: #718096;
    }
    
    /* Item cards */
    .item-card {
        background: #f7fafc;
        padding: 12px 25px;
        margin: 8px 0;
        border-left: 4px solid #7dd3fc;
        page-break-inside: avoid;
    }
    
    .item-card h4 {
        color: #0a4d68;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .item-details {
        font-size: 11px;
        color: #718096;
        margin-bottom: 8px;
    }
    
    .item-price {
        font-size: 14px;
        font-weight: 700;
        color: #0a4d68;
        margin-top: 8px;
    }
    
    .notes-box {
        background: #fffbeb;
        border-left: 3px solid #f59e0b;
        padding: 12px 15px;
        margin-top: 15px;
        font-size: 11px;
        color: #78350f;
    }
    
    /* Totales */
    .totals-section {
        padding: 12px 25px;
        page-break-inside: avoid;
    }
    
    .totals-box {
        max-width: 350px;
        margin-left: auto;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 12px;
    }
    
    .total-row.subtotal {
        color: #718096;
    }
    
    .total-row.final {
        font-size: 16px;
        font-weight: 700;
        color: #0a4d68;
        border-top: 2px solid #0a4d68;
        padding-top: 10px;
        margin-top: 6px;
    }
    
    /* Footer */
    .invoice-footer {
        background: #0a4d68;
        padding: 15px 25px;
        position: relative;
        overflow: hidden;
        color: white;
        margin-top: 15px;
        page-break-inside: avoid;
    }
    
    .invoice-footer::before {
        content: '';
        position: absolute;
        top: -50px;
        left: -50px;
        width: 300px;
        height: 150px;
        background: #7dd3fc;
        opacity: 0.3;
        border-radius: 50%;
    }
    
    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    
    .signature {
        text-align: left;
    }
    
    .signature-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .signature-title {
        font-size: 11px;
        color: #7dd3fc;
    }
    
    .contact-info {
        text-align: right;
    }
    
    .contact-info p {
        font-size: 11px;
        margin: 3px 0;
        color: #bae6fd;
    }
</style>
@endif

<div class="{{ $isPdf ? 'invoice-container' : '' }}"

<div class="{{ $isPdf ? 'invoice-container' : '' }}">

<!-- Header -->
@if(!$isPdf)
<!-- Header de la Proforma (Vista en pantalla) -->
<div class="mb-6 p-6 bg-white rounded-xl border border-slate-200 shadow-sm">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Proforma de Producto{{ $isMultiItem && count($items) > 1 ? 's' : '' }}</h2>
        <p class="text-lg font-bold text-slate-700 mb-1">{{ $displayNumber }}</p>
        <p class="text-sm text-slate-600">Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    @if(isset($user) && $user)
        <div class="mt-4 bg-slate-50 rounded-lg p-4 shadow-sm max-w-md mx-auto border border-slate-200">
            <h3 class="font-semibold text-slate-900 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Información del Cliente
            </h3>
            <div class="text-sm space-y-1 text-slate-700">
                <p><strong class="text-slate-900">Cliente:</strong> {{ $user->name ?? $user->email ?? 'Usuario' }}</p>
                @if(!empty($user->email))<p><strong class="text-slate-900">Email:</strong> {{ $user->email }}</p>@endif
                @if(!empty($user->phone))<p><strong class="text-slate-900">Teléfono:</strong> {{ $user->phone }}</p>@endif
                @if(!empty($user->address))<p><strong class="text-slate-900">Dirección:</strong> {{ $user->address }}</p>@endif
                @if(!empty($user->province))<p><strong class="text-slate-900">Provincia:</strong> {{ $user->province }}</p>@endif
                @if(!empty($user->city))<p><strong class="text-slate-900">Ciudad:</strong> {{ $user->city }}</p>@endif
            </div>
        </div>
    @endif
</div>
@else
<!-- PDF Header -->
<div class="invoice-header">
    <div class="header-content">
        <div class="company-logo">
            <img src="{{ public_path('images/logo.png') }}" alt="Quality Logo">
        </div>
        <div class="invoice-title">QUALITY</div>
    </div>
</div>

<!-- Invoice Info -->
<div style="padding: 5px 25px 3px 25px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                <h4 style="font-size: 11px; color: #718096; margin: 0 0 3px 0; text-transform: uppercase; letter-spacing: 1px;">Proforma Para:</h4>
                @if(isset($user) && $user)
                    <p style="font-weight: 700; font-size: 13px; color: #1a202c; margin: 2px 0; line-height: 1.4;">{{ $user->name ?? $user->email ?? 'Cliente' }}</p>
                    @if(!empty($user->email))<p style="font-size: 12px; color: #2d3748; margin: 2px 0; line-height: 1.4;"><b>Correo:</b> {{ $user->email }}</p>@endif
                    @if(!empty($user->phone))<p style="font-size: 12px; color: #2d3748; margin: 2px 0; line-height: 1.4;"><b>Teléfono:</b> {{ $user->phone }}</p>@endif
                    @if(!empty($user->city))<p style="font-size: 12px; color: #2d3748; margin: 2px 0; line-height: 1.4;"><b>Ubicación:</b> {{ $user->city }}, {{ $user->province }}</p>@endif
                @endif
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right; padding-top: 18px;">
                <div style="display: inline-block; background: #7dd3fc; color: #0a4d68; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-bottom: 2px;">Proforma: {{ $displayNumber }}</div>
                <p style="font-size: 11px; color: #718096; margin: 1px 0;">Fecha: {{ now()->format('d/m/Y') }}</p>
                @if(isset($expiration_date))
                    <p style="font-size: 11px; color: #718096; margin: 1px 0;">Válida hasta: {{ \Carbon\Carbon::parse($expiration_date)->format('d/m/Y') }}</p>
                @endif
                @if(isset($is_expired))
                    <div style="display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 10px; font-weight: 600; margin-top: 3px; background: {{ $is_expired ? '#fee2e2' : '#d1fae5' }}; color: {{ $is_expired ? '#991b1b' : '#065f46' }};">
                        {{ $is_expired ? 'X Expirada' : 'Vigente' }}
                    </div>
                @endif
            </td>
        </tr>
    </table>
</div>
@endif

@if($isMultiItem)
    <!-- Vista Multi-Item -->
    @if(!$isPdf)
    <!-- Vista en pantalla -->
    <div class="mb-6">
        <h4 class="text-lg font-semibold text-slate-900 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
            </svg>
            Información General de la Proforma
        </h4>
        <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-600 mb-1">Número de Proforma</p>
                    <p class="font-semibold text-slate-900">{{ $displayNumber }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 mb-1">Configuraciones</p>
                    <p class="font-semibold text-slate-900">{{ count($items) }} {{ count($items) == 1 ? 'configuración' : 'configuraciones' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 mb-1">Cantidad Total de Productos</p>
                    <p class="font-semibold text-slate-900">{{ $totalQuantity }} {{ $totalQuantity == 1 ? 'producto' : 'productos' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-600 mb-1">Estado de la Proforma</p>
                    @if(isset($is_expired))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $is_expired ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                            @if($is_expired)
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Expirada
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Vigente
                            @endif
                        </span>
                    @else
                        <span class="text-slate-600">-</span>
                    @endif
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-200">
                <p class="text-xs text-slate-600 mb-1">Fecha de Expiración</p>
                <p class="font-semibold text-slate-900">{{ isset($expiration_date) ? \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') : '-' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Ítems de la Proforma -->
    @if(!$isPdf)
    <!-- Vista pantalla: Cards detallados -->
    @foreach($items as $index => $item)
    <div class="bg-white rounded-xl p-6 mb-6 border-l-4 border-slate-300 shadow-sm">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center shadow-sm mr-3 border border-slate-300">
                <span class="text-white font-bold text-lg">{{ $index + 1 }}</span>
            </div>
            <h4 class="text-xl font-bold text-slate-900">{{ $item['product_name'] }}</h4>
        </div>
        
        <!-- Datos del Producto -->
        <div class="mb-6">
            <h5 class="text-md font-semibold text-slate-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                Datos del Producto
            </h5>
            <div class="bg-slate-50 rounded-lg p-4 space-y-3 border border-slate-200">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-600 mb-1">Nombre del Producto</p>
                        <p class="font-medium text-slate-900">{{ $item['product_name'] }}</p>
                    </div>
                    @if(isset($item['parameters']['color']))
                    <div>
                        <p class="text-xs text-slate-600 mb-1">Color Aluminio</p>
                        <p class="font-medium text-slate-900">{{ $item['parameters']['color'] }}</p>
                    </div>
                    @endif
                    @if(isset($item['parameters']['glassColor']))
                    <div>
                        <p class="text-xs text-slate-600 mb-1">Color Vidrio</p>
                        <p class="font-medium text-slate-900">{{ $item['parameters']['glassColor'] }}</p>
                    </div>
                    @endif
                    @if(isset($item['parameters']['width']) && isset($item['parameters']['height']))
                    <div class="col-span-2">
                        <p class="text-xs text-slate-600 mb-1">Dimensiones</p>
                        <p class="font-medium text-slate-900">
                            Ancho: {{ number_format($item['parameters']['width'], 3) }} m | Alto: {{ number_format($item['parameters']['height'], 3) }} m
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-600 mb-1">Cantidad</p>
                        <p class="font-medium text-slate-900">{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-600 mb-1">Precio Unitario (sin IVA)</p>
                        <p class="font-medium text-slate-900">${{ number_format((($item['price'] ?? 0) / 1.15) / max(1, $item['quantity']), 2) }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-200">
                    <div class="flex justify-between items-center bg-slate-50 rounded-lg p-3 border border-slate-200">
                        <span class="font-semibold text-slate-900">Subtotal del ítem (sin IVA)</span>
                        <span class="text-lg font-bold text-slate-900">${{ number_format(($item['price'] ?? 0) / 1.15, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materiales -->
        @if(!empty($item['materialCosts']))
        @php
            $totalMateriales = collect($item['materialCosts'])->sum('total_cost');
        @endphp
        <div class="mb-6">
            <h5 class="text-md font-semibold text-slate-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Desglose de Materiales
            </h5>
            <div class="bg-white rounded-lg overflow-hidden border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Unidad</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">P. Unit.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Inc. Color</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($item['materialCosts'] as $mat)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-900">{{ $mat['name'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $formatMaterialQuantity($mat['quantity'] ?? 0, $mat['unit'] ?? '') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-700">{{ $formatMaterialUnit($mat['unit'] ?? '-') }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-orange-600">${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-900">${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-slate-50 font-bold border-t-2 border-slate-300">
                            <td colspan="5" class="px-4 py-3 text-right text-sm text-slate-900">Subtotal Materiales:</td>
                            <td class="px-4 py-3 text-sm font-bold text-slate-900">${{ number_format($totalMateriales, 2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
        
        <div class="mb-6">
            <h5 class="text-md font-semibold text-slate-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                </svg>
                Análisis de Costos
            </h5>
            <div class="bg-slate-50 rounded-lg p-5 border border-slate-200">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-700">Costo de Materiales</span>
                        <span class="text-sm font-semibold text-slate-900">${{ number_format($totalMateriales, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-700">Costo de Mano de Obra ({{ $directCost }}%)</span>
                        <span class="text-sm font-semibold text-slate-900">${{ number_format($directAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-700">Costos Indirectos ({{ $indirectCost }}%)</span>
                        <span class="text-sm font-semibold text-slate-900">${{ number_format($indirectAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-200">
                        <span class="text-sm text-slate-700">Factor de merma ({{ $wastePercentage }}%)</span>
                        <span class="text-sm font-semibold text-slate-900">${{ number_format($wasteAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-100 rounded-lg px-3 mt-2 border border-slate-200">
                        <span class="text-sm font-semibold text-slate-900">Costo Total de Producción</span>
                        <span class="text-sm font-bold text-slate-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t border-slate-200 pt-3 mt-2">
                        <span class="text-sm text-slate-700">Margen de Utilidad ({{ $profitMargin }}%)</span>
                        <span class="text-sm font-semibold text-green-600">${{ number_format($profitAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-100 rounded-lg px-3 mt-2 border border-slate-200">
                        <span class="text-base font-bold text-slate-900">Subtotal del análisis (sin IVA)</span>
                        <span class="text-lg font-bold text-green-600">${{ number_format($subtotal + $profitAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notas del Ítem -->
        @if(!empty($item['notes']))
        <div>
            <h5 class="text-md font-semibold text-slate-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
                Notas
            </h5>
            <div class="bg-slate-50 border-l-4 border-slate-300 rounded-lg p-4">
                <p class="text-sm text-slate-700">{{ $item['notes'] }}</p>
            </div>
        </div>
        @endif
    </div>
    @endforeach
    @else
    <!-- PDF: Vista detallada completa -->
    @foreach($items as $index => $item)
    <div style="margin-bottom: 15px; {{ $loop->first ? 'margin-top: 2px;' : '' }}">
        <!-- Encabezado del ítem -->
        <div style="background: linear-gradient(135deg, #0a4d68 0%, #088395 100%); color: white; padding: 8px 12px; border-radius: 6px 6px 0 0; margin-bottom: 0; page-break-inside: avoid;">
            <h3 style="margin: 0; font-size: 14px; font-weight: bold;">
                <span style="display: inline-block; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 3px; margin-right: 8px; font-size: 12px;">{{ $index + 1 }}</span>
                {{ $item['product_name'] }}
            </h3>
        </div>
        
        <!-- Datos del Producto -->
        <div style="border: 1px solid #cbd5e0; border-top: none; padding: 10px; background: #f8fafc; border-radius: 0 0 6px 6px; margin-bottom: 8px; page-break-inside: avoid;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Producto</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">{{ $item['product_name'] }}</span>
                    </td>
                    @if(isset($item['parameters']['color']))
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Color Aluminio</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">{{ $item['parameters']['color'] }}</span>
                    </td>
                    @else
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;"></td>
                    @endif
                </tr>
                <tr>
                    @if(isset($item['parameters']['glassColor']))
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Color Vidrio</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">{{ $item['parameters']['glassColor'] }}</span>
                    </td>
                    @else
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;"></td>
                    @endif
                    @if(isset($item['parameters']['width']) && isset($item['parameters']['height']))
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Dimensiones</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">{{ $item['parameters']['width'] }} x {{ $item['parameters']['height'] }} m</span>
                    </td>
                    @else
                    <td style="padding: 5px; width: 50%; border-bottom: 1px solid #e2e8f0;"></td>
                    @endif
                </tr>
                <tr>
                    <td style="padding: 5px; width: 50%;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Cantidad</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</span>
                    </td>
                    <td style="padding: 5px; width: 50%;">
                        <strong style="color: #0a4d68; font-size: 10px; text-transform: uppercase;">Precio Unitario (sin IVA)</strong><br>
                        <span style="font-size: 12px; color: #1a202c;">${{ number_format((($item['price'] ?? 0) / 1.15) / max(1, $item['quantity']), 2) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 8px; background: #e6f7ff; border-top: 2px solid #0a4d68;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="color: #0a4d68; font-size: 12px;">Subtotal del ítem (sin IVA)</strong>
                            <strong style="color: #0a4d68; font-size: 15px;">${{ number_format(($item['price'] ?? 0) / 1.15, 2) }}</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Materiales -->
        @if(!empty($item['materialCosts']))
        @php
            $totalMateriales = collect($item['materialCosts'])->sum('total_cost');
        @endphp
        <div style="margin-bottom: 8px;">
            <h4 style="color: #0a4d68; font-size: 12px; font-weight: bold; margin: 0 0 5px 0; padding: 5px 8px; background: #e6f7ff; border-left: 3px solid #0a4d68; page-break-after: avoid;">
                DESGLOSE DE MATERIALES
            </h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 6px;">
                <thead>
                    <tr style="background: #0a4d68; color: white;">
                        <th style="padding: 5px 6px; text-align: left; border: 1px solid #0a4d68;">Material</th>
                        <th style="padding: 5px 6px; text-align: center; border: 1px solid #0a4d68; width: 60px;">Cant.</th>
                        <th style="padding: 5px 6px; text-align: center; border: 1px solid #0a4d68; width: 45px;">Unid.</th>
                        <th style="padding: 5px 6px; text-align: right; border: 1px solid #0a4d68; width: 60px;">P. Unit.</th>
                        <th style="padding: 5px 6px; text-align: right; border: 1px solid #0a4d68; width: 60px;">Inc. Color</th>
                        <th style="padding: 5px 6px; text-align: right; border: 1px solid #0a4d68; width: 70px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($item['materialCosts'] as $mat)
                    <tr style="background: {{ $loop->iteration % 2 == 0 ? '#f8fafc' : 'white' }};">
                        <td style="padding: 4px 6px; border: 1px solid #e2e8f0;">{{ $mat['name'] ?? '-' }}</td>
                        <td style="padding: 4px 6px; text-align: center; border: 1px solid #e2e8f0;">{{ $formatMaterialQuantity($mat['quantity'] ?? 0, $mat['unit'] ?? '') }}</td>
                        <td style="padding: 4px 6px; text-align: center; border: 1px solid #e2e8f0;">{{ $formatMaterialUnit($mat['unit'] ?? '-') }}</td>
                        <td style="padding: 4px 6px; text-align: right; border: 1px solid #e2e8f0; font-weight: 600;">${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                        <td style="padding: 4px 6px; text-align: right; border: 1px solid #e2e8f0; color: #f59e0b; font-weight: 600;">${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                        <td style="padding: 4px 6px; text-align: right; border: 1px solid #e2e8f0; font-weight: bold;">${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                    <tr style="background: #e6f7ff; font-weight: bold;">
                        <td colspan="5" style="padding: 5px 6px; text-align: right; border: 2px solid #0a4d68; color: #0a4d68; font-size: 10px;">Subtotal Materiales:</td>
                        <td style="padding: 5px 6px; text-align: right; border: 2px solid #0a4d68; color: #0a4d68; font-size: 10px;">${{ number_format($totalMateriales, 2) }}</td>
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
        
        <div style="margin-bottom: 8px;">
            <h4 style="color: #0a4d68; font-size: 12px; font-weight: bold; margin: 0 0 5px 0; padding: 5px 8px; background: #e6f7ff; border-left: 3px solid #0a4d68; page-break-after: avoid;">
                ANALISIS DE COSTOS
            </h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 4px 8px; color: #4a5568;">Costo de Materiales</td>
                    <td style="padding: 4px 8px; text-align: right; font-weight: 600; color: #1a202c;">${{ number_format($totalMateriales, 2) }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 4px 8px; color: #4a5568;">Mano de Obra ({{ $directCost }}%)</td>
                    <td style="padding: 4px 8px; text-align: right; font-weight: 600; color: #1a202c;">${{ number_format($directAmount, 2) }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 4px 8px; color: #4a5568;">Costos Indirectos ({{ $indirectCost }}%)</td>
                    <td style="padding: 4px 8px; text-align: right; font-weight: 600; color: #1a202c;">${{ number_format($indirectAmount, 2) }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 4px 8px; color: #4a5568;">Factor de merma ({{ $wastePercentage }}%)</td>
                    <td style="padding: 4px 8px; text-align: right; font-weight: 600; color: #1a202c;">${{ number_format($wasteAmount, 2) }}</td>
                </tr>
                <tr style="background: #f8fafc; border-top: 2px solid #cbd5e0; border-bottom: 2px solid #cbd5e0;">
                    <td style="padding: 6px 8px; font-weight: bold; color: #0a4d68;">Costo Total Producción</td>
                    <td style="padding: 6px 8px; text-align: right; font-weight: bold; color: #0a4d68; font-size: 11px;">${{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 4px 8px; color: #4a5568;">Margen de Utilidad ({{ $profitMargin }}%)</td>
                    <td style="padding: 4px 8px; text-align: right; font-weight: 600; color: #10b981;">${{ number_format($profitAmount, 2) }}</td>
                </tr>
                <tr style="background: #e6f7ff; border-top: 2px solid #0a4d68;">
                    <td style="padding: 8px; font-weight: bold; color: #0a4d68; font-size: 11px;">Precio Final Ítem</td>
                    <td style="padding: 8px; text-align: right; font-weight: bold; color: #0a4d68; font-size: 13px;">${{ number_format($subtotal + $profitAmount, 2) }}</td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Notas del Ítem -->
        @if(!empty($item['notes']))
        <div style="margin-bottom: 8px; padding: 8px; background: #fffbeb; border-left: 3px solid #f59e0b; border-radius: 3px; page-break-inside: avoid;">
            <h4 style="color: #92400e; font-size: 11px; font-weight: bold; margin: 0 0 4px 0;">NOTAS:</h4>
            <p style="margin: 0; color: #78350f; font-size: 10px; line-height: 1.4;">{{ $item['notes'] }}</p>
        </div>
        @endif
        
        @if(!$loop->last)
        <div style="border-bottom: 1px dashed #cbd5e0; margin: 8px 0;"></div>
        @endif
    </div>
    @endforeach
    @endif
    
    <!-- Total de la Proforma -->
    @if(!$isPdf)
    <!-- Vista pantalla -->
    <div class="bg-indigo-50 rounded-xl p-6 shadow-sm border border-indigo-200">
        <p class="text-lg font-semibold mb-4 text-slate-900">Total de la Proforma</p>
        <div class="space-y-2 text-left max-w-md mx-auto">
            <div class="mb-3 pb-3 border-b border-indigo-200">
                <p class="text-xs uppercase tracking-wide text-indigo-700/80 mb-2">Suma de ítems (sin IVA)</p>
                @foreach($items as $index => $item)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-600">{{ $index + 1 }}. {{ $item['product_name'] ?? 'Ítem' }}</span>
                        <span class="text-slate-800">${{ number_format((($item['price'] ?? 0) / 1.15), 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-600">Suma de subtotales (sin IVA):</span>
                <span class="text-slate-800">${{ number_format($displaySubtotalSinIva, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-600">IVA (15%):</span>
                <span class="text-slate-800">${{ number_format($displayIva, 2) }}</span>
            </div>
            <div class="border-t-2 border-indigo-300 pt-2 mt-2">
                <div class="flex justify-between">
                    <span class="text-xl font-bold text-slate-900">Total:</span>
                    <span class="text-3xl font-bold text-indigo-800">${{ number_format($displayTotalConIva, 2) }}</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-slate-600 mt-4 text-center">{{ count($items) }} {{ count($items) == 1 ? 'configuración' : 'configuraciones' }} • {{ $totalQuantity }} {{ $totalQuantity == 1 ? 'producto' : 'productos' }}</p>
    </div>
    @else
    <!-- PDF: Sección de totales -->
    <div class="totals-section">
        <div class="totals-box">
            <div style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0;">
                <div style="font-size: 10px; color: #718096; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Suma de ítems (sin IVA)</div>
                @foreach($items as $index => $item)
                    <div style="display: flex; justify-content: space-between; padding: 2px 0; font-size: 11px; color: #4a5568;">
                        <span>{{ $index + 1 }}. {{ $item['product_name'] ?? 'Ítem' }}</span>
                        <span>${{ number_format((($item['price'] ?? 0) / 1.15), 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="total-row subtotal">
                <span>Suma de subtotales:</span>
                <span>${{ number_format($displaySubtotalSinIva, 2) }}</span>
            </div>
            <div class="total-row subtotal">
                <span>IVA (15%):</span>
                <span>${{ number_format($displayIva, 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total:</span>
                <span>${{ number_format($displayTotalConIva, 2) }}</span>
            </div>
        </div>
        <p style="text-align: center; font-size: 12px; color: #718096; margin-top: 15px;">
            {{ count($items) }} {{ count($items) == 1 ? 'configuración' : 'configuraciones' }} • {{ $totalQuantity }} {{ $totalQuantity == 1 ? 'producto' : 'productos' }}
        </p>
    </div>
    
    <!-- PDF Footer -->
    <div class="invoice-footer">
        <div class="footer-content">
            <div class="signature">
                <div class="signature-name">Autorizado por</div>
                <div class="signature-title">Gerente General</div>
            </div>
            <div class="contact-info">
                <p><strong style="color: white;">Quality</strong></p>
                <p>+593 123 456 7890</p>
                <p>info@quality.com.ec</p>
                <p>www.quality.com.ec</p>
            </div>
        </div>
    </div>
    @endif

@else
<!-- Vista Single-Item -->
<div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 mb-6 border-l-4 border-slate-500 shadow-lg">
    <h4 class="text-xl font-bold text-gray-100 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
        </svg>
        Datos Generales del Producto
    </h4>
    <div class="bg-slate-700/50 rounded-lg p-5 space-y-4 border border-slate-600">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-400 mb-1">Nombre del Producto</p>
                <p class="font-medium text-gray-200">{{ $product->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Color</p>
                <p class="font-medium text-gray-200">{{ $parameters['color'] ?? '-' }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-slate-400 mb-1">Dimensiones</p>
                <p class="font-medium text-gray-200">
                    Ancho: {{ isset($parameters['width']) ? number_format($parameters['width'], 3) : '-' }} m | Alto: {{ isset($parameters['height']) ? number_format($parameters['height'], 3) : '-' }} m
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Cantidad</p>
                <p class="font-medium text-gray-200">{{ $quantity ?? 1 }} {{ ($quantity ?? 1) == 1 ? 'unidad' : 'unidades' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Precio Unitario</p>
                <p class="font-medium text-gray-200">${{ number_format($calculatedPrice / max(1, $quantity ?? 1), 2) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Fecha de Expiración</p>
                <p class="font-medium text-gray-200">{{ isset($expiration_date) ? \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Estado</p>
                @if(isset($is_expired))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $is_expired ? 'bg-red-900/50 text-red-300 border border-red-700' : 'bg-emerald-900/50 text-emerald-300 border border-emerald-700' }}">
                        {{ $is_expired ? '❌ Expirada' : '✓ Vigente' }}
                    </span>
                @else
                    <span class="text-slate-400">-</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bg-slate-800/50 rounded-lg p-6 mb-6 border border-slate-600">
    <h4 class="font-semibold text-gray-200 mb-3">Parámetros Detallados</h4>
    <table class="min-w-full divide-y divide-slate-600">
        <tbody class="divide-y divide-slate-600">
        @php
            $paramLabels = [
                'width' => 'Ancho (m)',
                'height' => 'Alto (m)',
                'frameWidth' => 'Ancho del Marco',
                'color' => 'Color del Marco',
                'glassColor' => 'Color del Vidrio',
                'notes' => 'Notas',
            ];
        @endphp
        @forelse($parameters as $key => $value)
            @if($key !== 'notes')
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">{{ $paramLabels[$key] ?? Str::headline($key) }}</th>
                    <td class="px-4 py-3 text-sm text-gray-200">{{ is_array($value) ? json_encode($value) : $value }}</td>
                </tr>
            @endif
        @empty
            <tr><td colspan="2" class="text-center text-slate-400 px-4 py-3">Sin parámetros</td></tr>
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

<div class="bg-slate-800/50 rounded-lg p-6 mb-6 border border-slate-600">
    <h4 class="font-semibold text-gray-200 mb-3">Desglose Detallado de Materiales</h4>
    <table class="min-w-full divide-y divide-slate-600">
        <thead class="bg-slate-700/70">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Material</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Cantidad</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Unidad</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Precio Unit.</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Inc. Color</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase">Costo Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-600">
        @forelse($materialCosts as $mat)
            <tr class="hover:bg-slate-700/50 transition-colors">
                <td class="px-4 py-3 text-sm text-gray-200">{{ $mat['name'] ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-300">{{ $formatMaterialQuantity($mat['quantity'] ?? 0, $mat['unit'] ?? '') }}</td>
                <td class="px-4 py-3 text-sm text-gray-300">{{ $formatMaterialUnit($mat['unit'] ?? '-') }}</td>
                <td class="px-4 py-3 text-sm text-gray-200">${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                <td class="px-4 py-3 text-sm text-orange-400">${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                <td class="px-4 py-3 text-sm font-semibold text-gray-100">${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-slate-400 px-4 py-3">Sin materiales</td></tr>
        @endforelse
        @if(!empty($materialCosts))
            <tr class="bg-slate-700/70 font-bold border-t-2 border-slate-500">
                <td colspan="5" class="px-4 py-3 text-right text-sm text-gray-200">Subtotal Materiales:</td>
                <td class="px-4 py-3 text-sm font-bold text-slate-300">${{ number_format($totalMateriales, 2) }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="bg-slate-800/50 rounded-lg p-6 mb-6 border border-slate-600">
    <h4 class="font-semibold text-gray-200 mb-3">Análisis de Costos</h4>
    <div class="cost-breakdown">
        <table class="min-w-full divide-y divide-slate-600">
            <tbody class="divide-y divide-slate-600">
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th width="60%" class="px-4 py-3 text-left text-sm text-gray-300">Costo de Materiales</th>
                    <td class="px-4 py-3 text-right text-sm text-gray-200">${{ number_format($totalMateriales, 2) }}</td>
                </tr>
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm text-gray-300">Costos Directos / Mano de Obra ({{ $directCost }}%)</th>
                    <td class="px-4 py-3 text-right text-sm text-gray-200">${{ number_format($directAmount, 2) }}</td>
                </tr>
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm text-gray-300">Costos Indirectos ({{ $indirectCost }}%)</th>
                    <td class="px-4 py-3 text-right text-sm text-gray-200">${{ number_format($indirectAmount, 2) }}</td>
                </tr>
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm text-gray-300">Material de Desperdicio ({{ $wastePercentage }}%)</th>
                    <td class="px-4 py-3 text-right text-sm text-gray-200">${{ number_format($wasteAmount, 2) }}</td>
                </tr>
                <tr class="bg-slate-700/70 font-bold border-t-2 border-slate-500">
                    <th class="px-4 py-3 text-left text-sm text-gray-200">Costo Total de Producción</th>
                    <td class="px-4 py-3 text-right text-sm text-slate-300">${{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm text-gray-300">Margen de Ganancia ({{ $profitMargin }}%)</th>
                    <td class="px-4 py-3 text-right text-sm text-emerald-400">${{ number_format($profitAmount, 2) }}</td>
                </tr>
                <tr class="bg-slate-700/70 font-bold border-t-2 border-slate-500">
                    <th class="px-4 py-3 text-left text-sm text-gray-200">Subtotal (sin IVA)</th>
                    <td class="px-4 py-3 text-right text-sm text-gray-200">${{ number_format($subtotal + $profitAmount, 2) }}</td>
                </tr>
                <tr class="hover:bg-slate-700/50 transition-colors">
                    <th class="px-4 py-3 text-left text-sm text-gray-300">IVA (15%)</th>
                    <td class="px-4 py-3 text-right text-sm text-emerald-400">${{ number_format(($subtotal + $profitAmount) * 0.15, 2) }}</td>
                </tr>
                <tr class="bg-gradient-to-r from-emerald-900/50 to-green-900/50 font-bold border-t-2 border-emerald-600">
                    <th class="px-4 py-3 text-left text-base text-emerald-300">Precio Final al Cliente (con IVA)</th>
                    <td class="px-4 py-3 text-right text-lg text-emerald-400">${{ number_format(($subtotal + $profitAmount) * 1.15, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="bg-gradient-to-r from-slate-700 to-slate-800 text-white rounded-xl p-6 text-center shadow-lg border border-slate-600 mb-6">
    <span class="text-lg text-slate-300">Precio Final al Cliente:</span>
    <span class="text-3xl font-bold text-gray-100 block mt-2">${{ number_format($calculatedPrice, 2) }}</span>
</div>

@if(!empty($notes))
<div class="bg-slate-800/50 rounded-lg p-4 mb-6 border-l-4 border-slate-500">
    <h4 class="font-semibold text-gray-200 mb-2">Notas del Cliente</h4>
    <div class="bg-slate-700/50 p-3 rounded border border-slate-600">
        <p class="text-sm text-gray-300">{{ $notes }}</p>
    </div>
</div>
@endif
@endif

</div>

@if(isset($showDownloadButton) && $showDownloadButton)
<div class="flex justify-end mt-4">
    <button type="button" wire:click="downloadProformaPdf" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-lg transition-colors border border-emerald-500">Descargar PDF</button>
</div>
@endif