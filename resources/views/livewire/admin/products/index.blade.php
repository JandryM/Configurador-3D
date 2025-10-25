<div>
    <!-- Encabezado de la sección -->
    <div class="fade-in mb-8">
        <div class="glass-card rounded-2xl shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Gestión de Productos</h1>
                        <p class="text-slate-600">Administra el catálogo de productos y sus configuraciones</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Nuevo Producto</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de productos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total de Productos -->
        <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalProducts }}</p>
                    <p class="text-sm text-slate-600">Total Productos</p>
                </div>
            </div>
        </div>
        <!-- Productos de Galería -->
        <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $galleryProducts }}</p>
                    <p class="text-sm text-slate-600">Galería</p>
                </div>
            </div>
        </div>
        <!-- Productos Personalizables -->
        <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $customizableProducts }}</p>
                    <p class="text-sm text-slate-600">Personalizables</p>
                </div>
            </div>
        </div>
        <!-- Productos Visibles -->
        <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $visibleProducts }}</p>
                    <p class="text-sm text-slate-600">Visibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="glass-card rounded-2xl shadow-xl">
        <div class="p-6 border-b border-slate-200/50">
            <h2 class="text-xl font-bold text-slate-800">Lista de Productos</h2>
            <p class="text-slate-600 mt-1">Todos los productos registrados en el catálogo</p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Producto</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Tipo</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Precio</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Estado</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-700">Creado</th>
                            <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover shadow-md">
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-r from-slate-300 to-slate-400 rounded-lg flex items-center justify-center shadow-md">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $product->name }}</p>
                                            <p class="text-sm text-slate-500">{{ Str::limit($product->description, 40) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($product->product_type === 'gallery')
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            Galería
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Personalizable
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($product->price)
                                        <span class="font-medium text-slate-800">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="text-slate-500">Cotizar</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($product->is_gallery_visible)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-md">
                                            Visible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-md">
                                            Oculto
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-slate-600">
                                    {{ $product->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button wire:click="toggleGalleryVisibility({{ $product->id }})" class="transition-colors"
                                            title="{{ $product->is_gallery_visible ? 'Ocultar de galería' : 'Mostrar en galería' }}">
                                            @if($product->is_gallery_visible)
                                                <!-- Icono de ojo abierto -->
                                                <svg class="w-4 h-4 text-green-600 hover:text-green-800" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <!-- Icono de ojo cerrado (con raya) -->
                                                <svg class="w-4 h-4 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.96 9.96 0 012.197-6.13M6.13 6.13l11.74 11.74M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12" />
                                                </svg>
                                            @endif
                                        </button>
                                        @if((!$product->is_gallery_visible && !$product->allows_customization) || ($product->is_gallery_visible && !$product->allows_customization))
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Editar producto">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        <button wire:click="deleteProduct({{ $product->id }})" onclick="return confirm('¿Estás seguro de eliminar este producto?')" class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar producto">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500">
                                    No hay productos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Paginación --}}
            @if($products->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
