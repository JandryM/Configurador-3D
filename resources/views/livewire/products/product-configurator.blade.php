<div class="w-full px-2 sm:px-4 lg:px-8 py-8 bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 text-slate-800 pt-24 min-h-screen">
        <!-- Título -->
        <div class="mb-8">
            <div class="flex items-start gap-6">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Configurador 3D</span> - {{ $product->name }}
                    </h1>
                    <p class="text-slate-600 text-lg mt-2">Personaliza tu producto y ve los cambios en tiempo real</p>
                    <div class="mt-3 flex items-center gap-4 text-sm">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 font-medium border border-blue-200/50">
                            {{ $product->category?->name ?? 'Sin categoría' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        @if (session()->has('message'))
            <div class="mb-6 rounded-2xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200/50 p-4 shadow-lg backdrop-blur-sm">
                <p class="text-sm font-medium text-green-700">{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Visor 3D -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Producto -->
                <div class="bg-slate-50/80 backdrop-blur-sm rounded-3xl shadow-xl border border-slate-200/50 p-6 transition-all duration-300 hover:shadow-2xl">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $product->name }}</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <p class="text-slate-600 leading-relaxed mt-1">{{ Str::limit($product->description, 120) }}</p>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">${{ number_format($calculatedPrice, 2) }}</span>
                                <span class="text-sm text-slate-500">precio calculado por materiales</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Configurador 3D -->
                <div class="bg-slate-50/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 p-4 transition-all duration-300 hover:shadow-2xl w-fit mx-auto">
                    <h2 class="text-xl font-bold text-slate-800 mb-3 text-center">Vista 3D Interactiva</h2>
                    
                    <div wire:ignore id="parametric-3d-viewer" class="w-96 h-96 bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 rounded-2xl border-2 border-slate-300/50 relative overflow-hidden shadow-inner">
                    </div>
                    <div class="mt-3 flex justify-center space-x-3">
                        <button type="button" 
                                onclick="resetParametricView()"
                                class="px-3 py-2 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-slate-200"
                                title="Resetear vista">
                            🔄 Resetear Vista
                        </button>
                        <button type="button" 
                                onclick="takeParametricScreenshot()"
                                class="px-3 py-2 text-sm bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg border border-blue-500/20"
                                title="Tomar captura">
                            📸 Capturar Imagen
                        </button>
                    </div>

                    <div class="mt-3 p-3 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-100/50 shadow-inner">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="text-center">
                                <span class="block font-semibold text-slate-700 mb-1">Ancho</span>
                                <span class="text-blue-600 font-bold text-base">{{ number_format($parameters['width'], 3) }}m</span>
                            </div>
                            <div class="text-center">
                                <span class="block font-semibold text-slate-700 mb-1">Alto</span>
                                <span class="text-cyan-600 font-bold text-base">{{ number_format($parameters['height'], 3) }}m</span>
                            </div>
                            <div class="text-center">
                                <span class="block font-semibold text-slate-700 mb-1">Área</span>
                                <span class="text-emerald-600 font-bold text-base">{{ number_format($parameters['width'] * $parameters['height'], 3) }}m²</span>
                            </div>
                            <div class="text-center">
                                <span class="block font-semibold text-slate-700 mb-1">Precio</span>
                                <span class="text-slate-800 font-bold text-base">${{ number_format($calculatedPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        <div class="space-y-4">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-slate-200 p-4">
                <h3 class="text-lg font-bold text-slate-800 mb-3">🔢 Cantidad de Unidades</h3>
                
                <div class="flex items-center gap-3">
                    <button type="button" 
                            wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                            class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg font-bold transition-all duration-200 hover:scale-105 shadow-md">
                        -
                    </button>
                    
                    <input type="number" 
                           wire:model.live="quantity"
                           min="1" 
                           step="1"
                           class="flex-1 px-4 py-2 text-center text-xl font-bold border-2 border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    
                    <button type="button" 
                            wire:click="$set('quantity', {{ $quantity + 1 }})"
                            class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-bold transition-all duration-200 hover:scale-105 shadow-md">
                        +
                    </button>
                </div>
                
                <p class="text-sm text-slate-600 mt-2 text-center">
                    Precio por unidad: <span class="font-semibold text-blue-600">${{ number_format($calculatedPrice / max(1, $quantity), 2) }}</span>
                </p>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-slate-200 p-4">
                <h3 class="text-lg font-bold text-slate-800 mb-3">📏 Dimensiones</h3>
                
                @foreach(['width' => 'Ancho', 'height' => 'Alto', 'depth' => 'Profundidad'] as $param => $label)
                    @if($param === 'depth' && $productType === 'window') @continue @endif
                    @if(isset($parameterLimits[$param]))
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            {{ $label }}: <span class="text-blue-600">{{ number_format($parameters[$param], 2) }}m</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="range" wire:model.live="parameters.{{ $param }}"
                                   min="{{ $parameterLimits[$param]['min'] }}" max="{{ $parameterLimits[$param]['max'] }}" step="0.001"
                                   class="w-full h-2 bg-gradient-to-r from-blue-200 to-cyan-200 rounded-lg slider-thumb">
                            <input type="number" wire:model.blur="parameters.{{ $param }}"
                                   min="{{ $parameterLimits[$param]['min'] }}" max="{{ $parameterLimits[$param]['max'] }}" step="0.001"
                                   class="w-20 px-2 py-1 border border-slate-300 rounded-lg text-sm">
                        </div>
                        <div class="flex justify-between text-xs text-slate-500 mt-1">
                            <span>Min: {{ $parameterLimits[$param]['min'] }}m</span>
                            <span>Max: {{ $parameterLimits[$param]['max'] }}m</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="bg-slate-50/80 backdrop-blur-sm rounded-3xl shadow-xl border border-slate-200/50 p-6 transition-all duration-300 hover:shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">🎨 Colores Disponibles</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Color del Aluminio</label>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($availableColors as $colorName => $color)
                                    @if(!str_contains(strtolower($colorName), 'glass') && !str_contains(strtolower($colorName), 'vidrio'))
                                    <div class="relative">
                                        <input type="radio" 
                                               wire:change="updateParameter('color', '{{ $colorName }}')"
                                               name="color_selection" 
                                               value="{{ $colorName }}"
                                               id="color_{{ $loop->index }}"
                                               class="sr-only peer"
                                               @if($parameters['color'] === $colorName) checked @endif>
                                        
                                        <label for="color_{{ $loop->index }}" 
                             class="flex items-center p-4 bg-white rounded-lg border-2 cursor-pointer transition-all
                                 hover:border-blue-300 hover:shadow-md
                                 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-black">
                                            
                                            <div class="w-12 h-12 rounded-md border-2 border-gray-200 mr-4 shadow-inner
                                                        @if($colorName === 'Natural') bg-gradient-to-br from-gray-200 to-gray-300
                                                        @elseif($colorName === 'White') bg-gradient-to-br from-white to-gray-100
                                                        @elseif($colorName === 'Black Anodized') bg-gradient-to-br from-gray-800 to-black
                                                        @elseif($colorName === 'Woody') bg-gradient-to-br from-yellow-100 to-yellow-200
                                                        @elseif($colorName === 'Bronze') bg-gradient-to-br from-yellow-600 to-yellow-800
                                                        @else bg-gradient-to-br from-gray-300 to-gray-400 @endif">
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="font-medium text-black">{{ $color->color_name }}</div>
                                                <div class="text-sm text-black">
                                                    Aluminio {{ strtolower($color->color_name) }}
                                                </div>
                                            </div>
                                            
                                            <div class="ml-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Color del Vidrio</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($availableColors as $colorName => $color)
                                    @if(str_contains(strtolower($colorName), 'glass') || str_contains(strtolower($colorName), 'vidrio'))
                                    <div class="relative">
                                        <input type="radio"
                                               wire:change="updateParameter('glassColor', '{{ $colorName }}')"
                                               name="glass_color_selection"
                                               value="{{ $colorName }}"
                                               id="glass_color_{{ $loop->index }}"
                                               class="sr-only peer"
                                               @if($parameters['glassColor'] === $colorName) checked @endif>
                                        <label for="glass_color_{{ $loop->index }}"
                             class="flex items-center p-4 bg-white rounded-lg border-2 cursor-pointer transition-all
                                 hover:border-blue-300 hover:shadow-md
                                 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-black">
                                            <div class="w-12 h-12 rounded-md border-2 border-gray-200 mr-4 shadow-inner bg-gradient-to-br from-blue-100 to-blue-300"></div>
                                            <div class="flex-1">
                                                <div class="font-medium text-black">{{ $color->color_name }}</div>
                                                <div class="text-sm text-black">Vidrio {{ strtolower($color->color_name) }}</div>
                                            </div>
                                            <div class="ml-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-slate-50 to-blue-50 backdrop-blur-sm rounded-3xl shadow-xl border border-slate-200/50 p-6 transition-all duration-300 hover:shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">💰 Precio Estimado</h3>
                    
                    <div class="mb-4 p-3 bg-white/50 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-600">Cantidad:</span>
                            <span class="text-lg font-bold text-slate-800">{{ $quantity }} {{ $quantity == 1 ? 'unidad' : 'unidades' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Precio unitario:</span>
                            <span class="text-lg font-semibold text-blue-600">${{ number_format($calculatedPrice / max(1, $quantity), 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-2">
                        ${{ number_format($calculatedPrice, 2) }}
                    </div>
                    <p class="text-sm text-slate-500 mb-6">Precio total</p>

                    <div class="space-y-3">
                        @if(Auth::check())
                            <button type="button"
                                    wire:click="$set('showProformaModal', true)"
                                    class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl border border-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                    @if(empty($calculatedPrice) || $calculatedPrice == 0) disabled aria-disabled="true" @endif>
                                📋 Visualizar Proforma
                            </button>
                        @else
                            <div class="w-full bg-gradient-to-r from-yellow-100 to-amber-100 border border-yellow-300/50 text-yellow-800 font-semibold py-3 px-4 rounded-xl text-center shadow-md">
                                ⚠️ Debes iniciar sesión para agregar a la proforma.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div x-data="{ show: @entangle('showProformaModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px] transition-opacity"></div>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="w-full max-w-2xl relative overflow-visible">
                    <button @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10">&times;</button>
                    @if(Auth::check())
                        <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full p-6 md:p-8 relative text-white overflow-hidden">
                            @if (session()->has('message'))
                                <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed inset-0 z-60 flex items-center justify-center bg-black bg-opacity-40">
                                    <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl max-w-sm w-full p-6 relative border-2 border-green-500">
                                        <button type="button" @click="show = false" class="absolute top-2 right-2 text-gray-400 hover:text-white text-2xl">&times;</button>
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-green-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            <h3 class="text-xl font-bold text-white mb-2">
                                                @if(str_contains(session('message'), 'Orden creada'))
                                                    ¡Orden creada exitosamente!
                                                @elseif(str_contains(session('message'), 'agregada'))
                                                    ¡Configuración agregada!
                                                @else
                                                    ¡Operación exitosa!
                                                @endif
                                            </h3>
                                            <p class="text-white/80 text-center">{{ session('message') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="overflow-y-auto max-h-[75vh] pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);">
                                @if($currentProformaStatus === 'saved')
                                    <div class="mb-4 p-4 bg-green-500/20 border border-green-400/50 rounded-lg">
                                        <p class="text-green-200 text-sm font-medium mb-2">✓ Esta configuración ya está guardada</p>
                                        <p class="text-green-100/70 text-xs">Se encuentra en la proforma {{ DB::table('proformas')->where('id', $currentProformaId)->value('number') }}. Puedes actualizar la cantidad.</p>
                                    </div>
                                @else
                                    <div class="mb-4 p-4 bg-blue-500/20 border border-blue-400/50 rounded-lg">
                                        <p class="text-blue-200 text-sm font-medium mb-2">➕ Nueva configuración</p>
                                        <p class="text-blue-100/70 text-xs">Elige dónde guardar esta configuración: crea una nueva proforma o agrégala a una existente.</p>
                                    </div>
                                @endif

                                <div class="mb-4 p-4 bg-black/30 border border-white/20 rounded-xl">
                                    <h4 class="text-sm font-semibold text-white mb-3">🔢 Ajustar Cantidad</h4>
                                    <div class="flex items-center gap-3">
                                        <button type="button" 
                                                wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                                class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg font-bold transition-all duration-200 hover:scale-105 shadow-md">
                                            -
                                        </button>
                                        
                                        <input type="number" 
                                               wire:model.live="quantity"
                                               min="1" 
                                               step="1"
                                               class="flex-1 px-4 py-2 text-center text-lg font-bold border-2 border-white/20 bg-black/30 text-white rounded-lg focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200/50">
                                        
                                        <button type="button" 
                                                wire:click="$set('quantity', {{ $quantity + 1 }})"
                                                class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-bold transition-all duration-200 hover:scale-105 shadow-md">
                                            +
                                        </button>
                                    </div>
                                    <div class="mt-2 text-sm text-white/70 text-center">
                                        <span class="font-medium">Precio unitario:</span> ${{ number_format($calculatedPrice / max(1, $quantity), 2) }}
                                    </div>

                                    <!-- Botón colapsable para notas -->
                                    <div class="mt-2 flex justify-end">
                                        <button type="button" onclick="document.getElementById('notes-section').classList.toggle('hidden'); this.classList.toggle('opacity-70')" class="text-xs text-cyan-200 hover:text-cyan-100 px-2 py-1 rounded transition-opacity opacity-50 focus:outline-none">
                                            📝 Añadir notas opcionales
                                        </button>
                                    </div>
                                    <div id="notes-section" class="hidden mt-2">
                                        <textarea wire:model.defer="parameters.notes" rows="2" maxlength="250" placeholder="Notas para esta configuración (opcional)" class="w-full px-3 py-2 rounded-lg border border-white/20 bg-black/30 text-white text-xs focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200/50 resize-none"></textarea>
                                    </div>
                                </div>

                                @include('livewire.proformas.proforma', [
                                    'product' => $product,
                                    'parameters' => $parameters,
                                    'quantity' => $quantity,
                                    'materialCosts' => $materialCosts,
                                    'calculatedPrice' => $calculatedPrice,
                                    'notes' => $parameters['notes'] ?? null,
                                    'directCost' => $directCost ?? null,
                                    'indirectCost' => $indirectCost ?? null,
                                    'wastePercentage' => $wastePercentage ?? null
                                ])

                                @if($currentProformaId && $proformaItems && $proformaItems->count() > 0)
                                <div class="mt-6 pt-6 border-t border-white/20">
                                    <h4 class="text-lg font-bold text-white mb-4">📦 Ítems en esta Proforma ({{ $proformaItems->count() }} {{ $proformaItems->count() == 1 ? 'ítem' : 'ítems' }})</h4>
                                    <div class="space-y-3 max-h-64 overflow-y-auto">
                                        @foreach($proformaItems as $item)
                                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-white mb-1">{{ $item->product_name }}</h5>
                                                    <div class="text-xs text-white/70 space-y-1">
                                                        @if(isset($item->parsed_config['parameters']))
                                                        <div class="flex gap-4">
                                                            @if(isset($item->parsed_config['parameters']['width']))
                                                            <span>📏 {{ number_format($item->parsed_config['parameters']['width'], 2) }}m × {{ number_format($item->parsed_config['parameters']['height'], 2) }}m</span>
                                                            @endif
                                                            @if(isset($item->parsed_config['parameters']['color']))
                                                            <span>🎨 {{ $item->parsed_config['parameters']['color'] }}</span>
                                                            @endif
                                                        </div>
                                                        @endif
                                                        <div class="flex gap-4 mt-2">
                                                            <span class="font-medium">Cantidad: {{ $item->quantity }}</span>
                                                            <span class="font-bold text-cyan-300">${{ number_format($item->price, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($item->product_id == $product->id && isset($item->parsed_config['parameters']) && 
                                                    $item->parsed_config['parameters']['width'] == $parameters['width'] &&
                                                    $item->parsed_config['parameters']['height'] == $parameters['height'] &&
                                                    ($item->parsed_config['parameters']['color'] ?? null) == ($parameters['color'] ?? null))
                                                <span class="text-green-400 text-xs font-bold">✓ Actual</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-600/30 to-cyan-600/30 rounded-lg border border-blue-400/30">
                                        <div class="flex justify-between items-center">
                                            <span class="text-white font-semibold">Total de la Proforma:</span>
                                            <span class="text-2xl font-bold text-white">${{ number_format($proformaTotalPrice, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-6 pt-6 border-t border-white/20">
                                @if($currentProformaStatus === 'saved')
                                    <!-- Configuración ya guardada - Solo actualizar -->
                                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                                        <button type="button" 
                                                wire:click="guardarProforma" 
                                                class="px-4 py-2 text-sm font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                            🔄 Actualizar Cantidad
                                        </button>
                                        
                                        @if($currentProformaId)
                                            <button type="button" 
                                                    wire:click="orderProforma" 
                                                    class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                                🚀 Ordenar Proforma
                                            </button>
                                            
                                            <button type="button" 
                                                    wire:click="downloadProformaPdf({{ $currentProformaId }})" 
                                                    class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                                📄 Descargar PDF
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <!-- Botones principales para nueva configuración -->
                                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                                        <button type="button" 
                                                wire:click="crearNuevaProforma" 
                                                class="px-4 py-2 text-sm font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                            ➕ Crear Nueva Proforma
                                        </button>
                                        
                                        @if(count($availableProformas) > 0)
                                        <button type="button" 
                                                wire:click="openProformaSelectorModal" 
                                                class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                            📋 Agregar a Proforma Existente
                                        </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-xl w-full p-8 relative flex flex-col items-center">
                            <svg class="w-16 h-16 text-yellow-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <h3 class="text-xl font-bold text-white mb-2">Acceso restringido</h3>
                            <p class="text-white/70 text-center">Debes iniciar sesión para visualizar, guardar o descargar la proforma.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
            <!-- Modal Selector de Proformas (Separado) -->
    <div x-data="{ show: @entangle('showProformaSelectorModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-[60]">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="w-full max-w-lg relative">
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl w-full p-6 relative text-white border border-white/10">
                    <button @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10">&times;</button>
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">📋 Selecciona una Proforma</h3>
                        <p class="text-white/70 text-sm">Elige a qué proforma deseas agregar esta configuración</p>
                    </div>

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2 mb-6" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);">
                        @forelse($availableProformas as $proforma)
                        <label class="flex items-start p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-all border-2 @if($selectedProformaToAdd == $proforma['id']) border-cyan-500 bg-cyan-500/10 shadow-lg shadow-cyan-500/20 @else border-white/10 @endif group">
                            <input type="radio" 
                                   wire:model.live="selectedProformaToAdd" 
                                   value="{{ $proforma['id'] }}"
                                   class="mt-1 mr-4 w-5 h-5 flex-shrink-0 text-cyan-500">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-bold text-lg text-white">{{ $proforma['number'] }}</div>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-cyan-500/20 text-cyan-300 rounded-full">
                                        {{ $proforma['items_count'] }} {{ $proforma['items_count'] == 1 ? 'ítem' : 'ítems' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white/60">Total actual:</span>
                                    <span class="font-bold text-green-400">${{ number_format($proforma['total_price'], 2) }}</span>
                                </div>
                                <div class="text-xs text-white/50 mt-1">
                                    Creada: {{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="text-center text-white/70 py-8">
                            <svg class="w-16 h-16 mx-auto mb-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-medium">No tienes proformas disponibles</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="flex gap-3">
                        <button type="button" 
                                wire:click="closeProformaSelectorModal"
                                class="flex-1 px-5 py-3 text-sm font-medium bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-all hover:scale-[1.02]">
                            Cancelar
                        </button>
                        <button type="button" 
                                wire:click="guardarEnProformaSeleccionada"
                                class="flex-1 px-5 py-3 text-sm font-medium bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                @if(!$selectedProformaToAdd) disabled @endif>
                            ✓ Agregar a Proforma
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($showCostUpdateWarning)
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 border-2 border-amber-400 relative">
        <button type="button" wire:click="cancelarActualizarCostosYAgregar" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <div class="flex flex-col items-center">
            <svg class="w-16 h-16 text-amber-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="text-xl font-bold text-amber-700 mb-2 text-center">¡Atención! Costos desactualizados</h3>
            <p class="text-gray-700 text-center mb-4">Si agregas este producto a la proforma seleccionada, <span class="font-semibold text-amber-700">los precios de los productos existentes se actualizarán a los costos actuales</span> y la fecha de expiración se renovará.<br>¿Deseas continuar?</p>
            <div class="flex gap-4 mt-4">
                <button type="button" wire:click="cancelarActualizarCostosYAgregar" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold">Cancelar</button>
                <button type="button" wire:click="confirmarActualizarCostosYAgregar" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg font-semibold shadow-lg">Actualizar y Agregar</button>
            </div>
        </div>
    </div>
</div>
@endif
</div>

@script
<script>
    let parametricViewer = null;
    let initAttempts = 0;
    const maxAttempts = 10;

    function startViewer() {
        initParametricViewer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startViewer);
    } else {
        startViewer();
    }

    function initParametricViewer() {
        initAttempts++;

        if (typeof THREE === 'undefined') {
            updateViewerStatus('Three.js no está disponible');
            return;
        }

        if (typeof createParametricProduct3D !== 'function') {
            if (initAttempts < maxAttempts) {
                setTimeout(initParametricViewer, 1000);
                return;
            }
            updateViewerStatus('Error: Función de configurador no disponible');
            return;
        }

        const productType = '{{ $productType }}';
        const initialParams = @json($parameters);
        const colorTexturePath = '{{ $colorTexturePath }}';
        
        if (productType === 'window') {
            initialParams.depth = 1.0;
        }
        
        initialParams.texturePath = colorTexturePath;
        initialParams.frameColor = initialParams.frameColor || 0xC0C0C0;
        initialParams.glassColor = initialParams.glassColor || '#E0F6FF';

        try {
            updateViewerStatus('Generando modelo 3D...');
            parametricViewer = createParametricProduct3D(
                'parametric-3d-viewer', 
                productType, 
                initialParams
            );
        } catch (error) {
            updateViewerStatus('Error generando modelo 3D: ' + error.message);
        }
    }

    function updateViewerStatus(message) {
        const statusElement = document.querySelector('#parametric-3d-viewer .text-center p');
        if (statusElement) {
            statusElement.textContent = message;
        }
    }

    function hideLoader() {
        const loader = document.querySelector('#parametric-3d-viewer .absolute');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    Livewire.on('updateModel3D', (data) => {
        
        let parameters = null;
        if (Array.isArray(data) && data.length > 0) {
            parameters = data[0].parameters || data[0];
        } else if (data && typeof data === 'object') {
            parameters = data.parameters || data;
        }
        
        if (parametricViewer && parameters) {
            parametricViewer.updateParameters(parameters);
        }
    });

    setTimeout(() => {
        if (!parametricViewer) {
            startViewer();
        }
    }, 1000);

    window.resetParametricView = function() {
        if (parametricViewer) {
            parametricViewer.resetZoom();
        }
    };

    window.takeParametricScreenshot = function() {
        if (parametricViewer) {
            const dataURL = parametricViewer.screenshot(1200, 800);
            const link = document.createElement('a');
            link.download = '{{ Str::slug($product->name) }}_configurador_3d.png';
            link.href = dataURL;
            link.click();
        } else {
            alert('El visor 3D no está disponible');
        }
    };

</script>
@endscript
