@php
use Illuminate\Support\Facades\Storage;

$products = $this->featuredProducts;
@endphp

@if(count($products) > 0)
    @php
        $productCount = count($products);
        // Usar un grid especial para el patrón brick cuando hay 4 o 5 productos
        $needsBrickLayout = in_array($productCount, [4, 5]);
        
        if ($needsBrickLayout) {
            // Grid de 6 columnas para permitir el offset
            $gridClasses = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 max-w-6xl';
        } else {
            $gridClasses = match($productCount) {
                1 => 'flex justify-center',
                2 => 'grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl',
                3 => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl',
                6 => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl',
                default => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl'
            };
        }
        
        // Clase especial para el producto único
        $itemClasses = $productCount === 1 ? 'w-full max-w-md' : '';
    @endphp
    
    <div class="{{ $gridClasses }} mx-auto">
        @foreach($products as $index => $product)
            @php
                $gridColumnClass = '';
                
                if ($productCount === 4) {
                    // Para 4 productos: el 4to va centrado (debajo del 2do)
                    $gridColumnClass = match($index) {
                        0 => 'lg:col-span-2',           // PROD1: columnas 1-2
                        1 => 'lg:col-span-2',           // PROD2: columnas 3-4  
                        2 => 'lg:col-span-2',           // PROD3: columnas 5-6
                        3 => 'lg:col-start-3 lg:col-span-2', // PROD4: columnas 3-4 (debajo de PROD2, centrado)
                    };
                } elseif ($productCount === 5) {
                    // Patrón brick para 5 productos
                    $gridColumnClass = match($index) {
                        0 => 'lg:col-span-2',           // PROD1: columnas 1-2
                        1 => 'lg:col-span-2',           // PROD2: columnas 3-4
                        2 => 'lg:col-span-2',           // PROD3: columnas 5-6
                        3 => 'lg:col-start-2 lg:col-span-2', // PROD4: columnas 2-3 (entre PROD1 y PROD2)
                        4 => 'lg:col-start-4 lg:col-span-2', // PROD5: columnas 4-5 (entre PROD2 y PROD3)
                    };
                }
            @endphp
            
            <div class="group relative overflow-hidden rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 {{ $itemClasses }} {{ $gridColumnClass }}">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                    <!-- Imagen por defecto si el producto no tiene imagen -->
                    <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="absolute bottom-4 left-4 right-4 text-white">
                        <h4 class="font-semibold text-lg">{{ $product->name }}</h4>
                        <p class="text-sm opacity-90 mb-2">{{ Str::limit($product->description, $productCount === 1 ? 50 : 30) }}</p>
                        @if($product->category)
                            <span class="text-xs bg-white/20 px-2 py-1 rounded-full inline-block">{{ $product->category }}</span>
                        @endif
                        @if($product->product_type === 'gallery' && $product->price)
                            <div class="mt-2">
                                <span class="text-sm font-semibold bg-green-500/80 px-2 py-1 rounded-full">${{ number_format($product->price, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Mensaje cuando no hay productos -->
    <div class="text-center py-16">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Próximamente</h3>
        <p class="text-gray-500 dark:text-gray-400">Estamos preparando increíbles proyectos para mostrar aquí.</p>
    </div>
@endif