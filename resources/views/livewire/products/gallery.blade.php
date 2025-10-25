@php
use Illuminate\Support\Facades\Storage;
@endphp

<div>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-slate-600 via-blue-700 to-cyan-800 py-24">
        <div class="container mx-auto px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100/20 backdrop-blur-md rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-slate-100" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4h7V2H4c-1.1 0-2 .9-2 2v7h2V4zm6 9l-4 5h12l-3-4-2.03 2.71L10 13zm7-4.5c0-.83-.67-1.5-1.5-1.5S14 7.67 14 8.5s.67 1.5 1.5 1.5S17 9.33 17 8.5zM20 2h-7v2h7v7h2V4c0-1.1-.9-2-2-2zm0 18h-7v2h7c1.1 0 2-.9 2-2v-7h-2v7zM4 13H2v7c0 1.1.9 2 2 2h7v-2H4v-7z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold mb-6 text-slate-50 leading-tight">
                    Galería de <span class="bg-gradient-to-r from-cyan-300 to-blue-300 bg-clip-text text-transparent">Proyectos</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-200 mb-8 leading-relaxed">
                    Descubre nuestros trabajos especializados en aluminio y vidrio
                </p>
                <div class="flex justify-center space-x-8 text-slate-300">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-cyan-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Ventanas</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-cyan-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Portones</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-cyan-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Mallas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Content -->
    <div class="bg-gradient-to-br from-slate-50 via-cyan-50 to-blue-50 min-h-screen">
        <div class="container mx-auto px-6 py-20">
            <!-- Filtros -->
            <div class="mb-16 flex justify-center">
                <div class="max-w-md w-full">
                    <div class="relative">
                        <select wire:model.live="selectedCategory" 
                                class="w-full px-6 py-4 rounded-2xl border border-slate-300/50
                                       bg-slate-50/80 backdrop-blur-sm text-slate-800
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-400
                                       shadow-lg transition-all duration-300 text-lg appearance-none
                                       hover:shadow-xl">
                            @foreach($this->categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
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
    @if($this->showModal && $this->selectedProduct)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
        
        <!-- Modal Content -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative bg-slate-50/95 backdrop-blur-md rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto border border-slate-200/50">
                
                <!-- Botón cerrar -->
                <button wire:click="closeModal" 
                        class="absolute top-6 right-6 z-10 bg-slate-800/80 hover:bg-slate-900 text-slate-100 rounded-full p-3 transition-all duration-300 backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <!-- Imagen -->
                    <div class="relative group">
                        @if($this->selectedProduct->image)
                            <img src="{{ Storage::url($this->selectedProduct->image) }}" 
                                 alt="{{ $this->selectedProduct->name }}" 
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
                        <h2 class="text-3xl font-bold text-slate-800 mb-4">
                            {{ $this->selectedProduct->name }}
                        </h2>

                        <div class="mb-6">
                            <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-4 py-2 rounded-full text-sm font-medium">
                                {{ $this->selectedProduct->category?->name ?? 'Sin categoría' }}
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-3">Descripción del Proyecto</h3>
                            <p class="text-slate-600 leading-relaxed">
                                {{ $this->selectedProduct->description }}
                            </p>
                        </div>

                        @if($this->selectedProduct->product_type === 'gallery' && $this->selectedProduct->price)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-slate-800 mb-2">Precio de Referencia</h3>
                                <div class="text-4xl font-bold text-emerald-600">
                                    ${{ number_format($this->selectedProduct->price, 2) }}
                                </div>
                                <p class="text-sm text-slate-500 mt-1">*Precio puede variar según especificaciones</p>
                            </div>
                        @endif

                        @if($this->selectedProduct->product_type === 'customizable')
                            <div class="mb-8">
                                <div class="bg-cyan-50/70 backdrop-blur-sm border border-cyan-200/50 rounded-2xl p-6">
                                    <div class="flex items-start">
                                        <div class="bg-cyan-500/20 rounded-full p-2 mr-4">
                                            <svg class="w-6 h-6 text-cyan-700" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-semibold text-cyan-800 mb-2">
                                                Proyecto Personalizable
                                            </h4>
                                            <p class="text-cyan-700 leading-relaxed">
                                                El precio se calcula según las dimensiones, materiales y acabados seleccionados para tu proyecto específico. Solicita una cotización gratuita.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button wire:click="closeModal" 
                                    class="flex-1 bg-slate-100/80 hover:bg-slate-200/80 text-slate-700 px-6 py-3 rounded-2xl font-semibold transition-all duration-300 backdrop-blur-sm border border-slate-300/50">
                                Cerrar
                            </button>
                            @if($selectedProduct->product_type === 'customizable')
                                <button class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-slate-50 px-6 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Solicitar Cotización
                                </button>
                            @else
                                <button class="flex-1 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-slate-50 px-6 py-3 rounded-2xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
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
    @if($this->showImageZoom && $this->selectedProduct && $this->selectedProduct->image)
    @php
        // Obtener dimensiones de la imagen
    $fullPath = storage_path('app/public/' . $this->selectedProduct->image);
        $aspectRatio = 1;
        $maxWidth = '90vw';
        $maxHeight = '90vh';
        
        if (file_exists($fullPath)) {
            $imageInfo = getimagesize($fullPath);
            if ($imageInfo) {
                $aspectRatio = $imageInfo[0] / $imageInfo[1]; // width/height
                // Ajustar dimensiones según aspecto
                if ($aspectRatio > 2) {
                    $maxWidth = '95vw';
                    $maxHeight = '70vh';
                } elseif ($aspectRatio < 0.5) {
                    $maxWidth = '70vw';
                    $maxHeight = '95vh';
                }
            }
        }
    @endphp
    
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900/95 backdrop-blur-md transition-opacity" wire:click="closeImageZoom"></div>
        
        <!-- Contenedor de imagen -->
        <div class="relative" style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }};">
            <!-- Botón cerrar -->
            <button wire:click="closeImageZoom" 
                    class="absolute -top-16 -right-2 bg-slate-800/80 hover:bg-slate-900 text-slate-100 rounded-full p-3 transition-all duration-300 backdrop-blur-sm z-10 shadow-xl border border-slate-700/50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <!-- Imagen -->
          <img src="{{ Storage::url($this->selectedProduct->image) }}" 
              alt="{{ $this->selectedProduct->name }}" 
              class="w-full h-full object-contain rounded-2xl shadow-2xl border border-slate-300/20">
            
            <!-- Info de la imagen -->
            <div class="absolute -bottom-16 left-0 right-0 text-center">
                <div class="bg-black/60 backdrop-blur-sm rounded-lg px-4 py-2 inline-block">
                    <h3 class="text-white font-bold">{{ $this->selectedProduct->name }}</h3>
                    <p class="text-white/80 text-sm">{{ $this->selectedProduct->category?->name ?? 'Sin categoría' }}</p>
                </div>
            </div>
            
            <!-- Botón descarga -->
            <div class="absolute -top-16 left-0">
                <a href="{{ Storage::url($this->selectedProduct->image) }}" 
                   download="{{ $this->selectedProduct->name }}.jpg"
                   class="bg-blue-600/80 hover:bg-blue-700 text-slate-100 rounded-full p-3 transition-all duration-300 backdrop-blur-sm inline-block shadow-xl border border-blue-500/50"
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