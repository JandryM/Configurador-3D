@php
use Illuminate\Support\Facades\Storage;
@endphp

<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 py-20">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent">
            Galería de Proyectos
        </h1>
        <p class="text-xl text-gray-200 mb-8">
            Descubre todos nuestros trabajos en aluminio, vidrio, melamina, gypsum y cielo raso
        </p>
        </div>
    </div>

    <!-- Gallery Content -->
    <div class="container mx-auto px-6 py-16">
    <!-- Filtros -->
    <div class="mb-12 flex justify-center">
        <div class="max-w-md w-full">
            <select wire:model.live="selectedCategory" 
                    class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 focus:border-transparent
                           shadow-lg transition-all duration-300 text-lg">
                @foreach($categories as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($this->products as $product)
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer"
                 wire:click="openModal({{ $product->id }})">
                
                <!-- Imagen -->
                <div class="relative h-64 overflow-hidden">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <h3 class="text-lg font-bold mb-2">{{ $product->name }}</h3>
                            <p class="text-sm opacity-90 mb-3">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                                    {{ $product->category?->name ?? 'Sin categoría' }}
                                </span>
                                @if($product->product_type === 'gallery' && $product->price)
                                    <span class="text-sm font-bold bg-green-500/80 backdrop-blur-sm px-3 py-1 rounded-full">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Botón Ver Detalles -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-4">No hay productos disponibles</h3>
                <p class="text-gray-500 dark:text-gray-400 text-lg">
                    @if($selectedCategory)
                        No hay productos en la categoría "{{ $selectedCategory }}".
                        <button wire:click="$set('selectedCategory', '')" class="text-blue-600 hover:text-blue-800 underline font-semibold">
                            Ver todos los productos
                        </button>
                    @else
                        Pronto agregaremos increíbles proyectos para mostrar aquí.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
    
    <!-- Contador de productos -->
    @if(count($this->products) > 0)
        <div class="text-center mt-16">
            <div class="inline-block bg-gray-100 dark:bg-gray-800 rounded-full px-6 py-3">
                <p class="text-gray-600 dark:text-gray-400 font-medium">
                    Mostrando {{ count($this->products) }} producto(s)
                    @if($selectedCategory)
                        en "{{ $selectedCategory }}"
                    @endif
                </p>
            </div>
        </div>
    @endif
    </div>

    <!-- Modal -->
    @if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/75 transition-opacity" wire:click="closeModal"></div>
        
        <!-- Modal Content -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                
                <!-- Botón cerrar -->
                <button wire:click="closeModal" 
                        class="absolute top-4 right-4 z-10 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Imagen -->
                    <div class="relative group">
                        @if($selectedProduct->image)
                            <img src="{{ Storage::url($selectedProduct->image) }}" 
                                 alt="{{ $selectedProduct->name }}" 
                                 class="w-full h-64 lg:h-96 object-cover rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none cursor-pointer"
                                 wire:click="openImageZoom">
                            
                            <!-- Botón de zoom -->
                            <button wire:click="openImageZoom" 
                                    class="absolute top-4 left-4 bg-black/50 hover:bg-black/70 text-white rounded-full p-2 transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </button>
                        @else
                            <div class="w-full h-64 lg:h-96 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Información -->
                    <div class="p-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $selectedProduct->name }}
                        </h2>

                        <div class="mb-6">
                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-4 py-2 rounded-full text-sm font-medium">
                                {{ $selectedProduct->category?->name ?? 'Sin categoría' }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Descripción</h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ $selectedProduct->description }}
                            </p>
                        </div>

                        @if($selectedProduct->product_type === 'gallery' && $selectedProduct->price)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Precio</h3>
                                <div class="text-4xl font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($selectedProduct->price, 2) }}
                                </div>
                            </div>
                        @endif

                        @if($selectedProduct->product_type === 'customizable')
                            <div class="mb-8">
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">
                                                Producto Personalizable
                                            </h4>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                El precio se calcula según las dimensiones y materiales seleccionados para tu proyecto específico.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button wire:click="closeModal" 
                                    class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-6 py-3 rounded-xl font-semibold transition-colors">
                                Cerrar
                            </button>
                            @if($selectedProduct->product_type === 'customizable')
                                <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                                    Solicitar Cotización
                                </button>
                            @else
                                <button class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all transform hover:scale-105">
                                    Contactar Ahora
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Imagen Zoom -->
    @if($showImageZoom && $selectedProduct && $selectedProduct->image)
    @php
        // Obtener dimensiones de la imagen
        $fullPath = storage_path('app/public/' . $selectedProduct->image);
        $aspectRatio = 1;
        $maxWidth = '90vw';
        $maxHeight = '90vh';
        
        if (file_exists($fullPath)) {
            $imageInfo = getimagesize($fullPath);
            if ($imageInfo) {
                $aspectRatio = $imageInfo[0] / $imageInfo[1]; // width/height
                
                // Ajustar dimensiones según aspecto
                if ($aspectRatio > 2) {
                    // Imagen muy horizontal
                    $maxWidth = '95vw';
                    $maxHeight = '70vh';
                } elseif ($aspectRatio < 0.5) {
                    // Imagen muy vertical
                    $maxWidth = '70vw';
                    $maxHeight = '95vh';
                }
            }
        }
    @endphp
    
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/95 transition-opacity" wire:click="closeImageZoom"></div>
        
        <!-- Contenedor de imagen -->
        <div class="relative" style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }};">
            <!-- Botón cerrar -->
            <button wire:click="closeImageZoom" 
                    class="absolute -top-12 -right-2 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-colors backdrop-blur-sm z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Imagen -->
            <img src="{{ Storage::url($selectedProduct->image) }}" 
                 alt="{{ $selectedProduct->name }}" 
                 class="w-full h-full object-contain rounded-lg shadow-2xl">
            
            <!-- Info de la imagen -->
            <div class="absolute -bottom-16 left-0 right-0 text-center">
                <div class="bg-black/60 backdrop-blur-sm rounded-lg px-4 py-2 inline-block">
                    <h3 class="text-white font-bold">{{ $selectedProduct->name }}</h3>
                    <p class="text-white/80 text-sm">{{ $selectedProduct->category?->name ?? 'Sin categoría' }}</p>
                </div>
            </div>
            
            <!-- Botón descarga -->
            <div class="absolute -top-12 left-0">
                <a href="{{ Storage::url($selectedProduct->image) }}" 
                   download="{{ $selectedProduct->name }}.jpg"
                   class="bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-colors backdrop-blur-sm inline-block"
                   title="Descargar imagen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>