<form wire:submit="save" class="space-y-4">
    <!-- Información básica -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                Nombre del Material *
            </label>
            <input wire:model="name" type="text" id="name" 
                   class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('name') border-red-500 @enderror"
                   placeholder="Ej: Riel de Aluminio">
            @error('name') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                Descripción
            </label>
            <textarea wire:model="description" id="description" rows="2"
                      class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('description') border-red-500 @enderror"
                      placeholder="Descripción detallada del material..."></textarea>
            @error('description') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Selección de categoría -->
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">
                Categoría *
            </label>
            <select wire:model.live="category_id" id="category_id"
                    class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('category_id') border-red-500 @enderror">
                <option value="">Selecciona una categoría...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Tipo de Material *
            </label>
            <div class="space-y-2">
                <label class="flex items-center p-3 border border-slate-200 bg-slate-50 rounded-lg hover:bg-white hover:border-slate-300 cursor-pointer transition-all">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="units"
                           name="material_type" 
                           class="mr-3 text-slate-900 focus:ring-slate-900">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Por unidades individuales</span>
                        <p class="text-xs text-slate-500">Tornillos, componentes</p>
                    </div>
                </label>
                <label class="flex items-center p-3 border border-slate-200 bg-slate-50 rounded-lg hover:bg-white hover:border-slate-300 cursor-pointer transition-all">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="pieces"
                           name="material_type" 
                           class="mr-3 text-slate-900 focus:ring-slate-900">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Por piezas completas</span>
                        <p class="text-xs text-slate-500">Rieles, perfiles (metros lineales)</p>
                    </div>
                </label>
                <label class="flex items-center p-3 border border-slate-200 bg-slate-50 rounded-lg hover:bg-white hover:border-slate-300 cursor-pointer transition-all">
                    <input wire:model.live="material_type" 
                           type="radio" 
                           value="dimensions"
                           name="material_type" 
                           class="mr-3 text-slate-900 focus:ring-slate-900">
                    <div>
                        <span class="text-sm font-medium text-slate-800">Por dimensiones (ancho × alto)</span>
                        <p class="text-xs text-slate-500">Vidrios, láminas (m²)</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Configuración de precios -->
    <div class="border-t border-slate-100 pt-4 mt-2">
        <h3 class="text-base font-bold text-slate-800 mb-3">Configuración de Precios</h3>
        
        @if($is_by_piece)
            <!-- Materiales por pieza -->
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-2">
                <h4 class="text-sm font-bold text-slate-800 mb-1">Material por Piezas Completas</h4>
                <p class="text-xs text-slate-500 mb-3">
                    El precio se calculará automáticamente por unidad dividiendo el precio de la pieza entre su tamaño.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="piece_size" class="block text-sm font-medium text-slate-700 mb-1">
                            Tamaño de Pieza Completa *
                        </label>
                        <div class="relative">
                            <input wire:model.live="piece_size" type="number" step="0.001" min="0.001" id="piece_size"
                                   class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('piece_size') border-red-500 @enderror"
                                   placeholder="6.400">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">{{ $unit_measure ?: 'unidades' }}</span>
                            </div>
                        </div>
                        @error('piece_size') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        @if($piece_size)
                            <p class="mt-1 text-xs text-slate-600 font-medium">
                                {{ number_format($piece_size, 3, '.', '') }} {{ $unit_measure }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label for="piece_price" class="block text-sm font-medium text-slate-700 mb-1">
                            Precio por Pieza Completa *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">$</span>
                            </div>
                            <input wire:model.live="piece_price" type="number" step="0.01" min="0" id="piece_price"
                                   class="w-full pl-7 pr-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('piece_price') border-red-500 @enderror"
                                   placeholder="45.00">
                        </div>
                        @error('piece_price') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($piece_size > 0 && $piece_price > 0)
                    <div class="mt-3 p-3 bg-white border border-slate-200 rounded-lg">
                        <p class="text-sm font-medium text-slate-700">
                            Precio por {{ $unit_measure ?: 'unidad' }}: 
                            <span class="text-emerald-600 font-bold">${{ number_format($this->pricePerUnit, 4) }}</span>
                        </p>
                    </div>
                @endif
            </div>
        @elseif($has_dimensions)
            <!-- Materiales por dimensiones -->
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-2">
                <h4 class="text-sm font-bold text-slate-800 mb-1">Material por Dimensiones</h4>
                <p class="text-xs text-slate-500 mb-3">
                    Ingresa las dimensiones y el precio total de la pieza. El sistema calculará automáticamente el precio por m².
                </p>
                
                <!-- Selector de tipo de entrada de dimensiones -->
                <div class="mb-3 space-y-1">
                    <label class="block text-sm font-medium text-slate-700">Forma de ingresar dimensiones:</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input wire:model.live="dimension_input_type" type="radio" value="width_height" name="dimension_input_type" class="mr-2 text-slate-900 focus:ring-slate-900">
                            <span class="text-sm text-slate-700">Ancho × Alto</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input wire:model.live="dimension_input_type" type="radio" value="direct_area" name="dimension_input_type" class="mr-2 text-slate-900 focus:ring-slate-900">
                            <span class="text-sm text-slate-700">Área total directa</span>
                        </label>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($dimension_input_type === 'width_height')
                        <!-- Entrada por ancho y alto -->
                        <div>
                            <label for="width" class="block text-sm font-medium text-slate-700 mb-1">
                                Ancho *
                            </label>
                            <div class="relative">
                                <input wire:model.live="width" type="number" step="0.001" min="0.001" id="width"
                                       class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('width') border-red-500 @enderror"
                                       placeholder="2.140">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 text-sm">m</span>
                                </div>
                            </div>
                            @error('width') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="height" class="block text-sm font-medium text-slate-700 mb-1">
                                Alto *
                            </label>
                            <div class="relative">
                                <input wire:model.live="height" type="number" step="0.001" min="0.001" id="height"
                                       class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('height') border-red-500 @enderror"
                                       placeholder="3.300">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 text-sm">m</span>
                                </div>
                            </div>
                            @error('height') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <!-- Entrada por área directa -->
                        <div class="md:col-span-2">
                            <label for="area" class="block text-sm font-medium text-slate-700 mb-1">
                                Área Total *
                            </label>
                            <div class="relative">
                                <input wire:model.live="area" type="number" step="0.001" min="0.001" id="area"
                                       class="w-full px-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('area') border-red-500 @enderror"
                                       placeholder="200.000">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 text-sm">m²</span>
                                </div>
                            </div>
                            @error('area') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <!-- Campo de precio total de la pieza -->
                    <div>
                        <label for="unit_price_dimensions" class="block text-sm font-medium text-slate-700 mb-1">
                            Precio Total de la Pieza *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 text-sm">$</span>
                            </div>
                            <input wire:model.live="unit_price" type="number" step="0.01" min="0" id="unit_price_dimensions"
                                   class="w-full pl-7 pr-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all @error('unit_price') border-red-500 @enderror"
                                   placeholder="60.00">
                        </div>
                        @error('unit_price') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-slate-500">
                            Ingresa el precio total de toda la pieza
                        </p>
                    </div>
                </div>

                @if($this->calculatedArea > 0 && $unit_price > 0)
                    <div class="mt-3 p-3 bg-white border border-slate-200 rounded-lg">
                        <h5 class="text-sm font-bold text-slate-800 mb-2">Información Calculada</h5>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="space-y-1">
                                @if($dimension_input_type === 'width_height')
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Dimensiones:</span>
                                        <span class="font-medium text-slate-800">{{ $width }}m × {{ $height }}m</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Área total:</span>
                                    <span class="font-medium text-slate-800">{{ number_format($this->calculatedArea, 3) }} m²</span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Precio total pieza:</span>
                                    <span class="font-medium text-emerald-600">${{ number_format($unit_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Precio por m²:</span>
                                    <span class="font-medium text-emerald-600">${{ number_format($this->pricePerUnit, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- Materiales por unidad -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-4 mb-2">
                <h4 class="text-sm font-bold text-emerald-800 mb-1">Material por Unidades Individuales</h4>
                <p class="text-xs text-emerald-600 mb-3">
                    Define el precio por cada unidad individual del material.
                </p>
                
                <div class="max-w-sm">
                    <label for="unit_price" class="block text-sm font-medium text-slate-700 mb-1">
                        Precio por {{ $unit_measure ?: 'Unidad' }} *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 text-sm">$</span>
                        </div>
                        <input wire:model.live="unit_price" type="number" step="0.0001" min="0" id="unit_price"
                               class="w-full pl-7 pr-3 py-2 text-sm bg-white text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('unit_price') border-red-500 @enderror"
                               placeholder="0.1500">
                    </div>
                    @error('unit_price') <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                    @if($unit_price && !$is_by_piece && !$has_dimensions)
                        <p class="mt-1 text-xs text-emerald-600 font-medium">
                            Precio: ${{ number_format($unit_price, 4) }} por {{ $unit_measure ?: 'unidad' }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Fórmulas de cálculo por producto (solo para materiales existentes) --}}
    {{-- @if($materialId)
        @php
            $productFormulas = DB::table('product_material')
                ->join('products', 'product_material.product_id', '=', 'products.id')
                ->where('product_material.material_id', $materialId)
                ->whereNotNull('product_material.calculation_formula')
                ->where('product_material.calculation_formula', '!=', '')
                ->select('products.name as product_name', 'product_material.calculation_formula')
                ->get();
        @endphp
        
        @if($productFormulas->count() > 0)
            <div class="border-t border-slate-100 pt-4">
                <div class="bg-gradient-to-br from-blue-50 to-teal-50 rounded-lg p-4 border-2 border-teal-200 shadow-sm">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="p-2 bg-teal-100 rounded-lg">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-teal-900">Fórmulas de Cálculo en Productos</h3>
                    </div>
                    <p class="text-xs text-teal-600 mb-3">Este material se calcula con las siguientes fórmulas en cada producto:</p>
                    <div class="space-y-2">
                        @foreach($productFormulas as $index => $formula)
                            <div class="bg-white rounded-lg p-3 border-l-4 border-l-teal-500 border border-teal-100 shadow-sm hover:shadow-md transition-shadow" x-data="{ 
                                open: false, 
                                width: 2, 
                                height: 2, 
                                frameWidth: 0.05,
                                result: null,
                                pricePerUnit: {{ $this->pricePerUnit ?? 0 }},
                                calculate() {
                                    const formula = '{{ addslashes($formula->calculation_formula) }}';
                                    try {
                                        const replacedFormula = formula
                                            .replace(/\{width\}/g, this.width)
                                            .replace(/\{height\}/g, this.height)
                                            .replace(/\{frameWidth\}/g, this.frameWidth)
                                            .replace(/\{area\}/g, this.width * this.height)
                                            .replace(/\{perimeter\}/g, 2 * (parseFloat(this.width) + parseFloat(this.height)))
                                            .replace(/\{volume\}/g, this.width * this.height * 1);
                                        this.result = eval(replacedFormula);
                                    } catch(e) {
                                        this.result = 'Error en el cálculo';
                                    }
                                }
                            }">
                                <p class="text-xs font-bold text-teal-900 mb-1">{{ $formula->product_name }}</p>
                                <code class="text-xs text-teal-700 bg-teal-50 px-2 py-1 rounded font-mono block break-all border border-teal-200">
                                    {{ $formula->calculation_formula }}
                                </code>
                                
                                <!-- Botón colapsable discreto -->
                                <button 
                                    type="button"
                                    @click="open = !open; if(open && !result) calculate()"
                                    class="mt-2 text-xs text-teal-600 hover:text-teal-800 hover:bg-teal-50 flex items-center gap-1 transition-colors cursor-pointer font-medium px-2 py-1 rounded"
                                >
                                    <svg class="w-3 h-3 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    <span>Calcular con valores personalizados</span>
                                </button>
                                
                                <!-- Panel colapsable -->
                                <div 
                                    x-show="open" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="mt-3 bg-gradient-to-b from-teal-50 to-blue-50 rounded-lg p-3 border border-teal-200"
                                    style="display: none;"
                                >
                                    <div class="grid grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label class="text-xs text-teal-700 block mb-1 font-medium">Ancho (m)</label>
                                            <input 
                                                type="number" 
                                                x-model="width" 
                                                @input="calculate()"
                                                step="0.01"
                                                min="0"
                                                class="w-full px-2 py-1 text-xs bg-white text-slate-900 border-2 border-teal-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="text-xs text-teal-700 block mb-1 font-medium">Alto (m)</label>
                                            <input 
                                                type="number" 
                                                x-model="height" 
                                                @input="calculate()"
                                                step="0.01"
                                                min="0"
                                                class="w-full px-2 py-1 text-xs bg-white text-slate-900 border-2 border-teal-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                            >
                                        </div>
                                        <div>
                                            <label class="text-xs text-teal-700 block mb-1 font-medium">Marco (m)</label>
                                            <input 
                                                type="number" 
                                                x-model="frameWidth" 
                                                @input="calculate()"
                                                step="0.001"
                                                min="0"
                                                class="w-full px-2 py-1 text-xs bg-white text-slate-900 border-2 border-teal-300 rounded focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                            >
                                        </div>
                                    </div>
                                    
                                    <!-- Resultado -->
                                    <div class="bg-white rounded p-2 border-2 border-emerald-300 shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-xs font-bold text-emerald-700">Resultado del cálculo:</span>
                                        </div>
                                        <div class="ml-5 space-y-1">
                                            <div class="text-xs text-teal-600">
                                                <span class="text-teal-500 font-medium">Fórmula evaluada:</span>
                                                <code class="block text-teal-800 bg-teal-100 px-2 py-1 rounded mt-0.5 font-mono text-xs break-all border border-teal-300" x-text="'{{ $formula->calculation_formula }}'.replace(/\{width\}/g, width).replace(/\{height\}/g, height).replace(/\{frameWidth\}/g, frameWidth).replace(/\{area\}/g, (width * height).toFixed(3)).replace(/\{perimeter\}/g, (2 * (parseFloat(width) + parseFloat(height))).toFixed(3))"></code>
                                            </div>
                                            <div class="text-xs">
                                                <span class="text-teal-700 font-medium">Cantidad necesaria:</span>
                                                <span class="text-lg font-bold text-emerald-600 ml-1" x-text="result !== null ? parseFloat(result).toFixed(3) : '---'"></span>
                                                <span class="text-teal-600 ml-1">{{ $unit_measure == 'metros_cuadrados' ? 'm²' : ($unit_measure == 'unidad' ? 'unidades' : 'm') }}</span>
                                            </div>
                                            <div class="text-xs mt-1 pt-1 border-t border-slate-100">
                                                <span class="text-teal-700 font-medium">Costo estimado:</span>
                                                <span class="text-lg font-bold text-amber-600 ml-1" x-text="result !== null && pricePerUnit > 0 ? '$' + (parseFloat(result) * pricePerUnit).toFixed(4) : '---'"></span>
                                                <span class="text-slate-400 text-xs ml-1" x-text="pricePerUnit > 0 ? '($' + pricePerUnit.toFixed(4) + ' × ' + (result !== null ? parseFloat(result).toFixed(3) : '0') + ')' : ''"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif --}}

    <!-- Botones -->
    <div class="flex justify-end pt-4 border-t border-slate-100">
        <button type="submit" 
                class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
            {{ $materialId ? 'Actualizar Material' : 'Crear Material' }}
        </button>
    </div>
</form>
