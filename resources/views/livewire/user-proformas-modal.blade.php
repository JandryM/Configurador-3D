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
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Producto</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Fecha</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Precio</th>
                                        <th class="px-4 py-3 border-b dark:border-gray-600 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($proformas as $proforma)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200">
                                                {{ $proforma['product']?->name ?? 'Producto eliminado' }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600 text-sm font-semibold text-green-700 dark:text-green-400">
                                                ${{ number_format($proforma['calculatedPrice'], 2) }}
                                            </td>
                                            <td class="px-4 py-3 border-b dark:border-gray-600">
                                                <div class="flex gap-2">
                                                    <button wire:click="showProforma({{ $proforma['id'] }})" 
                                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded transition-colors">
                                                        Ver
                                                    </button>
                                                    <button wire:click="downloadProformaPdf({{ $proforma['id'] }})" 
                                                            class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded transition-colors">
                                                        PDF
                                                    </button>
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
                    @include('livewire.proformas.proforma', [
                        'product' => $selectedProforma['product'],
                        'parameters' => $selectedProforma['parameters'],
                        'materialCosts' => $selectedProforma['materialCosts'],
                        'calculatedPrice' => $selectedProforma['calculatedPrice'],
                        'notes' => $selectedProforma['notes'],
                        'directCost' => $selectedProforma['directCost'],
                        'indirectCost' => $selectedProforma['indirectCost'],
                        'user' => auth()->user(),
                        'isPdf' => false
                    ])
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-6 pt-6 border-t border-white/20 flex justify-end gap-3">
                    <button wire:click="closeProformaModal" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        Cerrar
                    </button>
                    <button wire:click="downloadProformaPdf({{ $selectedProforma['id'] }})" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
