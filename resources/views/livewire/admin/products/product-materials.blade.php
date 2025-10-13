<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Materiales del Producto</h3>
            <p class="text-sm text-gray-600">Configura los materiales necesarios y sus cantidades</p>
        </div>
        @if($product)
            <button wire:click="addMaterial" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Agregar Material
            </button>
        @endif
    </div>

    <!-- Mensajes -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Lista de materiales -->
    @if($materials && $materials->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fórmula</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($material->has_dimensions)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                                Por dimensiones
                                            </span>
                                        @elseif($material->is_by_piece)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                                Por pieza
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                Por unidad
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <strong>Necesaria:</strong> {{ number_format($material->pivot?->quantity ?? 0, 3) }} {{ $material->unit_measure }}
                                    @if(($material->pivot?->waste_percentage ?? 0) > 0)
                                        <div class="text-xs text-gray-500">
                                            + {{ $material->pivot->waste_percentage }}% desperdicio = {{ number_format(($material->pivot->quantity ?? 0) * (1 + (($material->pivot->waste_percentage ?? 0) / 100)), 3) }} {{ $material->unit_measure }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    ${{ number_format($material->pivot?->calculated_cost ?? 0, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    ${{ number_format($material->price_per_unit, 4) }}/{{ $material->unit_measure }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($material->pivot?->calculation_formula)
                                    <div class="max-w-xs truncate" title="{{ $material->pivot->calculation_formula }}">
                                        {{ $material->pivot->calculation_formula }}
                                    </div>
                                @else
                                    <span class="text-gray-400">Sin fórmula</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editMaterial({{ $material->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Editar
                                </button>
                                <button wire:click="removeMaterial({{ $material->id }})" 
                                        wire:confirm="¿Estás seguro de eliminar este material?"
                                        class="text-red-600 hover:text-red-900">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Total de costos -->
            <div class="bg-gray-50 px-6 py-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-900">Costo Total de Materiales:</span>
                    <span class="text-lg font-bold text-green-600">${{ number_format($this->totalMaterialsCost, 2) }}</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m10-8v2m0 4v2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay materiales</h3>
            <p class="mt-1 text-sm text-gray-500">Comienza agregando materiales a este producto.</p>
            @if($product)
                <div class="mt-6">
                    <button wire:click="addMaterial" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Agregar Primer Material
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- Modal para agregar/editar material -->
    @if($showAddForm)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $editingMaterialId ? 'Editar Material' : 'Agregar Material' }}
                    </h3>
                    <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="saveMaterial" class="p-6 space-y-6">
                    <!-- Selección de material -->
                    <div>
                        <label for="material" class="block text-sm font-medium text-gray-700 mb-2">
                            Material *
                        </label>
                        <select wire:model.live="selectedMaterialId" id="material"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('selectedMaterialId') border-red-500 @enderror">
                            @if($availableMaterials->count() > 0)
                                <option value="">Selecciona un material...</option>
                                @foreach($availableMaterials as $material)
                                    <option value="{{ $material->id }}">
                                        {{ $material->name }} 
                                        ({{ $material->unit_measure }} - ${{ number_format($material->price_per_unit, 4) }})
                                    </option>
                                @endforeach
                            @else
                                <option value="">No hay materiales disponibles para la categoría "{{ $product->category ? $product->category->name : 'Sin categoría' }}"</option>
                            @endif
                        </select>
                        @error('selectedMaterialId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if($selectedMaterialId)
                        @php $selectedMaterial = $availableMaterials->find($selectedMaterialId); @endphp
                        
                        <!-- Información del material seleccionado -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Información del Material</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Tipo:</span>
                                    @if($selectedMaterial->has_dimensions)
                                        <span class="ml-2 text-purple-600">Por dimensiones</span>
                                    @elseif($selectedMaterial->is_by_piece)
                                        <span class="ml-2 text-blue-600">Por pieza</span>
                                    @else
                                        <span class="ml-2 text-green-600">Por unidad</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-600">Precio unitario:</span>
                                    <span class="ml-2 font-medium">${{ number_format($selectedMaterial->price_per_unit, 4) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cantidad -->
                        <div class="max-w-md">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cantidad Necesaria *
                                </label>
                                <div class="relative">
                                    <input wire:model.live="quantity" type="number" step="0.001" min="0.001" id="quantity"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                                           placeholder="3.500">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-sm">{{ $selectedMaterial->unit_measure }}</span>
                                    </div>
                                </div>
                                @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Solo especifica cuánto necesitas. El sistema calculará el resto automáticamente.
                                </p>
                            </div>
                        </div>

                        <!-- Desperdicio -->
                        <div>
                            <label for="waste_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                Porcentaje de Desperdicio
                            </label>
                            <div class="relative max-w-sm">
                                <input wire:model.live="waste_percentage" type="number" step="0.1" min="0" max="100" id="waste_percentage"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('waste_percentage') border-red-500 @enderror"
                                       placeholder="5.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            @error('waste_percentage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Notas opcionales -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notas Adicionales (Opcional)
                            </label>
                            <textarea wire:model="notes" id="notes" rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                      placeholder="Observaciones especiales sobre este material..."></textarea>
                            @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Vista previa del costo -->
                        @if($quantity && $selectedMaterialId)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">Vista Previa del Costo</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Cantidad necesaria:</span>
                                        <span class="font-medium">{{ number_format($quantity, 3) }} {{ $selectedMaterial->unit_measure }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Desperdicio ({{ $waste_percentage }}%):</span>
                                        <span class="font-medium">{{ number_format($quantity * ($waste_percentage / 100), 3) }} {{ $selectedMaterial->unit_measure }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-blue-200 pt-2">
                                        <span class="text-blue-700 font-medium">Total a usar:</span>
                                        <span class="font-medium">{{ number_format($quantity * (1 + ($waste_percentage / 100)), 3) }} {{ $selectedMaterial->unit_measure }}</span>
                                    </div>
                                    <div class="flex justify-between bg-blue-100 -mx-4 px-4 py-2 rounded">
                                        <span class="text-blue-900 font-medium">Costo total:</span>
                                        <span class="font-bold text-green-600">${{ number_format($this->calculateCost(), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <button type="button" wire:click="resetForm" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                            {{ $editingMaterialId ? 'Actualizar' : 'Agregar' }} Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
