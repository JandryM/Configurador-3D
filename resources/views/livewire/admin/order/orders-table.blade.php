<div>
    <div class="glass-card rounded-2xl shadow-xl">
        <div class="p-6 border-b border-slate-200/50">
            <h2 class="text-xl font-bold text-slate-800">Gestión de Órdenes</h2>
            <p class="text-slate-600 mt-1">Administra y aprueba las órdenes de producción</p>
        </div>

        @if (session()->has('message'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm font-medium text-green-700">{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Número</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Cliente</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Producto</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Cantidad</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Monto</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Estado</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Fecha Creación</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Fecha Estimada</th>
                            <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $order['number'] }}</p>
                                            <p class="text-sm text-slate-500">Orden</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $order['client'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $order['email'] }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-slate-700">{{ $order['product_name'] }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-slate-700">
                                        {{ $order['quantity'] ?? 1 }} {{ ($order['quantity'] ?? 1) == 1 ? 'unidad' : 'unidades' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-medium text-slate-800">
                                        ${{ number_format($order['amount'], 2) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'in_production' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'approved' => 'Aprobada',
                                            'in_production' => 'En Producción',
                                            'completed' => 'Completada',
                                            'cancelled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order['status']] ?? $order['status'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-slate-700">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($order['estimated_finish_at'])
                                        <div>
                                            <span class="text-slate-700 font-medium">
                                                {{ \Carbon\Carbon::parse($order['estimated_finish_at'])->format('d/m/Y') }}
                                            </span>
                                            @if($order['status'] === 'in_production')
                                                @php
                                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($order['estimated_finish_at']), false);
                                                @endphp
                                                @if($daysRemaining > 0)
                                                    <p class="text-xs text-green-600">{{ ceil($daysRemaining) }} día(s) restantes</p>
                                                @elseif($daysRemaining < 0)
                                                    <p class="text-xs text-red-600">Retrasado {{ abs(floor($daysRemaining)) }} día(s)</p>
                                                @else
                                                    <p class="text-xs text-green-600">Finaliza hoy</p>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <button wire:click="showOrder({{ $order['id'] }})" 
                                                class="text-blue-600 hover:text-blue-800 transition-colors" 
                                                title="Ver detalles">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>

                                        @if($canModify)
                                            @if($order['status'] === 'pending')
                                                <button wire:click="approveOrder({{ $order['id'] }})" 
                                                        class="text-green-600 hover:text-green-800 transition-colors" 
                                                        title="Aprobar orden">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            @if($order['status'] === 'approved')
                                                <button wire:click="startProduction({{ $order['id'] }})" 
                                                        class="text-purple-600 hover:text-purple-800 transition-colors" 
                                                        title="Iniciar producción">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            @endif

                                            @if($order['status'] === 'in_production')
                                                <button wire:click="completeOrder({{ $order['id'] }})" 
                                                        class="text-green-600 hover:text-green-800 transition-colors" 
                                                        title="Marcar como completada">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @endif

                                        @if(in_array($order['status'], ['pending', 'approved']))
                                            <button wire:click="cancelOrder({{ $order['id'] }})" 
                                                    class="text-red-600 hover:text-red-800 transition-colors" 
                                                    title="Cancelar orden"
                                                    onclick="return confirm('¿Estás seguro de cancelar esta orden?')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-500">
                                    No hay órdenes registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Detalles de Orden -->
    @if($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative" style="max-height: 90vh; display: flex; flex-direction: column;">
                    <!-- Header -->
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Detalles de Orden: {{ $selectedOrder['number'] }}</h2>
                                <p class="text-sm text-gray-600">Gestión completa de la orden</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido -->
                    <div class="overflow-y-auto px-6 py-4" style="flex: 1;">
                        <!-- Información de la Orden -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Cliente</p>
                                    <p class="font-medium text-gray-800">{{ $selectedOrder['client'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Estado Actual</p>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'in_production' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'approved' => 'Aprobada',
                                            'in_production' => 'En Producción',
                                            'completed' => 'Completada',
                                            'cancelled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$selectedOrder['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$selectedOrder['status']] ?? $selectedOrder['status'] }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Cantidad</p>
                                    <p class="font-medium text-gray-800">{{ $selectedOrder['quantity'] ?? 1 }} {{ ($selectedOrder['quantity'] ?? 1) == 1 ? 'unidad' : 'unidades' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Monto Total</p>
                                    <p class="font-bold text-lg text-gray-800">${{ number_format($selectedOrder['amount'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Fecha de Creación</p>
                                    <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($selectedOrder['created_at'])->format('d/m/Y H:i') }}</p>
                                </div>
                                @if($selectedOrder['status'] === 'in_production' && $selectedOrder['estimated_finish_at'])
                                    <div class="col-span-2">
                                        <p class="text-sm text-gray-600">Fecha Estimada de Finalización</p>
                                        <p class="font-medium text-purple-600">
                                            {{ \Carbon\Carbon::parse($selectedOrder['estimated_finish_at'])->format('d/m/Y') }}
                                        </p>
                                        @php
                                            $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($selectedOrder['estimated_finish_at']), false);
                                        @endphp
                                        @if($daysRemaining > 0)
                                            <p class="text-xs text-gray-500">Faltan {{ ceil($daysRemaining) }} día(s)</p>
                                        @elseif($daysRemaining < 0)
                                            <p class="text-xs text-red-500">Retrasado por {{ abs(floor($daysRemaining)) }} día(s)</p>
                                        @else
                                            <p class="text-xs text-green-500">Finaliza hoy</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        @if($canModify)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Acciones de Gestión</h3>
                                <div class="flex flex-wrap gap-3">
                                    @if($selectedOrder['status'] === 'pending')
                                        <button wire:click="approveOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                            ✓ Aprobar Orden
                                        </button>
                                        <button wire:click="cancelOrder({{ $selectedOrder['id'] }})" 
                                                onclick="return confirm('¿Estás seguro de cancelar esta orden?')"
                                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                            ✗ Cancelar Orden
                                        </button>
                                    @elseif($selectedOrder['status'] === 'approved')
                                        <button wire:click="startProduction({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                            ▶ Iniciar Producción
                                        </button>
                                        <button wire:click="cancelOrder({{ $selectedOrder['id'] }})" 
                                                onclick="return confirm('¿Estás seguro de cancelar esta orden?')"
                                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                            ✗ Cancelar Orden
                                        </button>
                                    @elseif($selectedOrder['status'] === 'in_production')
                                        <button wire:click="completeOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                                            ✓ Marcar como Completada
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Modo Solo Lectura:</strong> No tienes permisos para modificar el estado de las órdenes. Solo puedes visualizar la información.
                                </p>
                            </div>
                        @endif

                        <!-- Detalle de Productos de la Proforma -->
                        @if(isset($selectedOrder['items']) && is_array($selectedOrder['items']) && count($selectedOrder['items']) > 0)
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Productos ({{ count($selectedOrder['items']) }})</h3>
                                <div class="space-y-2">
                                    @foreach($selectedOrder['items'] as $index => $item)
                                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md hover:border-indigo-300">
                                            <!-- Header del producto (siempre visible) -->
                                            <button 
                                                type="button"
                                                onclick="document.getElementById('product-detail-{{ $index }}').classList.toggle('hidden'); document.getElementById('chevron-{{ $index }}').classList.toggle('rotate-180')"
                                                class="w-full px-3 py-2 flex items-center justify-between hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors"
                                            >
                                                <div class="flex items-center space-x-2 flex-1 min-w-0">
                                                    <div class="w-7 h-7 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-md flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div class="text-left flex-1 min-w-0">
                                                        <p class="font-semibold text-gray-800 text-sm truncate">
                                                            {{ $item['product_name'] ?? ($item['product'] ? $item['product']->name : 'Producto eliminado') }}
                                                        </p>
                                                        <p class="text-xs text-gray-600">
                                                            <span class="font-medium text-indigo-600">{{ $item['quantity'] ?? 1 }}×</span> ${{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 2) }}
                                                            <span class="mx-1">•</span>
                                                            <span class="font-semibold text-gray-800">${{ number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? $item['price'] ?? 0), 2) }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <svg 
                                                    id="chevron-{{ $index }}"
                                                    class="w-5 h-5 text-gray-500 transition-transform duration-200 flex-shrink-0" 
                                                    fill="currentColor" 
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>

                                            <!-- Detalle del producto (colapsable) -->
                                            <div id="product-detail-{{ $index }}" class="hidden border-t border-gray-300">
                                                <div class="px-3 py-2 bg-white">
                                                    @php
                                                        // Campos permitidos y sus traducciones
                                                        $allowedFields = [
                                                            'height' => 'Alto',
                                                            'width' => 'Ancho',
                                                            'color' => 'Color aluminio',
                                                            'glassColor' => 'Color de vidrio',
                                                            'material' => 'Material',
                                                            'quantity' => 'Cantidad',
                                                            'profile' => 'Perfil',
                                                            'glass' => 'Vidrio',
                                                            'type' => 'Tipo',
                                                            'finish' => 'Acabado',
                                                        ];
                                                    @endphp
                                                    @if(isset($item['configuration']['parameters']))
                                                        <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1.5">Configuración</p>
                                                        <div class="grid grid-cols-3 gap-1.5 mb-2">
                                                            @foreach($allowedFields as $key => $label)
                                                                @if(isset($item['configuration']['parameters'][$key]) && !is_array($item['configuration']['parameters'][$key]))
                                                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded px-2 py-1.5">
                                                                        <p class="text-xs text-gray-600 leading-tight">{{ $label }}</p>
                                                                        <p class="text-xs font-semibold text-gray-800 leading-tight mt-0.5">{{ $item['configuration']['parameters'][$key] }}</p>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if(isset($item['configuration']['parameters']['notes']) && !empty($item['configuration']['parameters']['notes']))
                                                        <div class="bg-yellow-50 border border-yellow-200 rounded px-2 py-1.5">
                                                            <p class="text-xs font-medium text-yellow-800 mb-0.5">📝 Notas</p>
                                                            <p class="text-xs text-gray-700">{{ $item['configuration']['parameters']['notes'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Tiempo Estimado -->
    @if($showEstimatedTimeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1100;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="cancelEstimatedTimeModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Tiempo Estimado de Producción</h3>
                                    <p class="text-sm text-white/80">Ingresa los días estimados</p>
                                </div>
                            </div>
                            <button type="button" wire:click="cancelEstimatedTimeModal" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        @if(session()->has('error'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 bg-red-50">{{ session('error') }}</p>
                            </div>
                        @endif

                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-slate-700 bg-white">
                                    Tiempo estimado de producción *
                                </label>
                                <button 
                                    type="button"
                                    wire:click="toggleCustomDate"
                                    class="text-xs px-3 py-1 rounded-lg transition-colors {{ $customDate ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                                >
                                    {{ $customDate ? '📅 Fecha personalizada' : '🔧 Personalizar fecha' }}
                                </button>
                            </div>

                            @if(!$customDate)
                                <!-- Selector de periodos predefinidos -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 3)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 3 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 3 ? 'text-purple-600' : 'text-slate-700' }}">3</div>
                                            <div class="text-xs {{ $estimatedDays == 3 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 7)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 7 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 7 ? 'text-purple-600' : 'text-slate-700' }}">7</div>
                                            <div class="text-xs {{ $estimatedDays == 7 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (1 semana)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 14)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 14 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 14 ? 'text-purple-600' : 'text-slate-700' }}">14</div>
                                            <div class="text-xs {{ $estimatedDays == 14 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (2 semanas)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 21)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 21 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 21 ? 'text-purple-600' : 'text-slate-700' }}">21</div>
                                            <div class="text-xs {{ $estimatedDays == 21 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (3 semanas)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 30)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 30 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 30 ? 'text-purple-600' : 'text-slate-700' }}">30</div>
                                            <div class="text-xs {{ $estimatedDays == 30 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (1 mes)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 60)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 60 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 60 ? 'text-purple-600' : 'text-slate-700' }}">60</div>
                                            <div class="text-xs {{ $estimatedDays == 60 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (2 meses)</div>
                                        </div>
                                    </button>
                                </div>
                            @else
                                <!-- Selector de fecha personalizada -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2 bg-white">
                                        Selecciona la fecha estimada de finalización
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model.live="customEstimatedDate"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition shadow-sm bg-white text-slate-900"
                                    >
                                </div>
                            @endif
                        </div>

                        <!-- Vista previa de fecha -->
                        @php
                            $previewDate = null;
                            if ($customDate && $customEstimatedDate) {
                                $previewDate = \Carbon\Carbon::parse($customEstimatedDate);
                            } elseif (!$customDate && $estimatedDays) {
                                $previewDate = now()->addDays($estimatedDays);
                            }
                        @endphp

                        @if($previewDate)
                            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-purple-900 bg-purple-50">Fecha estimada de finalización:</span>
                                </div>
                                <p class="text-lg font-bold text-purple-700 bg-purple-50">
                                    {{ $previewDate->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-purple-600 mt-1 bg-purple-50">
                                    ({{ now()->diffInDays($previewDate, false) >= 0 ? 'En ' . ceil(now()->diffInDays($previewDate)) . ' día(s)' : 'Fecha pasada' }})
                                </p>
                            </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="flex space-x-3 bg-white">
                            <button 
                                type="button"
                                wire:click="cancelEstimatedTimeModal"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="button"
                                wire:click="confirmStartProduction"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all"
                            >
                                ▶ Iniciar Producción
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
