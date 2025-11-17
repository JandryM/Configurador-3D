<div>
    <div class="glass-card rounded-2xl shadow-xl">
        <div class="p-6 border-b border-slate-200/50">
            <h2 class="text-xl font-bold text-slate-800">Lista de Proformas</h2>
            <p class="text-slate-600 mt-1">Todas las cotizaciones y propuestas comerciales</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Número</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Cliente</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Fecha Expiración</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Cantidad de Productos</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Total</th>
                            <th class="text-center py-3 px-4 font-medium text-slate-700">¿Ordenada?</th>
                            <th class="text-center py-3 px-4 font-medium text-slate-700">¿Expirada?</th>
                            <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proformas as $proforma)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-violet-600 rounded-lg flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $proforma['number'] }}</p>
                                            <p class="text-sm text-slate-500">Proforma</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $proforma['client'] }}</p>
                                        <p class="text-sm text-slate-500">Cliente</p>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-slate-700">
                                        {{ $proforma['expiration_date'] ? \Carbon\Carbon::parse($proforma['expiration_date'])->format('d/m/Y H:i') : '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $proforma['total_quantity'] }} {{ $proforma['total_quantity'] == 1 ? 'producto' : 'productos' }}
                                        </span>
                                        <span class="text-xs text-slate-500">({{ $proforma['items_count'] }} {{ $proforma['items_count'] == 1 ? 'config' : 'configs' }})</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-medium text-slate-800">
                                        ${{ number_format($proforma['total_price'], 2) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @php
                                        $hasOrder = DB::table('orders')->where('proforma_id', $proforma['id'])->exists();
                                    @endphp
                                    @if($hasOrder)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sí
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($proforma['is_expired'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sí
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <button wire:click="showProforma({{ $proforma['id'] }})" class="text-blue-600 hover:text-blue-800 transition-colors" title="Ver proforma">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="downloadProformaPdf({{ $proforma['id'] }})" class="text-purple-600 hover:text-purple-800 transition-colors" title="Descargar PDF">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-slate-500">
                                    No hay proformas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showProformaModal && $selectedProforma)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                <!-- Panel del modal -->
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative" style="max-height: 90vh; display: flex; flex-direction: column;">
                    <!-- Header -->
                    <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Detalles de Proforma</h2>
                                <p class="text-sm text-gray-600">Información completa de la cotización</p>
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
                        @if(empty($selectedProforma['items']) || count($selectedProforma['items']) == 0)
                            <div class="text-center text-gray-400 py-8">No hay ítems en esta proforma.</div>
                        @else
                            @include('livewire.proformas.proforma-admin', [
                                'items' => $selectedProforma['items'],
                                'user' => $selectedProforma['user'],
                                'total_price' => $selectedProforma['total_price'],
                                'number' => $selectedProforma['number'],
                                'expiration_date' => $selectedProforma['expiration_date'],
                                'is_expired' => $selectedProforma['is_expired'],
                                'showDownloadButton' => false
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
