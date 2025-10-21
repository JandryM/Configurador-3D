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
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Fecha</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Monto</th>
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
                                    {{ \Carbon\Carbon::parse($proforma['date'])->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-medium text-slate-800">
                                    ${{ number_format($proforma['amount'], 0) }}
                                </span>
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
                            <td colspan="5" class="text-center py-8 text-slate-500">
                                No hay proformas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showProformaModal && $selectedProforma)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 relative mx-auto my-auto max-h-[90vh] flex flex-col">
                <button type="button" wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-center">Proforma de Producto</h2>
                <div class="overflow-y-auto" style="max-height:65vh;">
                    @if(!$selectedProforma['product'])
                        <div class="text-center text-gray-400 py-8">Producto eliminado o no disponible.</div>
                    @else
                        @include('proforma', [
                            'product' => $selectedProforma['product'],
                            'parameters' => $selectedProforma['parameters'],
                            'materialCosts' => $selectedProforma['materialCosts'],
                            'calculatedPrice' => $selectedProforma['calculatedPrice'],
                            'notes' => $selectedProforma['notes'],
                            'directCost' => $selectedProforma['directCost'],
                            'indirectCost' => $selectedProforma['indirectCost'],
                            'user' => $selectedProforma['user'],
                            'showDownloadButton' => false
                        ])
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
