<div class="space-y-6">
    <!-- Título -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Modelo 3D - {{ $product->name }}</h2>
        @if($product->has_3d_model)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18.5a8.5 8.5 0 100-17 8.5 8.5 0 000 17zm3.707-9.293a1 1 0 00-1.414-1.414L9 11.586 7.707 10.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Modelo 3D Activo
            </span>
        @endif
    </div>

    <!-- Mensajes -->
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 11.586 7.707 10.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Información sobre formatos soportados -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-900 mb-2">Formatos Soportados</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            @foreach($supportedFormats as $ext => $description)
                <div class="flex items-center">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                        .{{ strtoupper($ext) }}
                    </span>
                    <span class="text-blue-700">{{ $description }}</span>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-blue-600 mt-2">
            <strong>Límites:</strong> Modelo 3D máximo 50MB, texturas máximo 10MB cada una.
        </p>
    </div>

    <!-- Visor 3D (si existe modelo) -->
    @if($product->model_3d_file && $modelFileInfo)
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Vista Previa del Modelo</h3>
                <div class="flex space-x-2">
                    <button type="button" 
                            onclick="toggle3DControls()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Controles
                    </button>
                    <button type="button" 
                            onclick="screenshot3D()" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Captura
                    </button>
                </div>
            </div>
            
            <!-- Contenedor del visor 3D -->
            <div id="model3d-viewer" class="w-full h-96 bg-gray-100 rounded-lg border border-gray-300 relative overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Cargando modelo 3D...</p>
                    </div>
                </div>
            </div>

            <!-- Información del archivo -->
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Archivo:</span>
                        <p class="text-gray-600">{{ $modelFileInfo['name'] }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Tamaño:</span>
                        <p class="text-gray-600">{{ number_format($modelFileInfo['size'] / 1024 / 1024, 2) }} MB</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Formato:</span>
                        <p class="text-gray-600">{{ strtoupper($modelFileInfo['extension']) }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Escala:</span>
                        <p class="text-gray-600">{{ $model_scale }}x</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración del Visor -->
        <div class="bg-white border border-gray-200 rounded-lg p-6" id="viewer-settings" style="display: none;">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración del Visor</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Color de Fondo -->
                <div>
                    <label for="backgroundColor" class="block text-sm font-medium text-gray-700 mb-2">
                        Color de Fondo
                    </label>
                    <input wire:model.live="viewerSettings.backgroundColor" 
                           type="color" 
                           id="backgroundColor"
                           class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer">
                </div>

                <!-- Intensidad Luz Ambiental -->
                <div>
                    <label for="ambientLight" class="block text-sm font-medium text-gray-700 mb-2">
                        Intensidad Luz Ambiental: {{ $viewerSettings['ambientLightIntensity'] }}
                    </label>
                    <input wire:model.live="viewerSettings.ambientLightIntensity" 
                           type="range" 
                           id="ambientLight"
                           min="0" 
                           max="2" 
                           step="0.1"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                </div>

                <!-- Intensidad Luz Direccional -->
                <div>
                    <label for="directionalLight" class="block text-sm font-medium text-gray-700 mb-2">
                        Intensidad Luz Direccional: {{ $viewerSettings['directionalLightIntensity'] }}
                    </label>
                    <input wire:model.live="viewerSettings.directionalLightIntensity" 
                           type="range" 
                           id="directionalLight"
                           min="0" 
                           max="2" 
                           step="0.1"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                </div>

                <!-- Escala del Modelo -->
                <div>
                    <label for="modelScale" class="block text-sm font-medium text-gray-700 mb-2">
                        Escala del Modelo
                    </label>
                    <input wire:model.live="model_scale" 
                           type="number" 
                           id="modelScale"
                           step="0.1" 
                           min="0.1" 
                           max="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Controles Booleanos -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center">
                    <input wire:model.live="viewerSettings.enableControls" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Controles</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="viewerSettings.showWireframe" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Wireframe</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="viewerSettings.enableShadows" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Sombras</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="viewerSettings.showGrid" 
                           type="checkbox" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Grilla</span>
                </label>
            </div>
        </div>
    @endif

    <!-- Formulario de carga -->
    <form wire:submit="save3DModel" class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ $product->model_3d_file ? 'Actualizar Modelo 3D' : 'Cargar Modelo 3D' }}
        </h3>

        <!-- Archivo del Modelo -->
        <div class="mb-6">
            <label for="model3DFile" class="block text-sm font-medium text-gray-700 mb-2">
                Archivo del Modelo 3D
            </label>
            <input wire:model="model3DFile" 
                   type="file" 
                   id="model3DFile"
                   accept=".glb,.gltf,.obj"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('model3DFile') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Texturas -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700">
                    Texturas Adicionales (Opcional)
                </label>
                <button type="button" 
                        wire:click="addTextureSlot"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Textura
                </button>
            </div>

            <!-- Texturas existentes -->
            @if($product->model_3d_textures)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Texturas actuales:</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($product->model_3d_textures as $index => $texture)
                            <div class="relative group">
                                <img src="{{ Storage::url($texture) }}" 
                                     alt="Textura {{ $index + 1 }}" 
                                     class="w-full h-20 object-cover rounded-lg border border-gray-300">
                                <button type="button" 
                                        wire:click="removeTexture({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Nuevas texturas -->
            @foreach($textureFiles as $index => $textureFile)
                <div class="flex items-center space-x-4 mb-3">
                    <div class="flex-1">
                        <input wire:model="textureFiles.{{ $index }}" 
                               type="file" 
                               accept=".jpg,.jpeg,.png,.webp"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    </div>
                    <button type="button" 
                            wire:click="removeTextureSlot({{ $index }})"
                            class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                @error('textureFiles.' . $index) 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            @endforeach
        </div>

        <!-- Configuración Básica -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="scale" class="block text-sm font-medium text-gray-700 mb-2">
                    Escala del Modelo
                </label>
                <input wire:model="model_scale" 
                       type="number" 
                       id="scale"
                       step="0.1" 
                       min="0.1" 
                       max="1000"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('model_scale') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-between">
            <div>
                @if($product->model_3d_file)
                    <button type="button" 
                            wire:click="remove3DModel"
                            onclick="return confirm('¿Estás seguro de que quieres eliminar el modelo 3D? Esta acción no se puede deshacer.')"
                            class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Eliminar Modelo
                    </button>
                @endif
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    {{ $product->model_3d_file ? 'Actualizar' : 'Guardar' }} Modelo
                </button>
            </div>
        </div>
    </form>
</div>

@script
<script>
    let parametricViewer = null;

    // Inicializar configurador 3D paramétrico
    document.addEventListener('DOMContentLoaded', function() {
        initParametricViewer();
    });

    function initParametricViewer() {
        // Verificar que el sistema paramétrico esté disponible
        if (typeof createParametricProduct3D !== 'function') {
            console.error('Sistema paramétrico no disponible - usando vista estática');
            showStaticPreview();
            return;
        }

        // Determinar tipo de producto basado en categoría
        const productType = '{{ strtolower($product->category?->name ?? 'window') }}' || 'window';
        const supportedTypes = ['window', 'door', 'furniture'];
        const finalType = supportedTypes.includes(productType) ? productType : 'window';

        // Parámetros iniciales del producto
        const initialParams = {
            width: 1.2,
            height: 1.5,
            depth: 1.0, // Profundidad fija
            frameWidth: 0.05,
            color: '#8B4513',
            frameColor: '#8B4513',
            glassColor: '#87CEEB'
        };

        try {
            parametricViewer = createParametricProduct3D(
                'model3d-viewer', 
                finalType, 
                initialParams
            );
            

            
        } catch (error) {
            console.error('❌ Error inicializando configurador paramétrico:', error);
            showStaticPreview();
        }
    }

    function showStaticPreview() {
        const container = document.getElementById('model3d-viewer');
        if (container) {
            container.innerHTML = 
                '<div class="flex items-center justify-center h-full bg-gray-100 text-gray-600">' +
                '<div class="text-center">' +
                '<svg class="mx-auto h-16 w-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>' +
                '</svg>' +
                '<h3 class="text-lg font-medium mb-2">Modelo 3D Paramétrico</h3>' +
                '<p class="text-sm">Producto: {{ $product->name }}</p>' +
                '<p class="text-sm">Categoría: {{ $product->category?->name ?? 'Sin categoría' }}</p>' +
                '</div>' +
                '</div>';
        }
    }

    // Actualizar configuraciones en tiempo real
    Livewire.on('model3DUpdated', () => {
        setTimeout(() => {
            if (parametricViewer) {
                parametricViewer.dispose();
            }
            initParametricViewer();
        }, 100);
    });

    // Escuchar cambios en configuraciones del sistema paramétrico
    $wire.on('$refresh', () => {
        if (parametricViewer) {
            // Regenerar el modelo con nuevos parámetros si es necesario
            parametricViewer.generateProduct();
        }
    });

    // Funciones de control para el sistema paramétrico
    window.resetParametricView = function() {
        if (parametricViewer) {
            parametricViewer.resetZoom();
        }
    };

    window.takeParametricScreenshot = function() {
        if (parametricViewer) {
            const dataURL = parametricViewer.screenshot(800, 600);
            const link = document.createElement('a');
            link.download = '{{ Str::slug($product->name) }}_parametric_screenshot.png';
            link.href = dataURL;
            link.click();
        }
    };

    function toggle3DControls() {
        const settingsPanel = document.getElementById('viewer-settings');
        if (settingsPanel.style.display === 'none') {
            settingsPanel.style.display = 'block';
        } else {
            settingsPanel.style.display = 'none';
        }
    }

    function screenshot3D() {
        if (parametricViewer) {
            const dataURL = parametricViewer.screenshot(800, 600);
            const link = document.createElement('a');
            link.download = '{{ Str::slug($product->name) }}_3d_screenshot.png';
            link.href = dataURL;
            link.click();
        }
    }

    // Cleanup al salir
    window.addEventListener('beforeunload', function() {
        if (parametricViewer) {
            parametricViewer.dispose();
        }
    });
</script>
@endscript
