@php
    $isPdf = $isPdf ?? false; // Por defecto es vista web
    $isMultiItem = isset($items) && is_array($items) && count($items) > 0;
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
        padding: 20px 30px;
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
        height: 48px;
        max-width: 70px;
        object-fit: contain;
        display: block;
    }
    
    .invoice-title {
        display: table-cell;
        vertical-align: middle;
        font-size: 36px;
        font-weight: 700;
        letter-spacing: 2px;
        color: white;
        padding-left: 10px;
    }
    
    /* Info section */
    .invoice-info {
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
    }
    
    .invoice-to {
        flex: 1;
    }
    
    .invoice-to h4 {
        font-size: 11px;
        color: #718096;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .invoice-to p {
        font-size: 13px;
        color: #2d3748;
        margin: 4px 0;
        line-height: 1.5;
    }
    
    .invoice-to .client-name {
        font-weight: 700;
        font-size: 14px;
        color: #1a202c;
    }
    
    .invoice-meta {
        text-align: right;
    }
    
    .invoice-badge {
        display: inline-block;
        background: #7dd3fc;
        color: #0a4d68;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .invoice-date {
        font-size: 12px;
        color: #718096;
    }
    
    /* Tabla de productos */
    .products-table {
        width: calc(100% - 60px);
        margin: 10px 30px;
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
    
    /* Totales */
    .totals-section {
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        page-break-inside: avoid;
    }
    
    .terms-conditions {
        flex: 1;
        padding-right: 40px;
    }
    
    .terms-conditions h4 {
        font-size: 12px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }
    
    .terms-conditions p {
        font-size: 11px;
        color: #718096;
        line-height: 1.6;
    }
    
    .totals-box {
        width: 250px;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 13px;
    }
    
    .total-row.subtotal {
        color: #718096;
    }
    
    .total-row.final {
        font-size: 18px;
        font-weight: 700;
        color: #0a4d68;
        border-top: 2px solid #0a4d68;
        padding-top: 12px;
        margin-top: 8px;
    }
    
    /* Footer con onda */
    .invoice-footer {
        background: #0a4d68;
        padding: 20px 30px;
        position: relative;
        overflow: hidden;
        color: white;
        margin-top: 20px;
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
    
    /* Item cards para multi-item */
    .item-card { 
        background: #f7fafc;
        padding: 15px 30px; 
        margin: 10px 0;
        border-left: 4px solid #7dd3fc;
        page-break-inside: avoid;
    }
    
    .item-card h4 {
        color: #0a4d68;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .notes-box {
        background: #fffbeb;
        border-left: 3px solid #f59e0b;
        padding: 12px 15px;
        margin-top: 15px;
        font-size: 11px;
        color: #78350f;
    }
</style>
@endif

<div class="{{ $isPdf ? 'invoice-container' : 'space-y-4' }}">
    <!-- Header -->
    @if(!$isPdf)
    <div class="flex items-center space-x-3 mb-3">
        <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Proforma de Producto{{ $isMultiItem ? 's' : '' }}</h2>
            <p class="text-sm text-white/80">
                @if($isMultiItem)
                    {{ $number ?? '' }} • {{ now()->format('d/m/Y H:i') }}
                @else
                    {{ isset($product) && $product ? $product->name : 'Producto' }} • {{ now()->format('d/m/Y H:i') }}
                @endif
            </p>
        </div>
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
    <table style="width: 100%; border-collapse: collapse; padding: 15px 30px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <h4 style="font-size: 11px; color: #718096; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Proforma Para:</h4>
                @if(isset($user) && $user)
                    <p style="font-weight: 700; font-size: 14px; color: #1a202c; margin: 4px 0; line-height: 1.5;">{{ $user->name ?? $user->email ?? 'Cliente' }}</p>
                    @if(!empty($user->email))<p style="font-size: 13px; color: #2d3748; margin: 4px 0; line-height: 1.5;"><b>Correo:</b> {{ $user->email }}</p>@endif
                    @if(!empty($user->phone))<p style="font-size: 13px; color: #2d3748; margin: 4px 0; line-height: 1.5;"><b>Teléfono:</b> {{ $user->phone }}</p>@endif
                    @if(!empty($user->city))<p style="font-size: 13px; color: #2d3748; margin: 4px 0; line-height: 1.5;"><b>Ubicación:</b> {{ $user->city }}, {{ $user->province }}</p>@endif
                @endif
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right; padding-top: 18px;">
                <div style="display: inline-block; background: #7dd3fc; color: #0a4d68; padding: 6px 16px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-bottom: 8px;">
                    @if($isMultiItem)
                        Proforma: {{ $number ?? '' }}
                    @else
                        Proforma
                    @endif
                </div>
                @if(isset($created_at))
                    <p style="font-size: 12px; color: #718096; margin: 2px 0;">Fecha: {{ \Carbon\Carbon::parse($created_at)->format('d/m/Y') }}</p>
                @endif
                @if(isset($expiration_date))
                    <p style="font-size: 12px; color: #718096; margin: 2px 0;">Válida hasta: {{ \Carbon\Carbon::parse($expiration_date)->format('d/m/Y') }}</p>
                @endif
            </td>
        </tr>
    </table>
    
    <div style="padding: 0 10px;">
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
        <span class="triangle-decoration"></span>
    </div>
    @endif
    
    <!-- Información del Cliente -->
    @if(isset($user) && $user && !$isPdf)
        <div class="p-3 bg-black/30 border border-white/20 rounded-xl">
            <h4 class="text-sm font-semibold text-white mb-2">Información del Cliente</h4>
            <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-white/80">
                <p><strong class="text-white">Cliente:</strong> {{ $user->name ?? $user->email ?? 'Usuario' }}</p>
                @if(!empty($user->email))<p><strong class="text-white">Email:</strong> {{ $user->email }}</p>@endif
                @if(!empty($user->phone))<p><strong class="text-white">Teléfono:</strong> {{ $user->phone }}</p>@endif
                @if(!empty($user->city))<p><strong class="text-white">Ciudad:</strong> {{ $user->city }}, {{ $user->province }}</p>@endif
            </div>
        </div>
    @endif
</div>

@if($isMultiItem)
    <!-- Vista Multi-Item -->
    @if(!$isPdf)
    <div class="proforma-section text-gray-800">
        <h4 class="font-semibold mb-2">Información General de la Proforma</h4>
        <table class="proforma-table text-gray-800">
            <tbody>
                <tr>
                    <th width="40%">Número de Proforma</th>
                    <td>{{ $number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Total de Productos</th>
                    <td>{{ collect($items)->sum('quantity') }} {{ collect($items)->sum('quantity') == 1 ? 'unidad' : 'unidades' }}</td>
                </tr>
                @if(isset($expiration_date))
                <tr>
                    <th>Fecha de Expiración</th>
                    <td>{{ \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @else
    <!-- PDF: Tabla de productos -->
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 80px;">Cant.</th>
                <th>Descripción del Producto</th>
                <th style="width: 120px; text-align: right;">Precio Unit.</th>
                <th style="width: 120px;">Total</th>
            </tr>
        </thead>
        <tbody>

    <!-- Configuraciones -->
    @foreach($items as $index => $item)
    @if(!$isPdf)
    <div class="item-card">
        <h4 class="font-bold text-lg mb-3 text-gray-800">Configuración #{{ $index + 1 }}: {{ $item['product_name'] }}</h4>
        
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
                    <th>Precio Final</th>
                    <td>${{ number_format($item['price'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        @if(!empty($item['notes']))
        <div class="notes-box">
            <strong>📝 Notas del Cliente:</strong>
            <p>{{ $item['notes'] }}</p>
        </div>
        @endif
    </div>
    @else
    <!-- PDF: Fila de producto en tabla -->
    <tr>
        <td class="qty-col">{{ $item['quantity'] }}</td>
        <td>
            <strong>{{ $item['product_name'] }}</strong><br>
            @if(isset($item['parameters']['color']))
                <span style="font-size: 11px; color: #718096;">Color: {{ $item['parameters']['color'] }}</span>
            @endif
            @if(isset($item['parameters']['width']) && isset($item['parameters']['height']))
                <br><span style="font-size: 11px; color: #718096;">Dimensiones: {{ $item['parameters']['width'] }} x {{ $item['parameters']['height'] }} m</span>
            @endif
            @if(!empty($item['notes']))
                <br><span style="font-size: 10px; color: #f59e0b;">📝 {{ $item['notes'] }}</span>
            @endif
        </td>
        <td style="text-align: right;">${{ number_format($item['price'] / max(1, $item['quantity']), 2) }}</td>
        <td>${{ number_format($item['price'], 2) }}</td>
    </tr>
    @endif
    @endforeach
    
    @if($isPdf)
        </tbody>
    </table>
    @endif

    <!-- Total de la Proforma -->
    @if(!$isPdf)
    <div class="proforma-section proforma-total">
        <p style="font-size:16px; color: #1e40af; margin-bottom: 10px;">Total de la Proforma:</p>
        <span class="total-badge">${{ number_format($total_price ?? 0, 2) }}</span>
        <p style="font-size:13px; margin-top: 10px; color: #6b7280;">Incluye {{ collect($items)->sum('quantity') }} {{ collect($items)->sum('quantity') == 1 ? 'producto' : 'productos' }}</p>
    </div>
    @else
    <!-- PDF: Sección de totales -->
    <div class="totals-section">
        <div class="terms-conditions">
            <h4>Términos & Condiciones</h4>
            <p>Esta proforma es válida por 30 días desde la fecha de emisión. Los precios incluyen IVA.</p>
        </div>
        <div class="totals-box">
            <div class="total-row subtotal">
                <span>Subtotal:</span>
                <span>${{ number_format(($total_price ?? 0) / 1.15, 2) }}</span>
            </div>
            <div class="total-row subtotal">
                <span>IVA (15%):</span>
                <span>${{ number_format(($total_price ?? 0) - (($total_price ?? 0) / 1.15), 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total:</span>
                <span>${{ number_format($total_price ?? 0, 2) }}</span>
            </div>
        </div>
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
    @endif

@else
    <!-- Vista Single-Item (Legacy) -->
    @if(isset($product) && $product)
    @if(!$isPdf)
    <div class="mt-4">
        <h4 class="text-sm font-semibold text-white mb-2">Datos Generales del Producto</h4>
        <div class="bg-black/30 border border-white/20 rounded-xl overflow-hidden">
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-white/10">
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white w-2/5">Nombre del Producto</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">{{ $product->name }}</td>
                    </tr>
                    <tr class="border-b border-white/10">
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Color Aluminio</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">{{ $parameters['color'] ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-white/10">
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Color Vidrio</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">{{ $parameters['glassColor'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Dimensiones</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">
                            Ancho: {{ $parameters['width'] ?? '-' }} m, Alto: {{ $parameters['height'] ?? '-' }} m
                        </td>
                    </tr>
                    <tr class="border-b border-white/10">
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Cantidad</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">
                            {{ $quantity ?? 1 }} {{ ($quantity ?? 1) == 1 ? 'unidad' : 'unidades' }}
                        </td>
                    </tr>
                    <tr class="border-b border-white/10">
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Precio Unitario</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">
                            ${{ number_format($calculatedPrice / max(1, $quantity ?? 1), 2) }}
                        </td>
                    </tr>
                    @if(isset($expiration_date) && ((isset($showExpirationInfo) && $showExpirationInfo) || $isPdf))
                    <tr>
                        <th class="bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white">Fecha de Expiración</th>
                        <td class="px-4 py-2.5 text-sm text-white/80">
                            {{ \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Precio Total -->
    <div class="mt-4 p-4 bg-gradient-to-r from-cyan-700/30 to-slate-700/30 border border-cyan-400/40 rounded-xl">
        <p class="text-sm text-white/70 mb-1">
            <span>Precio del Producto:</span>
        </p>
        <p class="text-4xl font-bold text-white">
            <span>${{ number_format($calculatedPrice, 2) }}</span>
        </p>
    </div>

    <!-- Notas -->
    @if(!empty($notes))
    <div class="mt-4 p-3 bg-black/30 border border-white/20 rounded-xl">
        <strong class="text-white text-sm">Notas del Cliente:</strong>
        <p class="text-white/70 text-sm mt-1">{{ $notes }}</p>
    </div>
    @endif
    @else
    <!-- PDF: Vista single item -->
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 80px;">Cant.</th>
                <th>Descripción del Producto</th>
                <th style="width: 120px; text-align: right;">Precio Unit.</th>
                <th style="width: 120px;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="qty-col">{{ $quantity ?? 1 }}</td>
                <td>
                    <strong>{{ $product->name }}</strong><br>
                    @if(isset($parameters['color']))
                        <span style="font-size: 11px; color: #718096;">Color: {{ $parameters['color'] }}</span>
                    @endif
                    @if(isset($parameters['width']) && isset($parameters['height']))
                        <br><span style="font-size: 11px; color: #718096;">Dimensiones: {{ $parameters['width'] }} x {{ $parameters['height'] }} m</span>
                    @endif
                    @if(!empty($notes))
                        <br><span style="font-size: 10px; color: #f59e0b;">📝 {{ $notes }}</span>
                    @endif
                </td>
                <td style="text-align: right;">${{ number_format($calculatedPrice / max(1, $quantity ?? 1), 2) }}</td>
                <td>${{ number_format($calculatedPrice, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- PDF: Sección de totales -->
    <div class="totals-section">
        <div class="terms-conditions">
            <h4>Términos & Condiciones</h4>
            <p>Esta proforma es válida por 30 días desde la fecha de emisión. Los precios incluyen IVA. Para proceder con el pedido, se requiere un anticipo del 50%.</p>
        </div>
        <div class="totals-box">
            <div class="total-row subtotal">
                <span>Subtotal:</span>
                <span>${{ number_format($calculatedPrice / 1.15, 2) }}</span>
            </div>
            <div class="total-row subtotal">
                <span>IVA (15%):</span>
                <span>${{ number_format($calculatedPrice - ($calculatedPrice / 1.15), 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total:</span>
                <span>${{ number_format($calculatedPrice, 2) }}</span>
            </div>
        </div>
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
    @endif
@endif
