<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Productos</h1>
        <a href="{{ route('admin.products.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Crear Producto
        </a>
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Búsqueda --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Nombre o descripción..."
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Tipo de Producto --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select wire:model.live="productType" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos los tipos</option>
                    <option value="gallery">Galería (Precio Fijo)</option>
                    <option value="customizable">Personalizable (Costeo)</option>
                </select>
            </div>

            {{-- Categoría --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                <select wire:model.live="category" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Visibilidad en galería --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visibilidad</label>
                <select wire:model.live="galleryVisible" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="1">Visible en galería</option>
                    <option value="0">Oculto de galería</option>
                </select>
            </div>

            {{-- Elementos por página --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Por página</label>
                <select wire:model.live="perPage" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    {{-- Tabla de productos --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Producto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Precio/Costo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($product->image)
                                        <img class="h-12 w-12 rounded-lg object-cover mr-4" 
                                             src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 dark:bg-gray-600 rounded-lg mr-4 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($product->description, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->product_type === 'gallery')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-store mr-1"></i> Galería
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-tools mr-1"></i> Personalizable
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($product->product_type === 'gallery')
                                    <div class="flex items-center">
                                        <span class="text-green-600 font-semibold">${{ number_format($product->price, 2) }}</span>
                                        <span class="ml-1 text-xs text-gray-500">(Fijo)</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <span class="text-purple-600 font-semibold">${{ number_format($product->base_cost ?? 0, 2) }}</span>
                                        <span class="ml-1 text-xs text-gray-500">(Base)</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($product->product_type === 'gallery')
                                        <button wire:click="toggleGalleryVisibility({{ $product->id }})"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                                       {{ $product->is_gallery_visible 
                                                          ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                                          : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                            @if($product->is_gallery_visible)
                                                <i class="fas fa-eye mr-1"></i> Visible
                                            @else
                                                <i class="fas fa-eye-slash mr-1"></i> Oculto
                                            @endif
                                        </button>
                                    @else
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-cog mr-1"></i> Solo Costeo
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->product_type === 'customizable')
                                        <a href="{{ route('admin.products.materials', $product) }}" 
                                           class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                           title="Gestionar materiales">
                                            <i class="fas fa-tools"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.products.3d-model', $product) }}" 
                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 {{ $product->has_3d_model ? 'bg-green-100 dark:bg-green-900 rounded px-1' : '' }}"
                                       title="{{ $product->has_3d_model ? 'Gestionar modelo 3D (activo)' : 'Agregar modelo 3D' }}">
                                        <i class="fas fa-cube"></i>
                                    </a>
                                    <button wire:click="deleteProduct({{ $product->id }})" 
                                            onclick="return confirm('¿Estás seguro de eliminar este producto?')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No se encontraron productos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($products->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
