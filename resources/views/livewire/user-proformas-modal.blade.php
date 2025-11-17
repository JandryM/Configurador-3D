<div>
    <!-- Modal principal de listado de proformas -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative z-10" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Header -->
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 rounded-t-2xl flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Mis Proformas</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Historial de cotizaciones guardadas</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeModal" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Contenido -->
                <div class="overflow-y-auto px-6 py-4" style="flex: 1;">
                    @if(empty($proformas))
                        <div class="bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300 p-4 rounded-lg text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p>No tienes proformas guardadas.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Número</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Productos</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Fecha</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Estado</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Total</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proformas as $proforma)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $proforma['number'] }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $proforma['total_quantity'] }} {{ $proforma['total_quantity'] == 1 ? 'producto' : 'productos' }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm">
                                                @if($proforma['is_expired'])
                                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-semibold">
                                                        Expirada
                                                    </span>
                                                @elseif($proforma['is_ordered'])
                                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-semibold">
                                                        Ordenada
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-semibold">
                                                        Activa
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm font-semibold text-green-700 dark:text-green-400">
                                                ${{ number_format($proforma['total_price'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600">
                                                <div class="flex gap-2">
                                                    <button wire:click="showProforma({{ $proforma['id'] }})" 
                                                            class="px-4 py-2 text-xs font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-700 hover:from-blue-700 hover:to-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                                        Ver
                                                    </button>
                                                    <button wire:click="downloadProformaPdf({{ $proforma['id'] }})" 
                                                            class="px-4 py-2 text-xs font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                                        PDF
                                                    </button>
                                                    @if(!$proforma['is_expired'] && !$proforma['is_ordered'])
                                                    <button wire:click="orderProforma({{ $proforma['id'] }})" 
                                                            wire:confirm="¿Estás seguro de que deseas crear una orden con esta proforma?"
                                                            class="px-4 py-2 text-xs font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold">
                                                        🚀 Ordenar Proforma
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                <button wire:click="closeProformaModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10">&times;</button>
                
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
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-bold text-lg">{{ $item['product_name'] }}</h5>
                                <span class="text-cyan-300 font-bold text-lg">${{ number_format($item['price'], 2) }}</span>
                            </div>
                            
                            @if(!empty($item['parameters']))
                            <div class="grid grid-cols-2 gap-3 text-sm text-white/80 mt-3">
                                @if(isset($item['parameters']['width']))
                                <div>
                                    <span class="text-white/60">Dimensiones:</span>
                                    <p>{{ number_format($item['parameters']['width'], 2) }}m × {{ number_format($item['parameters']['height'], 2) }}m</p>
                                </div>
                                @endif
                                @if(isset($item['parameters']['color']))
                                <div>
                                    <span class="text-white/60">Color:</span>
                                    <p>{{ $item['parameters']['color'] }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="text-white/60">Cantidad:</span>
                                    <p class="font-semibold">{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</p>
                                </div>
                                <div>
                                    <span class="text-white/60">Precio unitario:</span>
                                    <p class="font-semibold">${{ number_format($item['price'] / $item['quantity'], 2) }}</p>
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
                    <button wire:click="downloadProformaPdf({{ $selectedProformaId }})" class="px-4 py-2 text-sm font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                        📄 Descargar PDF
                    </button>
                    @if(!$selectedProforma['is_expired'] && !$selectedProforma['is_ordered'])
                    <button wire:click="orderProforma({{ $selectedProformaId }})" 
                            wire:confirm="¿Estás seguro de que deseas crear una orden con esta proforma?"
                            class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold">
                        🚀 Ordenar Proforma
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
