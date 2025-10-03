<form wire:submit="save" class="space-y-6">
    <!-- Información básica -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nombre del Material *
            </label>
            <input wire:model="name" type="text" id="name" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                   placeholder="Ej: Riel de Aluminio">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción
            </label>
            <textarea wire:model="description" id="description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                      placeholder="Descripción detallada del material..."></textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="unit_measure" class="block text-sm font-medium text-gray-700 mb-2">
                Unidad de Medida * 
                @if($unit_measure)
                    <span class="text-xs text-green-600">(Auto-seleccionada)</span>
                @endif
            </label>
            <select wire:model.live="unit_measure" id="unit_measure"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit_measure') border-red-500 @enderror">
                @if(empty($unit_measure))
                    <option value="">Selecciona una unidad...</option>
                @endif
                @foreach($this->availableUnits as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('unit_measure') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

                <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Material *
            </label>
            <div class="space-y-3">
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="units"
                           name="material_type" 
                           class="mr-3">
                    <div>
                        <span class="text-sm font-medium">Por unidades individuales</span>
                        <p class="text-xs text-gray-500">Tornillos, piezas pequeñas, componentes (unidades)</p>
                    </div>
                </label>
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="pieces"
                           name="material_type" 
                           class="mr-3">
                    <div>
                        <span class="text-sm font-medium">Por piezas completas</span>
                        <p class="text-xs text-gray-500">Rieles, perfiles, barras con longitud fija (metros lineales)</p>
                    </div>
                </label>
                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="dimensions"
                           name="material_type" 
                           class="mr-3">
                    <div>
                        <span class="text-sm font-medium">Por dimensiones (ancho × alto)</span>
                        <p class="text-xs text-gray-500">Vidrios, láminas, placas rectangulares (metros cuadrados)</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Configuración de precios -->
    <div class="border-t pt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración de Precios</h3>
        
        @if($is_by_piece)
            <!-- Materiales por pieza -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <h4 class="font-medium text-blue-900 mb-2">Material por Piezas Completas</h4>
                <p class="text-sm text-blue-700 mb-4">
                    El precio se calculará automáticamente por unidad dividiendo el precio de la pieza entre su tamaño.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="piece_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Tamaño de Pieza Completa *
                        </label>
                        <div class="relative">
                            <input wire:model.live="piece_size" type="number" step="0.001" min="0.001" id="piece_size"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('piece_size') border-red-500 @enderror"
                                   placeholder="6.400">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">{{ $unit_measure ?: 'unidades' }}</span>
                            </div>
                        </div>
                        @error('piece_size') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($piece_size)
                            <p class="mt-1 text-xs text-blue-600">
                                Se guardará como: {{ number_format($piece_size, 3, '.', '') }} {{ $unit_measure }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label for="piece_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Precio por Pieza Completa *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">$</span>
                            </div>
                            <input wire:model.live="piece_price" type="number" step="0.01" min="0" id="piece_price"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('piece_price') border-red-500 @enderror"
                                   placeholder="45.00">
                        </div>
                        @error('piece_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($piece_size > 0 && $piece_price > 0)
                    <div class="mt-4 p-3 bg-white border border-blue-200 rounded">
                        <p class="text-sm font-medium text-gray-900">
                            Precio calculado por {{ $unit_measure ?: 'unidad' }}: 
                            <span class="text-green-600">${{ number_format($this->pricePerUnit, 4) }}</span>
                        </p>
                    </div>
                @endif
            </div>
        @elseif($has_dimensions)
            <!-- Materiales por dimensiones -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                <h4 class="font-medium text-purple-900 mb-2">Material por Dimensiones (Ancho × Alto)</h4>
                <p class="text-sm text-purple-700 mb-4">
                    Define las dimensiones exactas del material y el precio por m². El área se calculará automáticamente.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="width" class="block text-sm font-medium text-gray-700 mb-2">
                            Ancho *
                        </label>
                        <div class="relative">
                            <input wire:model.live="width" type="number" step="0.001" min="0.001" id="width"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('width') border-red-500 @enderror"
                                   placeholder="2.140">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">m</span>
                            </div>
                        </div>
                        @error('width') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                            Alto *
                        </label>
                        <div class="relative">
                            <input wire:model.live="height" type="number" step="0.001" min="0.001" id="height"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('height') border-red-500 @enderror"
                                   placeholder="3.300">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">m</span>
                            </div>
                        </div>
                        @error('height') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Configuración de Precio *
                        </label>
                        
                        <!-- Selector de tipo de precio -->
                        <div class="mb-3 space-y-2">
                            <label class="flex items-center">
                                <input wire:model.live="price_type" type="radio" value="per_unit" name="price_type" class="mr-2">
                                <span class="text-sm">Precio por m²</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.live="price_type" type="radio" value="total_piece" name="price_type" class="mr-2">
                                <span class="text-sm">Precio total de la pieza</span>
                            </label>
                        </div>

                        <!-- Campo de precio dinámico -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">$</span>
                            </div>
                            <input wire:model.live="unit_price" type="number" step="0.01" min="0" id="unit_price_dimensions"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit_price') border-red-500 @enderror"
                                   placeholder="{{ $price_type === 'per_unit' ? '25.00' : '176.55' }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">
                                    {{ $price_type === 'per_unit' ? '/m²' : 'total' }}
                                </span>
                            </div>
                        </div>
                        @error('unit_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($width > 0 && $height > 0 && $unit_price > 0)
                    <div class="mt-4 p-4 bg-white border border-purple-200 rounded-lg">
                        <h5 class="font-medium text-gray-900 mb-3">Información Calculada</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Dimensiones:</span>
                                    <span class="font-medium">{{ $width }}m × {{ $height }}m</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Área total:</span>
                                    <span class="font-medium text-purple-600">{{ number_format($this->calculatedArea, 3) }} m²</span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Precio por m²:</span>
                                    <span class="font-medium text-green-600">${{ number_format($this->pricePerSquareMeter, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Precio total pieza:</span>
                                    <span class="font-medium text-blue-600">${{ number_format($this->totalPiecePrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Materiales por unidad -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <h4 class="font-medium text-green-900 mb-2">Material por Unidades Individuales</h4>
                <p class="text-sm text-green-700 mb-4">
                    Define el precio por cada unidad individual del material.
                </p>
                
                <div class="max-w-sm">
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio por {{ $unit_measure ?: 'Unidad' }} *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">$</span>
                        </div>
                        <input wire:model.live="unit_price" type="number" step="0.0001" min="0" id="unit_price"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unit_price') border-red-500 @enderror"
                               placeholder="0.1500">
                    </div>
                    @error('unit_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if($unit_price && !$is_by_piece && !$has_dimensions)
                        <p class="mt-1 text-xs text-green-600">
                            Precio: ${{ number_format($unit_price, 4) }} por {{ $unit_measure ?: 'unidad' }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Botones -->
    <div class="flex justify-end space-x-3 pt-6 border-t">
        <button type="button" wire:click="$dispatch('closeModal')" 
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancelar
        </button>
        <button type="submit" 
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
            {{ $materialId ? 'Actualizar' : 'Crear' }} Material
        </button>
    </div>
</form>
