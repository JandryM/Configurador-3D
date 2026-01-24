@php use Illuminate\Support\Str; @endphp

@php
    $isMultiItem = isset($items) && is_array($items) && count($items) > 0;
    $displayNumber = $number ?? 'PRF-' . str_pad($proforma_id ?? 0, 3, '0', STR_PAD_LEFT);
    $totalQuantity = $isMultiItem ? collect($items)->sum('quantity') : ($quantity ?? 1);
@endphp

<!-- Header de la Proforma -->
<div class="mb-6 p-6 bg-gradient-to-r from-slate-700 to-slate-800 rounded-xl border border-slate-600 shadow-lg">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-100 mb-2">Proforma de Producto{{ $isMultiItem && count($items) > 1 ? 's' : '' }}</h2>
        <p class="text-lg font-bold text-slate-300 mb-1">{{ $displayNumber }}</p>
        <p class="text-sm text-slate-400">Fecha: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    
    @if(isset($user) && $user)
        <div class="mt-4 bg-slate-600/50 backdrop-blur-sm rounded-lg p-4 shadow-sm max-w-md mx-auto border border-slate-500">
            <h3 class="font-semibold text-gray-100 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Información del Cliente
            </h3>
            <div class="text-sm space-y-1 text-gray-300">
                <p><strong class="text-gray-100">Cliente:</strong> {{ $user->name ?? $user->email ?? 'Usuario' }}</p>
                @if(!empty($user->email))<p><strong class="text-gray-100">Email:</strong> {{ $user->email }}</p>@endif
                @if(!empty($user->phone))<p><strong class="text-gray-100">Teléfono:</strong> {{ $user->phone }}</p>@endif
                @if(!empty($user->address))<p><strong class="text-gray-100">Dirección:</strong> {{ $user->address }}</p>@endif
                @if(!empty($user->province))<p><strong class="text-gray-100">Provincia:</strong> {{ $user->province }}</p>@endif
                @if(!empty($user->city))<p><strong class="text-gray-100">Ciudad:</strong> {{ $user->city }}</p>@endif
            </div>
        </div>
    @endif
</div>

@if($isMultiItem)
    <!-- Vista Multi-Item -->
    <div class="mb-6">
        <h4 class="text-lg font-semibold text-gray-200 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
            </svg>
            Información General de la Proforma
        </h4>
        <div class="bg-slate-700/50 backdrop-blur-sm rounded-xl p-5 border border-slate-600 shadow-lg">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400 mb-1">Número de Proforma</p>
                    <p class="font-semibold text-gray-200">{{ $displayNumber }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Configuraciones</p>
                    <p class="font-semibold text-gray-200">{{ count($items) }} {{ count($items) == 1 ? 'configuración' : 'configuraciones' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Cantidad Total de Productos</p>
                    <p class="font-semibold text-gray-200">{{ $totalQuantity }} {{ $totalQuantity == 1 ? 'producto' : 'productos' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Estado de la Proforma</p>
                    @if(isset($is_expired))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $is_expired ? 'bg-red-900/50 text-red-300 border border-red-700' : 'bg-emerald-900/50 text-emerald-300 border border-emerald-700' }}">
                            {{ $is_expired ? '❌ Expirada' : '✓ Vigente' }}
                        </span>
                    @else
                        <span class="text-slate-400">-</span>
                    @endif
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-600">
                <p class="text-xs text-slate-400 mb-1">Fecha de Expiración</p>
                <p class="font-semibold text-gray-200">{{ isset($expiration_date) ? \Carbon\Carbon::parse($expiration_date)->format('d/m/Y H:i') : '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Ítems de la Proforma -->
    @foreach($items as $index => $item)
    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 mb-6 border-l-4 border-slate-500 shadow-lg">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-lg flex items-center justify-center shadow-md mr-3 border border-slate-500">
                <span class="text-gray-200 font-bold text-lg">{{ $index + 1 }}</span>
            </div>
            <h4 class="text-xl font-bold text-gray-100">{{ $item['product_name'] }}</h4>
        </div>
        
        <!-- Datos del Producto -->
        <div class="mb-6">
            <h5 class="text-md font-semibold text-gray-200 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                Datos del Producto
            </h5>
            <div class="bg-slate-700/50 backdrop-blur-sm rounded-lg p-4 space-y-3 border border-slate-600">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Nombre del Producto</p>
                        <p class="font-medium text-gray-200">{{ $item['product_name'] }}</p>
                    </div>
                    @if(isset($item['parameters']['color']))
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Color Aluminio</p>
                        <p class="font-medium text-gray-200">{{ $item['parameters']['color'] }}</p>
                    </div>
                    @endif
                    @if(isset($item['parameters']['glassColor']))
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Color Vidrio</p>
                        <p class="font-medium text-gray-200">{{ $item['parameters']['glassColor'] }}</p>
                    </div>
                    @endif
                    @if(isset($item['parameters']['width']) && isset($item['parameters']['height']))
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 mb-1">Dimensiones</p>
                        <p class="font-medium text-gray-200">
                            Ancho: {{ $item['parameters']['width'] }} m | Alto: {{ $item['parameters']['height'] }} m
                            @if(isset($item['parameters']['depth']))
                                | Profundidad: {{ $item['parameters']['depth'] }} m
                            @endif
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Cantidad</p>
                        <p class="font-medium text-gray-200">{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Precio Unitario</p>
                        <p class="font-medium text-gray-200">${{ number_format($item['price'] / max(1, $item['quantity']), 2) }}</p>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-600">
                    <div class="flex justify-between items-center bg-slate-700/70 rounded-lg p-3 border border-slate-600">
                        <span class="font-semibold text-gray-200">Subtotal Ítem</span>
                        <span class="text-lg font-bold text-slate-300">${{ number_format($item['price'], 2) }}</span>
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
            <h5 class="text-md font-semibold text-gray-200 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Desglose de Materiales
            </h5>
            <div class="bg-slate-800/50 rounded-lg overflow-hidden border border-slate-600">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-600">
                        <thead class="bg-slate-700/70">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Unidad</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">P. Unit.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Inc. Color</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-800/30 divide-y divide-slate-600">
                        @foreach($item['materialCosts'] as $mat)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-200">{{ $mat['name'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ number_format($mat['quantity'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $mat['unit'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-200">${{ number_format($mat['unit_price'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-orange-400">${{ number_format($mat['color_increase'] ?? 0, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-100">${{ number_format($mat['total_cost'] ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-slate-700/70 font-bold border-t-2 border-slate-500">
                            <td colspan="5" class="px-4 py-3 text-right text-sm text-gray-200">Subtotal Materiales:</td>
                            <td class="px-4 py-3 text-sm font-bold text-slate-300">${{ number_format($totalMateriales, 2) }}</td>
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
            <h5 class="text-md font-semibold text-gray-200 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                </svg>
                Análisis de Costos
            </h5>
            <div class="bg-slate-800/50 rounded-lg p-5 border border-slate-600">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-600/50">
                        <span class="text-sm text-gray-300">Costo de Materiales</span>
                        <span class="text-sm font-semibold text-gray-100">${{ number_format($totalMateriales, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-600/50">
                        <span class="text-sm text-gray-300">Costo de Mano de Obra ({{ $directCost }}%)</span>
                        <span class="text-sm font-semibold text-gray-100">${{ number_format($directAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-600/50">
                        <span class="text-sm text-gray-300">Costos Indirectos ({{ $indirectCost }}%)</span>
                        <span class="text-sm font-semibold text-gray-100">${{ number_format($indirectAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-600/50">
                        <span class="text-sm text-gray-300">Factor de merma ({{ $wastePercentage }}%)</span>
                        <span class="text-sm font-semibold text-gray-100">${{ number_format($wasteAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-700/70 rounded-lg px-3 mt-2 border border-slate-600">
                        <span class="text-sm font-semibold text-gray-200">Costo Total de Producción</span>
                        <span class="text-sm font-bold text-slate-300">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-t border-slate-600 pt-3 mt-2">
                        <span class="text-sm text-gray-300">Margen de Utilidad ({{ $profitMargin }}%)</span>
                        <span class="text-sm font-semibold text-emerald-400">${{ number_format($profitAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 bg-slate-700/70 rounded-lg px-3 mt-2 border border-slate-500">
                        <span class="text-base font-bold text-gray-100">Subtotal</span>
                        <span class="text-lg font-bold text-emerald-400">${{ number_format($subtotal + $profitAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Notas del Ítem -->
        @if(!empty($item['notes']))
        <div>
            <h5 class="text-md font-semibold text-gray-200 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                </svg>
                Notas
            </h5>
            <div class="bg-slate-800/50 border-l-4 border-slate-500 rounded-lg p-4 backdrop-blur-sm">
                <p class="text-sm text-gray-300">{{ $item['notes'] }}</p>
            </div>
        </div>
        @endif
    </div>
    @endforeach

    <div class="bg-gradient-to-r from-slate-700 to-slate-800 text-white rounded-xl p-6 shadow-lg border border-slate-600">
        <p class="text-lg font-semibold mb-4 text-slate-300">Total de la Proforma</p>
        <div class="space-y-2 text-left max-w-md mx-auto">
            <div class="flex justify-between text-sm">
                <span class="text-slate-400">Subtotal (sin IVA):</span>
                <span class="text-gray-200">${{ number_format(($total_price ?? 0) / 1.15, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-400">IVA (15%):</span>
                <span class="text-gray-200">${{ number_format(($total_price ?? 0) - (($total_price ?? 0) / 1.15), 2) }}</span>
            </div>
            <div class="border-t-2 border-slate-500 pt-2 mt-2">
                <div class="flex justify-between">
                    <span class="text-xl font-bold text-gray-100">Total:</span>
                    <span class="text-3xl font-bold text-gray-100">${{ number_format($total_price ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        <p class="text-sm text-slate-400 mt-4 text-center">{{ count($items) }} {{ count($items) == 1 ? 'configuración' : 'configuraciones' }} • {{ $totalQuantity }} {{ $totalQuantity == 1 ? 'producto' : 'productos' }}</p>
    </div>

@else
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
                    Ancho: {{ $parameters['width'] ?? '-' }} m | Alto: {{ $parameters['height'] ?? '-' }} m
                    @if(isset($parameters['depth']))
                        | Profundidad: {{ $parameters['depth'] }} m
                    @endif
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
                'depth' => 'Profundidad (m)',
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
                <td class="px-4 py-3 text-sm text-gray-300">{{ number_format($mat['quantity'] ?? 0, 2) }}</td>
                <td class="px-4 py-3 text-sm text-gray-300">{{ $mat['unit'] ?? '-' }}</td>
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

@if(isset($showDownloadButton) && $showDownloadButton)
<div class="flex justify-end mt-4">
    <button type="button" wire:click="downloadProformaPdf" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold shadow-lg transition-colors border border-emerald-500">Descargar PDF</button>
</div>
@endif