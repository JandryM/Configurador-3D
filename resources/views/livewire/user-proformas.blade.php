<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Historial de Proformas Guardadas</h2>
    @if(empty($proformas))
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">No tienes proformas guardadas.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Producto</th>
                        <th class="px-4 py-2 border-b">Fecha</th>
                        <th class="px-4 py-2 border-b">Precio</th>
                        <th class="px-4 py-2 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proformas as $proforma)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $proforma['product']?->name ?? 'Producto eliminado' }}</td>
                            <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 border-b font-semibold text-green-700">${{ number_format($proforma['calculatedPrice'], 2) }}</td>
                            <td class="px-4 py-2 border-b flex gap-2">
                                <button wire:click="showProforma({{ $proforma['id'] }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Ver Proforma</button>
                                <button wire:click="downloadProformaPdf({{ $proforma['id'] }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Descargar PDF</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($showProformaModal && $selectedProforma)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 relative">
                <button type="button" wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-center">Proforma de Producto</h2>
                <div class="overflow-y-auto max-h-[70vh]">
                    @include('proforma', [
                        'product' => $selectedProforma['product'],
                        'parameters' => $selectedProforma['parameters'],
                        'materialCosts' => $selectedProforma['materialCosts'],
                        'calculatedPrice' => $selectedProforma['calculatedPrice'],
                        'notes' => $selectedProforma['notes'],
                        'directCost' => $selectedProforma['directCost'],
                        'indirectCost' => $selectedProforma['indirectCost'],
                        'user' => auth()->user(),
                        'showDownloadButton' => false
                    ])
                </div>
            </div>
        </div>
    @endif
</div>
