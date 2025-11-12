@php
    $isPdf = $isPdf ?? false; // Por defecto es vista web
@endphp

@if($isPdf)
<style>
    .proforma-header { text-align: center; margin-bottom: 20px; }
    .proforma-section { margin-bottom: 20px; }
    .proforma-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .proforma-table th, .proforma-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
    .proforma-table th { background: #f5f5f5; }
    .proforma-total { font-weight: bold; font-size: 16px; }
</style>
@endif

<div class="{{ $isPdf ? 'proforma-header' : 'space-y-4' }}">
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
            <h2 class="text-xl font-bold text-white">Proforma de Producto</h2>
            <p class="text-sm text-white/80">{{ $product->name }} • {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    @else
    <h2>Proforma de Producto</h2>
    <p class="font-bold text-lg mb-1">{{ $product->name }}</p>
    <p>Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    @endif
    
    <!-- Información del Cliente -->
    @if(isset($user) && $user)
        <div class="{{ $isPdf ? 'mt-2 text-sm text-left' : 'p-3 bg-black/30 border border-white/20 rounded-xl' }}" @if($isPdf) style="margin: 0 auto; max-width: 400px;" @endif>
            <h4 class="{{ $isPdf ? 'font-bold' : 'text-sm font-semibold text-white mb-2' }}">Información del Cliente</h4>
            <div class="{{ $isPdf ? '' : 'grid grid-cols-2 gap-x-4 gap-y-1 text-sm text-white/80' }}">
                <p><strong class="{{ $isPdf ? '' : 'text-white' }}">Cliente:</strong> {{ $user->name ?? $user->email ?? 'Usuario' }}</p>
                @if(!empty($user->email))<p><strong class="{{ $isPdf ? '' : 'text-white' }}">Email:</strong> {{ $user->email }}</p>@endif
                @if(!empty($user->phone))<p><strong class="{{ $isPdf ? '' : 'text-white' }}">Teléfono:</strong> {{ $user->phone }}</p>@endif
                @if(!empty($user->city))<p><strong class="{{ $isPdf ? '' : 'text-white' }}">Ciudad:</strong> {{ $user->city }}, {{ $user->province }}</p>@endif
            </div>
        </div>
    @endif
</div>

<div class="{{ $isPdf ? 'proforma-section' : 'mt-4' }}">
    <h4 class="{{ $isPdf ? '' : 'text-sm font-semibold text-white mb-2' }}">Datos Generales del Producto</h4>
    <div class="{{ $isPdf ? '' : 'bg-black/30 border border-white/20 rounded-xl overflow-hidden' }}">
        <table class="{{ $isPdf ? 'proforma-table' : 'w-full' }}">
            <tbody>
                <tr class="{{ $isPdf ? '' : 'border-b border-white/10' }}">
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white w-2/5' }}">Nombre del Producto</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">{{ $product->name }}</td>
                </tr>
                <tr class="{{ $isPdf ? '' : 'border-b border-white/10' }}">
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Color Aluminio</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">{{ $parameters['color'] ?? '-' }}</td>
                </tr>
                <tr class="{{ $isPdf ? '' : 'border-b border-white/10' }}">
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Color Vidrio</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">{{ $parameters['glassColor'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Dimensiones</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">
                        Ancho: {{ $parameters['width'] ?? '-' }} m, Alto: {{ $parameters['height'] ?? '-' }} m
                    </td>
                </tr>
                <tr class="{{ $isPdf ? '' : 'border-b border-white/10' }}">
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Cantidad</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">
                        {{ $quantity ?? 1 }} {{ ($quantity ?? 1) == 1 ? 'unidad' : 'unidades' }}
                    </td>
                </tr>
                <tr class="{{ $isPdf ? '' : 'border-b border-white/10' }}">
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Precio Unitario</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">
                        ${{ number_format($calculatedPrice / max(1, $quantity ?? 1), 2) }}
                    </td>
                </tr>
                @if(isset($expiration_date) && ((isset($showExpirationInfo) && $showExpirationInfo) || $isPdf))
                <tr>
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">Fecha de Expiración</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">
                        {{ \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') }}
                    </td>
                </tr>
                <tr>
                    <th class="{{ $isPdf ? '' : 'bg-black/50 px-4 py-2.5 text-left text-sm font-medium text-white' }}">¿Expirada?</th>
                    <td class="{{ $isPdf ? '' : 'px-4 py-2.5 text-sm text-white/80' }}">
                        {{ $is_expired ? 'Sí' : 'No' }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Precio Total -->
<div class="{{ $isPdf ? 'proforma-section proforma-total' : 'mt-4 p-4 bg-gradient-to-r from-cyan-700/30 to-slate-700/30 border border-cyan-400/40 rounded-xl' }}">
    <p class="{{ $isPdf ? '' : 'text-sm text-white/70 mb-1' }}">
        <span @if($isPdf) style="font-size:18px;" @endif>Precio Final del Producto:</span>
    </p>
    <p class="{{ $isPdf ? '' : 'text-4xl font-bold text-white' }}">
        <span @if($isPdf) style="font-size:22px; font-weight:bold;" @endif>${{ number_format($calculatedPrice, 2) }}</span>
    </p>
</div>

<!-- Notas -->
@if(!empty($notes))
<div class="{{ $isPdf ? 'proforma-section' : 'mt-4 p-3 bg-black/30 border border-white/20 rounded-xl' }}">
    <strong class="{{ $isPdf ? '' : 'text-white text-sm' }}">Notas del Cliente:</strong>
    <p class="{{ $isPdf ? '' : 'text-white/70 text-sm mt-1' }}">{{ $notes }}</p>
</div>
@endif
