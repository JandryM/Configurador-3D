<form wire:submit="save" class="space-y-3 bg-transparent text-white">
    <!-- Información básica -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-slate-200 mb-1">
                Nombre del Material *
            </label>
            <input wire:model="name" type="text" id="name" 
                   class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                   placeholder="Ej: Riel de Aluminio">
            @error('name') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

    <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-200 mb-1">
                Descripción
            </label>
            <textarea wire:model="description" id="description" rows="2"
                      class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                      placeholder="Descripción detallada del material..."></textarea>
            @error('description') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Selección de categoría -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-200 mb-1">
                Categoría *
            </label>
            <select wire:model.live="category_id" id="category_id"
                    class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror">
                <option value="">Selecciona una categoría...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

                <div>
            <label class="block text-sm font-medium text-slate-200 mb-1">
                Tipo de Material *
            </label>
            <div class="space-y-1.5">
                <label class="flex items-center p-2 border border-slate-600 bg-slate-700 rounded hover:bg-slate-600 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="units"
                           name="material_type" 
                           class="mr-2">
                    <div>
                        <span class="text-sm font-medium text-white">Por unidades individuales</span>
                        <p class="text-xs text-slate-400">Tornillos, componentes</p>
                    </div>
                </label>
                <label class="flex items-center p-2 border border-slate-600 bg-slate-700 rounded hover:bg-slate-600 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="pieces"
                           name="material_type" 
                           class="mr-2">
                    <div>
                        <span class="text-sm font-medium text-white">Por piezas completas</span>
                        <p class="text-xs text-slate-400">Rieles, perfiles (metros lineales)</p>
                    </div>
                </label>
                <label class="flex items-center p-2 border border-slate-600 bg-slate-700 rounded hover:bg-slate-600 cursor-pointer">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="dimensions"
                           name="material_type" 
                           class="mr-2">
                    <div>
                        <span class="text-sm font-medium text-white">Por dimensiones (ancho × alto)</span>
                        <p class="text-xs text-slate-400">Vidrios, láminas (m²)</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Configuración de precios -->
    <div class="border-t border-slate-600 pt-3">
        <h3 class="text-base font-medium text-white mb-2">Configuración de Precios</h3>
        
        @if($is_by_piece)
            <!-- Materiales por pieza -->
            <div class="bg-blue-900/30 border border-blue-700 rounded p-2 mb-2">
                <h4 class="text-sm font-medium text-blue-300 mb-1">Material por Piezas Completas</h4>
                <p class="text-xs text-blue-400 mb-2">
                    El precio se calculará automáticamente por unidad dividiendo el precio de la pieza entre su tamaño.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label for="piece_size" class="block text-sm font-medium text-slate-200 mb-1">
                            Tamaño de Pieza Completa *
                        </label>
                        <div class="relative">
                            <input wire:model.live="piece_size" type="number" step="0.001" min="0.001" id="piece_size"
                                   class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('piece_size') border-red-500 @enderror"
                                   placeholder="6.400">
                            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">{{ $unit_measure ?: 'unidades' }}</span>
                            </div>
                        </div>
                        @error('piece_size') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($piece_size)
                            <p class="mt-0.5 text-xs text-blue-400">
                                {{ number_format($piece_size, 3, '.', '') }} {{ $unit_measure }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label for="piece_price" class="block text-sm font-medium text-slate-200 mb-1">
                            Precio por Pieza Completa *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">$</span>
                            </div>
                            <input wire:model.live="piece_price" type="number" step="0.01" min="0" id="piece_price"
                                   class="w-full pl-6 pr-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('piece_price') border-red-500 @enderror"
                                   placeholder="45.00">
                        </div>
                        @error('piece_price') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($piece_size > 0 && $piece_price > 0)
                    <div class="mt-2 p-2 bg-slate-700 border border-blue-600 rounded">
                        <p class="text-sm font-medium text-white">
                            Precio por {{ $unit_measure ?: 'unidad' }}: 
                            <span class="text-green-400">${{ number_format($this->pricePerUnit, 4) }}</span>
                        </p>
                    </div>
                @endif
            </div>
        @elseif($has_dimensions)
            <!-- Materiales por dimensiones -->
            <div class="bg-purple-900/30 border border-purple-700 rounded p-2 mb-2">
                <h4 class="text-sm font-medium text-purple-300 mb-1">Material por Dimensiones</h4>
                <p class="text-xs text-purple-400 mb-2">
                    Ingresa las dimensiones y el precio total de la pieza. El sistema calculará automáticamente el precio por m².
                </p>
                
                <!-- Selector de tipo de entrada de dimensiones -->
                <div class="mb-2 space-y-1">
                    <label class="block text-sm font-medium text-slate-200">Forma de ingresar dimensiones:</label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input wire:model.live="dimension_input_type" type="radio" value="width_height" name="dimension_input_type" class="mr-1.5">
                            <span class="text-sm text-slate-300">Ancho × Alto</span>
                        </label>
                        <label class="flex items-center">
                            <input wire:model.live="dimension_input_type" type="radio" value="direct_area" name="dimension_input_type" class="mr-1.5">
                            <span class="text-sm text-slate-300">Área total directa</span>
                        </label>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    @if($dimension_input_type === 'width_height')
                        <!-- Entrada por ancho y alto -->
                        <div>
                            <label for="width" class="block text-sm font-medium text-slate-200 mb-1">
                                Ancho *
                            </label>
                            <div class="relative">
                                <input wire:model.live="width" type="number" step="0.001" min="0.001" id="width"
                                       class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('width') border-red-500 @enderror"
                                       placeholder="2.140">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-sm">m</span>
                                </div>
                            </div>
                            @error('width') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="height" class="block text-sm font-medium text-slate-200 mb-1">
                                Alto *
                            </label>
                            <div class="relative">
                                <input wire:model.live="height" type="number" step="0.001" min="0.001" id="height"
                                       class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('height') border-red-500 @enderror"
                                       placeholder="3.300">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-sm">m</span>
                                </div>
                            </div>
                            @error('height') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <!-- Entrada por área directa -->
                        <div class="md:col-span-2">
                            <label for="area" class="block text-sm font-medium text-slate-200 mb-1">
                                Área Total *
                            </label>
                            <div class="relative">
                                <input wire:model.live="area" type="number" step="0.001" min="0.001" id="area"
                                       class="w-full px-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('area') border-red-500 @enderror"
                                       placeholder="200.000">
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <span class="text-slate-400 text-sm">m²</span>
                                </div>
                            </div>
                            @error('area') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <!-- Campo de precio total de la pieza -->
                    <div>
                        <label for="unit_price_dimensions" class="block text-sm font-medium text-slate-200 mb-1">
                            Precio Total de la Pieza *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">$</span>
                            </div>
                            <input wire:model.live="unit_price" type="number" step="0.01" min="0" id="unit_price_dimensions"
                                   class="w-full pl-6 pr-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('unit_price') border-red-500 @enderror"
                                   placeholder="60.00">
                        </div>
                        @error('unit_price') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-0.5 text-xs text-slate-400">
                            Ingresa el precio total de toda la pieza
                        </p>
                    </div>
                </div>

                @if($this->calculatedArea > 0 && $unit_price > 0)
                    <div class="mt-2 p-2 bg-slate-700 border border-purple-600 rounded">
                        <h5 class="text-sm font-medium text-white mb-1">Información Calculada</h5>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="space-y-1">
                                @if($dimension_input_type === 'width_height')
                                    <div class="flex justify-between">
                                        <span class="text-slate-300">Dimensiones:</span>
                                        <span class="font-medium text-white">{{ $width }}m × {{ $height }}m</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Área total:</span>
                                    <span class="font-medium text-purple-400">{{ number_format($this->calculatedArea, 3) }} m²</span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Precio total pieza:</span>
                                    <span class="font-medium text-blue-400">${{ number_format($unit_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-300">Precio por m²:</span>
                                    <span class="font-medium text-green-400">${{ number_format($this->pricePerSquareMeter, 4) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Materiales por unidad -->
            <div class="bg-green-900/30 border border-green-700 rounded p-2 mb-2">
                <h4 class="text-sm font-medium text-green-300 mb-1">Material por Unidades Individuales</h4>
                <p class="text-xs text-green-400 mb-2">
                    Define el precio por cada unidad individual del material.
                </p>
                
                <div class="max-w-sm">
                    <label for="unit_price" class="block text-sm font-medium text-slate-200 mb-1">
                        Precio por {{ $unit_measure ?: 'Unidad' }} *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <span class="text-slate-400 text-sm">$</span>
                        </div>
                        <input wire:model.live="unit_price" type="number" step="0.0001" min="0" id="unit_price"
                               class="w-full pl-6 pr-2 py-1.5 text-sm bg-slate-700 text-white border border-slate-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent @error('unit_price') border-red-500 @enderror"
                               placeholder="0.1500">
                    </div>
                    @error('unit_price') <p class="mt-0.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if($unit_price && !$is_by_piece && !$has_dimensions)
                        <p class="mt-0.5 text-xs text-green-400">
                            Precio: ${{ number_format($unit_price, 4) }} por {{ $unit_measure ?: 'unidad' }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Botones -->
    <div class="flex justify-end space-x-2 pt-3 border-t border-slate-600">
        <button type="button" wire:click="$dispatch('closeModal')" 
                class="px-3 py-1.5 border border-slate-600 rounded text-sm font-medium text-slate-200 bg-slate-700 hover:bg-slate-600 focus:outline-none focus:ring-1 focus:ring-blue-500">
            Cancelar
        </button>
        <button type="submit" 
                class="px-3 py-1.5 border border-transparent rounded shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50">
            {{ $materialId ? 'Actualizar' : 'Crear' }} Material
        </button>
    </div>
</form>
