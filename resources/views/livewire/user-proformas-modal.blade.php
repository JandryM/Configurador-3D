<div>
    <!-- Mostrar mensaje de error si existe -->
    @if(session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
            <div class="bg-red-600/90 text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border border-red-400/40 animate-fade-in">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <!-- Mostrar mensaje de éxito si existe -->
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
            <div class="bg-green-600/90 text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border border-green-400/40 animate-fade-in">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    <!-- Modal principal de listado de proformas -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
           <div x-data="{ confirmDelete: false, deleteMessage: null }" class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative z-10 text-white" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Header -->
                <div class="sticky top-0 bg-black/50 backdrop-blur-sm border-b border-white/20 px-6 py-4 rounded-t-2xl flex justify-between items-center" wire:click="clearSelection">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">
                                @if($activeTab === 'proformas')
                                    Mis Proformas
                                @else
                                    Mis Órdenes
                                @endif
                            </h2>
                            <p class="text-sm text-white/80">
                                @if($activeTab === 'proformas')
                                    Historial de cotizaciones guardadas
                                @else
                                    Seguimiento de pedidos realizados
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class= "relative group">
                        <button type="button" wire:click="closeModal" class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Cerrar
                        </span>
                    </div>
                </div>

                <!-- Pestañas (Tabs) -->
                <div class="px-6 pt-4 border-b border-white/20">
                    <div class="flex gap-2">
                        <button wire:click="switchTab('proformas')" 
                                class="px-6 py-3 font-semibold transition-all duration-200 rounded-t-lg {{ $activeTab === 'proformas' ? 'bg-gradient-to-r from-blue-600 to-cyan-700 text-white' : 'bg-white/5 text-white/60 hover:text-white hover:bg-white/10' }} cursor-pointer">
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                            Proformas ({{ $totalProformas }})
                        </button>
                        <button wire:click="switchTab('orders')" 
                                class="px-6 py-3 font-semibold transition-all duration-200 rounded-t-lg {{ $activeTab === 'orders' ? 'bg-gradient-to-r from-emerald-600 to-green-700 text-white' : 'bg-white/5 text-white/60 hover:text-white hover:bg-white/10' }} cursor-pointer">
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                            </svg>
                            Órdenes ({{ $totalOrdenes }})
                        </button>
                    </div>
                </div>
                
                <!-- Contenido dinámico (Proformas u Órdenes) -->
                <div class="overflow-y-auto px-6 py-4" style="flex: 1;" wire:click="clearSelection">
                    @if($activeTab === 'proformas' && empty($proformas))
                        <div class="bg-yellow-500/20 backdrop-blur-sm border border-yellow-500/30 text-yellow-200 p-4 rounded-lg text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p>No tienes proformas guardadas.</p>
                        </div>
                    @elseif($activeTab === 'orders' && empty($orders))
                        <div class="bg-yellow-500/20 backdrop-blur-sm border border-yellow-500/30 text-yellow-200 p-4 rounded-lg text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                            </svg>
                            <p>No tienes órdenes creadas.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg" @click.stop>
                                <thead>
                                    <tr class="bg-white/10 backdrop-blur-sm">
                                        @if($activeTab === 'proformas')
                                        <th class="px-4 py-3 border-b border-white/20 text-center text-sm font-semibold text-white w-12" onclick="event.stopPropagation()">
                                            <input type="checkbox" 
                                                   wire:model.live="selectAll"
                                                   class="w-4 h-4 rounded border-white/30 bg-white/10 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-0 cursor-pointer">
                                        </th>
                                        @endif
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            @if($activeTab === 'proformas') Número @else Número Orden @endif
                                        </th>
                                        @if($activeTab === 'orders')
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Proforma
                                        </th>
                                        @endif
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Productos
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Fecha
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Estado
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Total
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white @if($activeTab === 'proformas') min-w-[260px] w-[260px] @else min-w-[120px] w-[120px] @endif">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maxRows = 5;
                                        $items = $activeTab === 'proformas' ? $proformas : $orders;
                                        $rowCount = count($items);
                                    @endphp
                                    @foreach($items as $item)
                                        <tr class="hover:bg-white/5 transition-colors @if($activeTab === 'proformas') cursor-pointer {{ in_array($item['id'], $selectedProformas) ? 'bg-cyan-500/10 border-l-4 border-cyan-500' : '' }} @endif"
                                            @if($activeTab === 'proformas' && !$item['is_ordered']) wire:click="toggleProformaSelection({{ $item['id'] }})" @endif>
                                            @if($activeTab === 'proformas')
                                            <td class="px-4 py-3 border-b border-white/10 text-center" onclick="event.stopPropagation()">
                                                <input type="checkbox" 
                                                       wire:model.live="selectedProformas"
                                                       value="{{ $item['id'] }}"
                                                       @if($item['is_ordered']) disabled @endif
                                                       class="w-4 h-4 rounded border-white/30 bg-white/10 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-0 {{ $item['is_ordered'] ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}">
                                            </td>
                                            @endif
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-white relative group">
                                                {{ $item['number'] }}
                                                <span class="absolute bottom-full left-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Número único de @if($activeTab === 'proformas') la proforma @else orden @endif
                                                </span>
                                            </td>
                                            @if($activeTab === 'orders')
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80 relative group">
                                                {{ $item['proforma_number'] }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Proforma de origen
                                                </span>
                                            </td>
                                            @endif
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80 relative group">
                                                {{ $item['total_quantity'] }} {{ $item['total_quantity'] == 1 ? 'producto' : 'productos' }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Cantidad total de productos
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80 relative group">
                                                {{ \Carbon\Carbon::parse($item['created_at'])->format('d/m/Y') }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Fecha de creación
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm relative group">
                                                @if($activeTab === 'proformas')
                                                    @if($item['is_expired'])
                                                        <span class="px-2 py-1 bg-red-500/20 backdrop-blur-sm border border-red-500/30 text-red-300 rounded-full text-xs font-semibold">
                                                            Expirada
                                                        </span>
                                                    @elseif($item['is_ordered'])
                                                        <span class="px-2 py-1 bg-blue-500/20 backdrop-blur-sm border border-blue-500/30 text-blue-300 rounded-full text-xs font-semibold">
                                                            Ordenada
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 bg-green-500/20 backdrop-blur-sm border border-green-500/30 text-green-300 rounded-full text-xs font-semibold">
                                                            Activa
                                                        </span>
                                                    @endif
                                                @else
                                                    @if($item['status'] === 'pending')
                                                        <span class="px-2 py-1 bg-yellow-500/20 backdrop-blur-sm border border-yellow-500/30 text-yellow-300 rounded-full text-xs font-semibold">
                                                            En Aprobación
                                                        </span>
                                                    @elseif($item['status'] === 'approved')
                                                        <span class="px-2 py-1 bg-blue-500/20 backdrop-blur-sm border border-blue-500/30 text-blue-300 rounded-full text-xs font-semibold">
                                                            Pendiente Pago
                                                        </span>
                                                    @elseif($item['status'] === 'paid')
                                                        <span class="px-2 py-1 bg-emerald-500/20 backdrop-blur-sm border border-emerald-500/30 text-emerald-300 rounded-full text-xs font-semibold">
                                                            Pagada
                                                        </span>
                                                    @elseif($item['status'] === 'in_production')
                                                        <span class="px-2 py-1 bg-purple-500/20 backdrop-blur-sm border border-purple-500/30 text-purple-300 rounded-full text-xs font-semibold">
                                                            En Producción
                                                        </span>
                                                    @elseif($item['status'] === 'completed')
                                                        <span class="px-2 py-1 bg-green-500/20 backdrop-blur-sm border border-green-500/30 text-green-300 rounded-full text-xs font-semibold">
                                                            Completada
                                                        </span>
                                                    @elseif($item['status'] === 'cancelled')
                                                        <span class="px-2 py-1 bg-red-500/20 backdrop-blur-sm border border-red-500/30 text-red-300 rounded-full text-xs font-semibold">
                                                            Rechazada
                                                        </span>
                                                    @endif
                                                @endif
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Estado actual
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-green-400 relative group">
                                                ${{ number_format($item['total_price'], 2) }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Precio total
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 @if($activeTab === 'proformas') min-w-[260px] w-[260px] @else min-w-[120px] w-[120px] @endif" @if($activeTab === 'proformas') onclick="event.stopPropagation()" @endif>
                                                @if($activeTab === 'proformas')
                                                <div class="flex gap-2 min-w-[260px] w-[260px]">
                                                    <div class="relative group">
                                                        <button wire:click="showProforma({{ $item['id'] }})" 
                                                                class="px-4 text-xs font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-700 hover:from-blue-700 hover:to-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl h-[38px] flex items-center justify-center cursor-pointer">
                                                            Ver
                                                        </button>
                                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Ver detalles de la proforma
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button wire:click="downloadProformaPdf({{ $item['id'] }})" 
                                                                class="px-4 text-xs font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl h-[38px] flex items-center justify-center cursor-pointer">
                                                            PDF
                                                        </button>
                                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Descargar documento PDF con todos los detalles
                                                        </span>
                                                    </div>
                                                    @if(!$item['is_expired'] && !$item['is_ordered'])
                                                    <div class="relative group">
                                                        <button wire:click="setConfirmOrderId({{ $item['id'] }})"
                                                                @if($confirmOrderId !== null) disabled @endif
                                                                class="px-3 text-xs font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold h-[38px] flex items-center justify-center whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                                            Ordenar Proforma
                                                        </button>
                                                        <span class="absolute bottom-full right-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Convertir esta proforma en orden de compra
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @else
                                                <div class="flex gap-2">
                                                    <div class="relative group">
                                                        <button wire:click="showOrder({{ $item['id'] }})" 
                                                                class="px-4 text-xs font-medium border border-emerald-600/40 rounded-lg text-white bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl h-[38px] flex items-center justify-center cursor-pointer">
                                                            Ver
                                                        </button>
                                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Ver detalles de la orden
                                                        </span>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @for($i = 0; $i < $maxRows - $rowCount; $i++)
                                        <tr class="h-[56px]">
                                            @if($activeTab === 'proformas')
                                            <td class="px-4 py-3 border-b border-white/10 text-center">&nbsp;</td>
                                            @endif
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-white">&nbsp;</td>
                                            @if($activeTab === 'orders')
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80">&nbsp;</td>
                                            @endif
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-green-400">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10">&nbsp;</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Controles de paginación -->
                <div class="px-6 pb-4 pt-2 border-t border-white/20">
                    <div class="flex justify-center items-center gap-3">
                        <nav aria-label="Paginación de @if($activeTab === 'proformas') proformas @else órdenes @endif" class="flex items-center gap-3 w-full justify-center">
                            <div class="flex items-center w-full">
                                <div class="flex-1 flex justify-start gap-2">
                                        <div class="relative group">
                                            @if($activeTab === 'proformas')
                                                <button wire:click="anteriorPaginaProformas" @if($paginaProformas <= 1) disabled aria-disabled="true" @endif
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                                    aria-label="Página anterior" tabindex="0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                                    <span class="hidden sm:inline"></span>
                                                </button>
                                            @else
                                                <button wire:click="anteriorPaginaOrdenes" @if($paginaOrdenes <= 1) disabled aria-disabled="true" @endif
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                                    aria-label="Página anterior" tabindex="0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                                    <span class="hidden sm:inline"></span>
                                                </button>
                                            @endif
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                Página anterior
                                            </span>
                                        </div>
                                        @if($activeTab === 'proformas' && count($selectedProformas) > 0)
                                            <div class="relative group">
                                                <button @click="confirmDelete = true"
                                                        :disabled="confirmDelete"
                                                        class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium border border-red-600/40 rounded-lg text-white bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar ({{ count($selectedProformas) }})
                                                </button>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Eliminar permanentemente las proformas seleccionadas
                                                </span>
                                            </div>
                                        @endif
                                </div>
                                <div class="flex flex-col items-center">
                                    @if($activeTab === 'proformas')
                                        <div wire:key="pagination-proformas-{{ $paginaProformas }}">
                                            <x-pagination-carousel 
                                                :currentPage="$paginaProformas"
                                                :totalPages="max(1, ceil($totalProformas / $proformasPorPagina))"
                                                updateMethod="actualizarProformas"
                                                color="cyan" />
                                        </div>
                                    @else
                                        <div wire:key="pagination-orders-{{ $paginaOrdenes }}">
                                            <x-pagination-carousel 
                                                :currentPage="$paginaOrdenes"
                                                :totalPages="max(1, ceil($totalOrdenes / $ordenesPorPagina))"
                                                updateMethod="actualizarOrdenes"
                                                color="emerald" />
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 flex justify-end">
                                    <div class="relative group">
                                        @if($activeTab === 'proformas')
                                            <button wire:click="siguientePaginaProformas" @if($paginaProformas >= ceil($totalProformas / $proformasPorPagina)) disabled aria-disabled="true" @endif
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                                aria-label="Página siguiente" tabindex="0">
                                                <span class="hidden sm:inline"></span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        @else
                                            <button wire:click="siguientePaginaOrdenes" @if($paginaOrdenes >= ceil($totalOrdenes / $ordenesPorPagina)) disabled aria-disabled="true" @endif
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                                aria-label="Página siguiente" tabindex="0">
                                                <span class="hidden sm:inline"></span>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        @endif
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                            Página siguiente
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                        <!-- Modal de confirmación de eliminación al nivel del contenedor principal -->
                        <div x-show="confirmDelete" x-transition.opacity.duration.300ms class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button @click="confirmDelete = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-red-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2 text-red-700">¿Confirmar eliminación?</h3>
                                    <p class="text-sm text-gray-700 mb-4 text-center">¿Estás seguro de que deseas eliminar {{ count($selectedProformas) }} {{ count($selectedProformas) == 1 ? 'proforma' : 'proformas' }} seleccionadas? Esta acción no se puede deshacer.</p>
                                    <!-- Banner de éxito/error -->
                                    <template x-if="deleteMessage">
                                        <div :class="deleteMessage.success ? 'bg-green-600/90 border-green-400/40' : 'bg-red-600/90 border-red-400/40'" class="text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border animate-fade-in mb-3 w-full" x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms">
                                            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <template x-if="deleteMessage.success">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </template>
                                                <template x-if="!deleteMessage.success">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                                                </template>
                                            </svg>
                                            <span x-text="deleteMessage.message"></span>
                                        </div>
                                    </template>
                                    <div class="flex gap-3 mt-2">
                                        <button @click="confirmDelete = false"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button @click="$wire.deleteSelectedProformas().then(() => { deleteMessage = { success: true, message: 'Proforma(s) eliminada(s) exitosamente.' }; setTimeout(() => deleteMessage = null, 2000); confirmDelete = false; }).catch(() => { deleteMessage = { success: false, message: 'Hubo un error al eliminar.' }; setTimeout(() => deleteMessage = null, 2000); });"
                                                class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de confirmación de orden -->
                        @if($confirmOrderId !== null)
                        <div x-data="{}" x-init="setTimeout(() => window.scrollTo(0,0), 10)" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button wire:click="setConfirmOrderId(null)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-amber-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2">¿Confirmar orden?</h3>
                                    @php
                                        $proformaConfirm = collect($proformas)->firstWhere('id', $confirmOrderId);
                                    @endphp
                                    <p class="text-sm text-gray-700 mb-4 text-center">
                                        @if($proformaConfirm && isset($proformaConfirm['number']))
                                            ¿Estás seguro de que deseas crear una orden con la proforma <span class="font-bold text-amber-700">#{{ $proformaConfirm['number'] }}</span>?
                                        @else
                                            ¿Estás seguro de que deseas crear una orden con esta proforma?
                                        @endif
                                    </p>
                                    <div class="flex gap-3 mt-2">
                                        <button wire:click="setConfirmOrderId(null)"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button wire:click="orderProforma({{ $confirmOrderId }})"
                                                class="px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700 transition">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal secundario para ver proforma individual -->
    @if($showProformaModal && $selectedProforma)
    <div class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro más intenso -->
            <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeProformaModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative z-10 p-6 md:p-8 text-white" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Botón cerrar -->
                 <div class= "relative group">
                <button wire:click="closeProformaModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10 cursor-pointer">&times;</button>
                    <span class="absolute bottom-full right-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        Cerrar
                    </span>
                </div>
                
                <!-- Contenido de la proforma -->
                <div class="overflow-y-auto max-h-[70vh]">
                    <h3 class="text-2xl font-bold mb-4">Proforma {{ $selectedProforma['number'] }}</h3>
                    
                    <div class="mb-6 p-4 bg-white/10 rounded-lg border border-white/20">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-white/70">Fecha de creación:</span>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($selectedProforma['created_at'])->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-white/70">Estado:</span>
                                <p class="font-semibold">
                                    @if($selectedProforma['is_expired'])
                                        <span class="text-red-400">Expirada</span>
                                    @elseif($selectedProforma['is_ordered'])
                                        <span class="text-blue-400">Ordenada</span>
                                    @else
                                        <span class="text-green-400">Activa</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold mb-3">Configuraciones ({{ count($selectedProforma['items']) }} {{ count($selectedProforma['items']) == 1 ? 'ítem' : 'ítems' }})</h4>
                    
                    <div class="space-y-4">
                        @foreach($selectedProforma['items'] as $item)
                        @php
                            $colorTranslations = [
                                'Natural'                    => 'Natural',
                                'White'                      => 'Blanco',
                                'Black Anodized'             => 'Negro Anodizado',
                                'Woody'                      => 'Madera',
                                'Bronze'                     => 'Bronze',
                                'Silver'                     => 'Plateado',
                                'Gold'                       => 'Dorado',
                                'Transparent Glass'          => 'Vidrio Transparente',
                                'Tinted Glass'               => 'Vidrio Tintado',
                                'Frosted Glass'              => 'Vidrio Esmerilado',
                                'Reflective Blue Sky Glass'  => 'Azul Cielo Reflectivo',
                                'Reflective Gray Dark Glass' => 'Gris Oscuro Reflectivo',
                            ];
                            $params   = $item['parameters'] ?? [];
                            $width    = isset($params['width'])  ? (float)$params['width']  : null;
                            $height   = isset($params['height']) ? (float)$params['height'] : null;
                            $area     = ($width && $height)      ? $width * $height          : null;
                            $colorRaw = $params['color']      ?? null;
                            $glassRaw = $params['glassColor'] ?? null;
                            $colorLabel = $colorRaw ? ($colorTranslations[$colorRaw] ?? $colorRaw) : null;
                            $glassLabel = $glassRaw ? ($colorTranslations[$glassRaw] ?? $glassRaw) : null;
                            $notes    = $item['notes'] ?? ($params['notes'] ?? null);
                            $unitPrice = $item['quantity'] > 0 ? $item['price'] / $item['quantity'] : $item['price'];
                        @endphp
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg border border-white/20 overflow-hidden">
                            <!-- Header de item -->
                            <div class="flex justify-between items-center px-4 py-3 bg-white/5 border-b border-white/10">
                                <h5 class="font-bold text-base text-white">{{ $item['product_name'] }}</h5>
                                <div class="text-right">
                                    <p class="text-cyan-300 font-black text-lg leading-tight">${{ number_format($item['price'], 2) }}</p>
                                    <p class="text-white/50 text-xs">${{ number_format($unitPrice, 2) }} c/u</p>
                                </div>
                            </div>

                            <div class="p-4">
                                <!-- Cantiad destacada -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="px-2.5 py-1 bg-blue-500/20 border border-blue-400/30 rounded-full text-xs font-semibold text-blue-300">
                                        {{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}
                                    </span>
                                    @if($area)
                                    @php [$ai, $ad] = explode('.', number_format($area, 3, '.', '')); $ad = rtrim($ad, '0'); @endphp
                                    <span class="px-2.5 py-1 bg-slate-500/20 border border-slate-400/20 rounded-full text-xs text-slate-300">
                                        <span class="font-black">{{ $ai }}</span>@if($ad)<span class="text-white/60">,{{ $ad }}</span>@endif<span> m²</span>
                                    </span>
                                    @endif
                                </div>

                                <!-- Grid de especificaciones -->
                                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    @if($width && $height)
                                    <div class="flex flex-col">
                                        <span class="text-white/50 text-xs uppercase tracking-wide mb-0.5">Dimensiones</span>
                                        <span class="text-white font-semibold">
                                            @php
                                                [$wi, $wd] = explode('.', number_format($width,  3, '.', ''));
                                                [$hi, $hd] = explode('.', number_format($height, 3, '.', ''));
                                                $wd = rtrim($wd, '0');
                                                $hd = rtrim($hd, '0');
                                            @endphp
                                            <span class="text-base font-black">{{ $wi }}</span>@if($wd)<span class="text-xs text-white/60">,{{ $wd }}</span>@endif<span class="text-xs">m</span>
                                            <span class="text-white/50 mx-0.5">&times;</span>
                                            <span class="text-base font-black">{{ $hi }}</span>@if($hd)<span class="text-xs text-white/60">,{{ $hd }}</span>@endif<span class="text-xs">m</span>
                                        </span>
                                    </div>
                                    @endif

                                    @if($colorLabel)
                                    <div class="flex flex-col">
                                        <span class="text-white/50 text-xs uppercase tracking-wide mb-0.5">Color Aluminio</span>
                                        <span class="text-white font-semibold">{{ $colorLabel }}</span>
                                    </div>
                                    @endif

                                    @if($glassLabel)
                                    <div class="flex flex-col">
                                        <span class="text-white/50 text-xs uppercase tracking-wide mb-0.5">Color Vidrio</span>
                                        <span class="text-cyan-200 font-semibold">{{ $glassLabel }}</span>
                                    </div>
                                    @endif
                                </div>

                                @if($notes)
                                <div class="mt-3 pt-3 border-t border-white/10">
                                    <span class="text-white/50 text-xs uppercase tracking-wide">Notas</span>
                                    <p class="mt-0.5 text-xs text-white/70 italic leading-snug">{{ $notes }}</p>
                                </div>
                                @endif
                            </div>

                            @if(!$selectedProforma['is_expired'] && !$selectedProforma['is_ordered'])
                            <div class="px-4 pb-3 flex justify-end">
                                <div class="relative group">
                                    <button wire:click="deleteProformaItem({{ $item['id'] }})"
                                            class="px-3 py-1.5 text-xs font-medium border border-red-600/40 rounded-lg text-white bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 hover:scale-[1.02] hover:shadow-xl cursor-pointer">
                                        Eliminar configuración
                                    </button>
                                    <span class="absolute bottom-full right-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                        Eliminar esta configuración de la proforma
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-600/30 to-cyan-600/30 rounded-lg border border-blue-400/30">
                        <div class="flex justify-between items-center">
                            <span class="text-white font-semibold text-lg">Total de la Proforma:</span>
                            <span class="text-3xl font-bold text-white">${{ number_format($selectedProforma['total_price'], 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-6 pt-6 border-t border-white/20 flex justify-end gap-3">
                    <div class="relative group">
                        <button wire:click="downloadProformaPdf({{ $selectedProformaId }})" class="px-4 py-2 text-sm font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:scale-[1.02] hover:shadow-xl cursor-pointer">
                            Descargar PDF
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Descargar documento PDF con todos los detalles
                        </span>
                    </div>
                    @if(!$selectedProforma['is_expired'] && !$selectedProforma['is_ordered'])
                    <div x-data="{ showConfirmOrder: false }" class="relative group">
                        <button @click="showConfirmOrder = true"
                                class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold">
                            Ordenar Proforma
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Convertir esta proforma en orden de compra
                        </span>
                        <!-- Modal de confirmación para ordenar proforma -->
                        <div x-show="showConfirmOrder" x-transition.opacity.duration.300ms class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button @click="showConfirmOrder = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-amber-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2">¿Confirmar orden?</h3>
                                    <p class="text-sm text-gray-700 mb-4 text-center">
                                        @if(!empty($selectedProforma) && isset($selectedProforma['number']))
                                            ¿Estás seguro de que deseas crear una orden con la proforma <span class="font-bold text-amber-700">#{{ $selectedProforma['number'] }}</span>?
                                        @else
                                            ¿Estás seguro de que deseas crear una orden con esta proforma?
                                        @endif
                                    </p>
                                    <div class="flex gap-3 mt-2">
                                        <button @click="showConfirmOrder = false"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button @click="$wire.orderProforma({{ $selectedProformaId }}); showConfirmOrder = false;"
                                                class="px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700 transition">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal secundario para ver orden individual -->
    @if($showOrderModal && $selectedOrder)
    <div class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro más intenso -->
            <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeOrderModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative z-10 p-6 md:p-8 text-white" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Botón cerrar -->
                <button wire:click="closeOrderModal" class="absolute top-3 right-3 text-white/60 hover:text-white text-2xl font-bold z-10 cursor-pointer">&times;</button>
                
                <!-- Header -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-2">Detalles de la Orden</h3>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-white/60">Número Orden:</span>
                            <span class="font-bold text-emerald-400">{{ $selectedOrder['number'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-white/60">Proforma:</span>
                            <span class="font-semibold text-cyan-400">#{{ $selectedOrder['proforma_number'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-white/60">Estado:</span>
                            @if($selectedOrder['status'] === 'pending')
                                <span class="px-2 py-1 bg-yellow-500/20 border border-yellow-500/30 text-yellow-300 rounded-full text-xs font-semibold">En Aprobación</span>
                            @elseif($selectedOrder['status'] === 'approved')
                                <span class="px-2 py-1 bg-blue-500/20 border border-blue-500/30 text-blue-300 rounded-full text-xs font-semibold">Pendiente Pago</span>
                            @elseif($selectedOrder['status'] === 'paid')
                                <span class="px-2 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-full text-xs font-semibold">Pagada</span>
                            @elseif($selectedOrder['status'] === 'in_production')
                                <span class="px-2 py-1 bg-purple-500/20 border border-purple-500/30 text-purple-300 rounded-full text-xs font-semibold">En Producción</span>
                            @elseif($selectedOrder['status'] === 'completed')
                                <span class="px-2 py-1 bg-green-500/20 border border-green-500/30 text-green-300 rounded-full text-xs font-semibold">Completada</span>
                            @elseif($selectedOrder['status'] === 'cancelled')
                                <span class="px-2 py-1 bg-red-500/20 border border-red-500/30 text-red-300 rounded-full text-xs font-semibold">Rechazada</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido scrollable -->
                <div class="overflow-y-auto flex-1 pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);">
                    <!-- Total de la orden -->
                    <div class="bg-gradient-to-r from-emerald-600/30 to-green-600/30 rounded-xl p-4 mb-6 border border-emerald-500/30">
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm font-medium">Total de la Orden:</span>
                            <span class="text-2xl font-bold text-emerald-300">${{ number_format($selectedOrder['total_price'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Productos/Ítems -->
                    <div class="mb-6">
                        <h4 class="text-lg font-bold text-white mb-4">Productos Incluidos ({{ count($selectedOrder['items']) }} {{ count($selectedOrder['items']) == 1 ? 'ítem' : 'ítems' }})</h4>
                        <div class="space-y-3">
                            @foreach($selectedOrder['items'] as $item)
                            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4 hover:bg-white/10 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        @if($item['product'] && $item['product']->image)
                                        <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product_name'] }}" class="w-12 h-12 object-cover rounded-lg border border-white/20">
                                        @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-slate-600 to-slate-700 rounded-lg flex items-center justify-center border border-white/20">
                                            <svg class="w-6 h-6 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        @endif
                                        <div>
                                            <h5 class="font-semibold text-white">{{ $item['product_name'] }}</h5>
                                            <p class="text-xs text-white/60">Cantidad: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-green-400">${{ number_format($item['price'], 2) }}</p>
                                        <p class="text-xs text-white/60">${{ number_format($item['price'] / max(1, $item['quantity']), 2) }} c/u</p>
                                    </div>
                                </div>
                                
                                <!-- Dimensiones y colores -->
                                @if(isset($item['parameters']) && !empty($item['parameters']))
                                @php
                                    $orderColorTranslations = [
                                        'Natural'                    => 'Natural',
                                        'White'                      => 'Blanco',
                                        'Black Anodized'             => 'Negro Anodizado',
                                        'Woody'                      => 'Madera',
                                        'Bronze'                     => 'Bronze',
                                        'Silver'                     => 'Plateado',
                                        'Gold'                       => 'Dorado',
                                        'Transparent Glass'          => 'Vidrio Transparente',
                                        'Tinted Glass'               => 'Vidrio Tintado',
                                        'Frosted Glass'              => 'Vidrio Esmerilado',
                                        'Reflective Blue Sky Glass'  => 'Azul Cielo Reflectivo',
                                        'Reflective Gray Dark Glass' => 'Gris Oscuro Reflectivo',
                                    ];
                                    $oColorRaw  = $item['parameters']['color']      ?? null;
                                    $oGlassRaw  = $item['parameters']['glassColor'] ?? null;
                                    $oColorLabel = $oColorRaw ? ($orderColorTranslations[$oColorRaw] ?? $oColorRaw) : null;
                                    $oGlassLabel = $oGlassRaw ? ($orderColorTranslations[$oGlassRaw] ?? $oGlassRaw) : null;
                                @endphp
                                <div class="mt-3 pt-3 border-t border-white/10">
                                    <p class="text-xs font-semibold text-white/80 mb-2">Especificaciones:</p>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        @if(isset($item['parameters']['height']))
                                        <div class="flex justify-between items-baseline">
                                            <span class="text-white/60">Alto:</span>
                                            @php [$oi, $od] = explode('.', number_format($item['parameters']['height'], 3, '.', '')); $od = rtrim($od, '0'); @endphp
                                            <span class="text-white font-medium">
                                                <span class="text-sm font-black">{{ $oi }}</span>@if($od)<span class="text-[10px] text-white/60">,{{ $od }}</span>@endif<span class="text-[10px]">m</span>
                                            </span>
                                        </div>
                                        @endif
                                        @if(isset($item['parameters']['width']))
                                        <div class="flex justify-between items-baseline">
                                            <span class="text-white/60">Ancho:</span>
                                            @php [$oi, $od] = explode('.', number_format($item['parameters']['width'], 3, '.', '')); $od = rtrim($od, '0'); @endphp
                                            <span class="text-white font-medium">
                                                <span class="text-sm font-black">{{ $oi }}</span>@if($od)<span class="text-[10px] text-white/60">,{{ $od }}</span>@endif<span class="text-[10px]">m</span>
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                    @if($oColorLabel || $oGlassLabel)
                                    <div class="flex gap-4 mt-2">
                                        @if($oColorLabel)
                                        <div class="flex flex-col">
                                            <span class="text-white/50 text-[10px] uppercase tracking-wide">Color Aluminio</span>
                                            <span class="text-white font-semibold text-xs">{{ $oColorLabel }}</span>
                                        </div>
                                        @endif
                                        @if($oGlassLabel)
                                        <div class="flex flex-col">
                                            <span class="text-white/50 text-[10px] uppercase tracking-wide">Color Vidrio</span>
                                            <span class="text-cyan-200 font-semibold text-xs">{{ $oGlassLabel }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @endif
                                
                                <!-- Notas -->
                                @if(isset($item['notes']) && !empty($item['notes']))
                                <div class="mt-3 pt-3 border-t border-white/10">
                                    <p class="text-xs font-semibold text-white/80 mb-1">Notas:</p>
                                    <p class="text-xs text-white/70 italic">{{ $item['notes'] }}</p>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4">
                        <h4 class="text-sm font-bold text-white mb-3">Información Adicional</h4>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-white/60">Fecha de creación:</span>
                                <span class="text-white font-medium">{{ \Carbon\Carbon::parse($selectedOrder['created_at'])->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($selectedOrder['product_created_at'])
                            <div class="flex justify-between">
                                <span class="text-white/60">Inicio de producción:</span>
                                <span class="text-white font-medium">{{ \Carbon\Carbon::parse($selectedOrder['product_created_at'])->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                            @if($selectedOrder['estimated_finish_at'])
                            <div class="flex justify-between">
                                <span class="text-white/60">Entrega estimada:</span>
                                <span class="text-white font-medium">{{ \Carbon\Carbon::parse($selectedOrder['estimated_finish_at'])->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sección de pago (solo si está en estado Pendiente Pago) -->
                @if($selectedOrder['status'] === 'approved')
                <div class="mt-6 pt-4 border-t border-white/20">
                    <h4 class="text-lg font-bold text-white mb-4">Comprobante de Pago</h4>
                    
                    @if($selectedOrder['payment_proof'] && !$showUploadForm)
                        <!-- Mostrar comprobante existente -->
                        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-lg p-4">
                            <p class="text-sm text-white/80 mb-3">Comprobante subido - En espera de aprobación</p>
                            <div class="flex items-center gap-4">
                                <a href="{{ asset('storage/' . $selectedOrder['payment_proof']) }}" target="_blank" class="group relative">
                                    <img src="{{ asset('storage/' . $selectedOrder['payment_proof']) }}" alt="Comprobante de pago" class="w-32 h-32 object-cover rounded-lg border-2 border-white/20 hover:border-blue-400 transition-colors">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </a>
                                <div class="flex-1">
                                    <p class="text-xs text-white/60 mb-2">Haz clic en la imagen para verla en tamaño completo</p>
                                    <button wire:click="replacePaymentProof" 
                                            type="button"
                                            class="px-4 py-2 text-xs font-medium border border-amber-600/40 rounded-lg text-white bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 transition-all duration-200 cursor-pointer">
                                        Subir nuevo comprobante
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Formulario para subir comprobante -->
                        <div class="bg-blue-500/10 backdrop-blur-sm border border-blue-500/30 rounded-lg p-4">
                            <p class="text-sm text-blue-300 mb-4">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tu orden ha sido aprobada. Por favor sube el comprobante de pago para continuar con el proceso.
                            </p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-white/80 mb-2">
                                        Selecciona la imagen del comprobante
                                    </label>
                                    <input type="file" 
                                           wire:model.live="paymentProof" 
                                           accept="image/jpeg,image/jpg,image/png,image/webp"
                                           class="block w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-white/5 border border-white/20 rounded-lg @error('paymentProof') border-red-500 @enderror">
                                    
                                    @error('paymentProof')
                                        <div class="mt-3 bg-red-500/20 backdrop-blur-sm border border-red-500/40 rounded-lg p-3 flex items-start gap-2 animate-pulse">
                                            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-red-300 text-sm font-semibold mb-1">Error en el archivo</p>
                                                <p class="text-red-200 text-xs">{{ $message }}</p>
                                            </div>
                                        </div>
                                    @enderror
                                    
                                    @if(!$errors->has('paymentProof'))
                                        <p class="text-xs text-white/50 mt-2">
                                            <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Formatos: JPG, PNG, WEBP • Tamaño máximo: 5MB • Mínimo: 100x100px
                                        </p>
                                    @endif
                                    
                                    <div wire:loading wire:target="paymentProof" class="mt-3 bg-blue-500/20 backdrop-blur-sm border border-blue-500/40 rounded-lg p-3 flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm text-blue-300">Validando archivo...</span>
                                    </div>
                                </div>
                                
                                @if($paymentProof)
                                    <div class="flex items-center gap-3 bg-white/5 p-3 rounded-lg">
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm text-white/80">Archivo seleccionado</span>
                                        <button wire:click="uploadPaymentProof" 
                                                wire:loading.attr="disabled"
                                                wire:target="uploadPaymentProof"
                                                type="button"
                                                class="ml-auto px-4 py-2 text-xs font-medium bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                            <span wire:loading.remove wire:target="uploadPaymentProof">Subir Comprobante</span>
                                            <span wire:loading wire:target="uploadPaymentProof">
                                                <svg class="animate-spin h-4 w-4 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Subiendo...
                                            </span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Footer -->
                <div class="mt-6 pt-4 border-t border-white/20 flex justify-between items-center">
                    <div class="relative group">
                        <button wire:click="downloadProformaPdf({{ $selectedOrder['proforma_id'] }})" 
                                class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.02] border border-green-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 cursor-pointer">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Descargar Proforma PDF
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Descargar la proforma original #{{ $selectedOrder['proforma_number'] }}
                        </span>
                    </div>
                    <button wire:click="closeOrderModal" class="px-6 py-2.5 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.02] cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
