<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Título -->
        <div class="mb-8">
            <div class="flex items-start gap-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Configurador 3D - {{ $product->name }}
                    </h1>
                    <p class="text-gray-600 mt-2">Personaliza tu producto y ve los cambios en tiempo real</p>
                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                            {{ $product->category?->name ?? 'Sin categoría' }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800">
                            ✨ Generado 100% con JavaScript
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        @if (session()->has('message'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Visor 3D -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información del Producto -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $product->name }}</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($product->description, 120) }}</p>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-lg font-bold text-green-600">${{ number_format($calculatedPrice, 2) }}</span>
                                <span class="text-sm text-gray-500">precio calculado por materiales</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-purple-100 text-purple-800 text-xs">
                                    🎯 Modelo 3D Generativo
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs">
                                    🚀 Tiempo Real
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Configurador 3D -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vista 3D Interactiva</h2>
                    
                    <!-- Contenedor del modelo 3D -->
                    <div wire:ignore id="parametric-3d-viewer" class="w-full h-96 bg-gray-900 rounded-lg border border-gray-300 relative overflow-hidden">
                        <!-- Canvas de Three.js se insertará aquí -->
                    </div>

                    <!-- Controles del visor 3D optimizados -->
                    <div class="mt-3 flex justify-center space-x-3">
                        <button type="button" 
                                onclick="resetParametricView()"
                                class="px-3 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors"
                                title="Resetear vista">
                            🔄 Resetear
                        </button>
                        <button type="button" 
                                onclick="takeParametricScreenshot()"
                                class="px-3 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors"
                                title="Tomar captura">
                            📸 Captura
                        </button>
                        <button type="button" 
                                onclick="toggleParametricInfo()"
                                class="px-3 py-2 text-sm bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors"
                                title="Información del modelo">
                            ℹ️ Info
                        </button>
                    </div>

                    <!-- Información del producto -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Ancho:</span>
                                <span class="text-gray-600">{{ number_format($parameters['width'], 2) }}m</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Alto:</span>
                                <span class="text-gray-600">{{ number_format($parameters['height'], 2) }}m</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Área:</span>
                                <span class="text-gray-600">{{ number_format($parameters['width'] * $parameters['height'], 2) }}m²</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Precio:</span>
                                <span class="text-green-600 font-bold">${{ number_format($calculatedPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Panel de controles -->
            <div class="space-y-6">
                <!-- Dimensiones -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dimensiones</h3>
                    
                    @foreach(['width' => 'Ancho', 'height' => 'Alto', 'depth' => 'Profundidad'] as $param => $label)
                        @if($param === 'depth' && $productType === 'window')
                            @continue {{-- Omitir profundidad para ventanas --}}
                        @endif
                        @if(isset($parameterLimits[$param]))
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $label }}: {{ number_format($parameters[$param], 3) }}m
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="range" 
                                           wire:model.live="parameters.{{ $param }}"
                                           wire:change="updateParameter('{{ $param }}', $event.target.value)"
                                           min="{{ $parameterLimits[$param]['min'] }}"
                                           max="{{ $parameterLimits[$param]['max'] }}"
                                           step="{{ $parameterLimits[$param]['step'] }}"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                    <input type="number"
                                           wire:model.lazy="parameters.{{ $param }}"
                                           wire:change="updateParameter('{{ $param }}', $event.target.value)"
                                           min="{{ $parameterLimits[$param]['min'] }}"
                                           max="{{ $parameterLimits[$param]['max'] }}"
                                           step="{{ $parameterLimits[$param]['step'] }}"
                                           class="w-24 ml-2 px-2 py-1 border border-gray-300 rounded text-sm focus:ring focus:ring-blue-200"
                                           placeholder="{{ $label }}">
                                    <span class="ml-1 text-xs text-gray-500">m</span>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $parameterLimits[$param]['min'] }}m</span>
                                    <span>{{ $parameterLimits[$param]['max'] }}m</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Colores -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Colores Disponibles</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color del Aluminio</label>
                            
                            <!-- Selector de colores -->
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
                                                      peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            
                                            <!-- Muestra de color visual -->
                                            <div class="w-12 h-12 rounded-md border-2 border-gray-200 mr-4 shadow-inner
                                                        @if($colorName === 'Natural') bg-gradient-to-br from-gray-200 to-gray-300
                                                        @elseif($colorName === 'White') bg-gradient-to-br from-white to-gray-100
                                                        @elseif($colorName === 'Black Anodized') bg-gradient-to-br from-gray-800 to-black
                                                        @elseif($colorName === 'Woody') bg-gradient-to-br from-yellow-100 to-yellow-200
                                                        @elseif($colorName === 'Bronze') bg-gradient-to-br from-yellow-600 to-yellow-800
                                                        @else bg-gradient-to-br from-gray-300 to-gray-400 @endif">
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $color->color_name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    Aluminio {{ strtolower($color->color_name) }}
                                                </div>
                                                <div class="text-sm font-medium 
                                                            @if($color->percentage_increment == 0) text-green-600 
                                                            @else text-orange-600 @endif">
                                                    @if($color->percentage_increment == 0)
                                                        ✅ Sin incremento
                                                    @else
                                                        💰 +{{ number_format($color->percentage_increment, 1) }}%
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Indicador de selección -->
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
                            
                            <!-- Información del color seleccionado -->
                            @if($selectedColor)
                            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h4 class="text-sm font-semibold text-blue-900">
                                            Color seleccionado: {{ $selectedColor->color_name }}
                                        </h4>
                                        <div class="mt-1 text-sm text-blue-700">
                                            <div>📁 Ruta texturas: <code class="bg-white px-1 py-0.5 rounded text-xs">{{ $colorTexturePath }}</code></div>
                                            <div class="mt-1">💰 Incremento de precio: 
                                                <span class="font-medium">
                                                    @if($selectedColor->percentage_increment == 0)
                                                        <span class="text-green-600">Sin costo adicional</span>
                                                    @else
                                                        <span class="text-orange-600">+{{ number_format($selectedColor->percentage_increment, 1) }}%</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- Selector de color de vidrio -->
                        <div class="mt-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color del Vidrio</label>
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
                                                      peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <div class="w-12 h-12 rounded-md border-2 border-gray-200 mr-4 shadow-inner bg-gradient-to-br from-blue-100 to-blue-300"></div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $color->color_name }}</div>
                                                <div class="text-sm text-gray-500">Vidrio {{ strtolower($color->color_name) }}</div>
                                                <div class="text-sm font-medium @if($color->percentage_increment == 0) text-green-600 @else text-orange-600 @endif">
                                                    @if($color->percentage_increment == 0)
                                                        ✅ Sin incremento
                                                    @else
                                                        💰 +{{ number_format($color->percentage_increment, 1) }}%
                                                    @endif
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
                    </div>
                </div>

                <!-- Precio y acciones -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Precio Estimado</h3>
                    
                    <div class="text-3xl font-bold text-green-600 mb-4">
                        ${{ number_format($calculatedPrice, 2) }}
                    </div>

                    <div class="space-y-3">
                        @if(Auth::check())
                            <button type="button"
                                    wire:click="$set('showProformaModal', true)"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Visualizar Proforma
                            </button>
                        @else
                            <div class="w-full bg-yellow-100 text-yellow-800 font-medium py-2 px-4 rounded-lg text-center">
                                Debes iniciar sesión para visualizar y guardar la proforma.
                            </div>
                        @endif
                    </div>

<!-- Modal de Proforma -->
@if($showProformaModal ?? false)
    @if(Auth::check())
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full p-6 relative">
                <button type="button" wire:click="$set('showProformaModal', false)" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-center">Proforma de Producto</h2>
                @if (session()->has('message'))
                    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="fixed inset-0 z-60 flex items-center justify-center bg-black bg-opacity-40">
                        <div class="bg-white rounded-lg shadow-2xl max-w-sm w-full p-6 relative border-2 border-green-500">
                            <button type="button" @click="show = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-green-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <h3 class="text-xl font-bold text-green-700 mb-2">¡Proforma guardada!</h3>
                                <p class="text-green-800 text-center">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="overflow-y-auto max-h-[70vh]">
                    @include('proforma', [
                        'product' => $product,
                        'parameters' => $parameters,
                        'materialCosts' => $materialCosts,
                        'calculatedPrice' => $calculatedPrice,
                        'notes' => $parameters['notes'] ?? null,
                        'directCost' => $directCost ?? null,
                        'indirectCost' => $indirectCost ?? null
                    ])
                </div>
                <div class="mt-4 flex flex-col sm:flex-row justify-end gap-2">
                    <button type="button" wire:click="guardarProforma" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">Guardar Proforma</button>
                    <button type="button" wire:click="orderProforma" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-semibold">Ordenar Proforma</button>
                    @if(session()->has('message'))
                        <button type="button" wire:click="downloadProformaPdf" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-8 relative flex flex-col items-center">
                <button type="button" wire:click="$set('showProformaModal', false)" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                <svg class="w-16 h-16 text-yellow-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <h3 class="text-xl font-bold text-yellow-700 mb-2">Acceso restringido</h3>
                <p class="text-yellow-800 text-center">Debes iniciar sesión para visualizar, guardar o descargar la proforma.</p>
            </div>
        </div>
    @endif
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let parametricViewer = null;
    let initAttempts = 0;
    const maxAttempts = 10;

    // Función de inicialización inmediata
    function startViewer() {
        initParametricViewer();
    }

    // Ejecutar inmediatamente si el DOM ya está listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startViewer);
    } else {
        startViewer();
    }

    function initParametricViewer() {
        initAttempts++;

        // Verificar si Three.js está disponible
        if (typeof THREE === 'undefined') {
            console.error('❌ Three.js no está cargado');
            updateViewerStatus('Three.js no está disponible');
            return;
        }

        // Verificar si nuestra función está disponible
        if (typeof createParametricProduct3D !== 'function') {
            console.error('❌ createParametricProduct3D function not loaded');
            
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
            initialParams.depth = 1.0; // Profundidad fija para ventanas
        }
        
        // Agregar información de texturas y color
        initialParams.texturePath = colorTexturePath;
        initialParams.frameColor = initialParams.frameColor || 0xC0C0C0; // Plata claro como fallback
        initialParams.glassColor = initialParams.glassColor || '#E0F6FF';

        try {
            updateViewerStatus('Generando modelo 3D...');
            
            // Verificar el contenedor
            const container = document.getElementById('parametric-3d-viewer');
            
            parametricViewer = createParametricProduct3D(
                'parametric-3d-viewer', 
                productType, 
                initialParams
            );
            

            
        } catch (error) {
            console.error('❌ Error inicializando configurador 3D:', error);
            console.error('📋 Stack trace:', error.stack);
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

    // Escuchar eventos de Livewire para actualizar modelo
    Livewire.on('updateModel3D', (data) => {
        
        // Manejar diferentes estructuras de datos de Livewire
        let parameters = null;
        if (Array.isArray(data) && data.length > 0) {
            parameters = data[0].parameters || data[0];
        } else if (data && typeof data === 'object') {
            parameters = data.parameters || data;
        }
        
        if (parametricViewer && parameters) {
            parametricViewer.updateParameters(parameters);
        } else {
            if (!parametricViewer) {
                console.warn('⚠️ Visor 3D no está inicializado');
            }
            if (!parameters) {
                console.warn('⚠️ No se pudieron extraer los parámetros del evento');
            }
        }
    });

    // Respaldo: Intentar inicializar después de un delay
    setTimeout(() => {
        if (!parametricViewer) {
            startViewer();
        }
    }, 1000);

    // Funciones globales para controles del visor 3D optimizadas
    window.resetParametricView = function() {
        if (parametricViewer) {
            parametricViewer.resetZoom();
        } else {
            console.warn('⚠️ Visor 3D no disponible para reset');
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

    window.toggleParametricInfo = function() {
        if (parametricViewer) {
            const config = parametricViewer.getConfiguration();
            const info = `
🎯 INFORMACIÓN DEL MODELO 3D
━━━━━━━━━━━━━━━━━━━━━━━━━
📦 Producto: {{ $product->name }}
🏷️ Tipo: {{ $productType }}
📏 Dimensiones: ${config.parameters.width}m × ${config.parameters.height}m${productType !== 'window' ? ' × ' + config.parameters.depth + 'm' : ''}
🎨 Color: ${config.parameters.color}
💰 Precio: ${{ number_format($calculatedPrice, 2) }}
⏰ Generado: ${new Date(config.timestamp).toLocaleString()}
━━━━━━━━━━━━━━━━━━━━━━━━━
✨ Sistema: Generación paramétrica 3D
🚀 Motor: Three.js con WebGL
            `;
            alert(info);
        } else {
            alert('El visor 3D no está disponible');
        }
    };
</script>
@endscript
