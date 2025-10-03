<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Materiales</h1>
            <p class="text-gray-600">Administra los materiales para el costeo de productos</p>
        </div>
        <button wire:click="createMaterial" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Material
        </button>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input wire:model.live="search" type="text" placeholder="Buscar por nombre o descripción..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="md:w-48">
                <select wire:model.live="filterByType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los tipos</option>
                    <option value="by_piece">Por piezas</option>
                    <option value="by_unit">Por unidades</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabla de materiales -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precios</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio/Unidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                    @if($material->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($material->has_dimensions)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Por dimensiones
                                    </span>
                                @elseif($material->is_by_piece)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Por pieza
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Por unidad
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->unit_measure }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($material->has_dimensions)
                                    <div>
                                        <strong>${{ number_format($material->unit_price, 2) }}/m²</strong>
                                        <div class="text-xs text-gray-500">
                                            {{ $material->width }}m × {{ $material->height }}m = {{ number_format($material->calculated_area, 3) }}m²
                                        </div>
                                    </div>
                                @elseif($material->is_by_piece)
                                    <div>
                                        <strong>${{ number_format($material->piece_price, 2) }}</strong>
                                        <div class="text-xs text-gray-500">por {{ $material->piece_size }} {{ $material->unit_measure }}</div>
                                    </div>
                                @else
                                    <strong>${{ number_format($material->unit_price, 2) }}</strong>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${{ number_format($material->price_per_unit, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editMaterial({{ $material->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Editar
                                </button>
                                <button wire:click="deleteMaterial({{ $material->id }})" 
                                        wire:confirm="¿Estás seguro de eliminar este material?"
                                        class="text-red-600 hover:text-red-900">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m10-8v2m0 4v2"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No hay materiales</p>
                                    <p class="text-sm">Comienza agregando un nuevo material.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $materials->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $editingMaterial ? 'Editar Material' : 'Nuevo Material' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <livewire:admin.material-form :material="$editingMaterial" wire:key="material-form-{{ $editingMaterial?->id ?? 'new' }}" />
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    $wire.on('materialSaved', () => {
        // El modal se cerrará automáticamente
    });
</script>
@endscript
