<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Crear Nuevo Producto</h1>
            <button wire:click="cancel" 
                    class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <form wire:submit="save" class="p-6 space-y-6">
                
                {{-- Nombre del producto --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nombre del Producto *
                    </label>
                    <input type="text" 
                           id="name"
                           wire:model.blur="name" 
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: Ventana de Aluminio Moderna">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción *
                    </label>
                    <textarea id="description"
                              wire:model.blur="description" 
                              rows="4"
                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe las características y beneficios del producto..."></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Producto --}}
                <div>
                    <label for="product_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Producto *
                    </label>
                    <select id="product_type"
                            wire:model.live="product_type" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        @foreach($productTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('product_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                        @if($product_type === 'gallery')
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <i class="fas fa-store mr-2"></i>
                                Los productos de galería tienen precio fijo y se muestran en la tienda web para que los clientes los compren directamente.
                            </p>
                        @else
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <i class="fas fa-tools mr-2"></i>
                                Los productos personalizables permiten al cliente modificar dimensiones y se calculan usando costeo por orden de trabajo con materiales.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Campos condicionales según tipo de producto --}}
                @if($product_type === 'gallery')
                    {{-- Precio y Categoría para productos de galería --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Precio --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Precio (USD) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">$</span>
                                <input type="number" 
                                       id="price"
                                       wire:model.blur="price" 
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-8 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0.00">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Categoría *
                            </label>
                            <select id="category_id"
                                    wire:model.blur="category_id" 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @else
                    {{-- Campos para productos personalizables --}}
                    <div class="space-y-6">
                        {{-- Categoría y Costo Base --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Categoría *
                                </label>
                                <select id="category_id"
                                        wire:model.blur="category_id" 
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="base_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Costo Base de Mano de Obra (USD) *
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">$</span>
                                    <input type="number" 
                                           id="base_cost"
                                           wire:model.blur="base_cost" 
                                           step="0.01"
                                           min="0"
                                           class="w-full pl-8 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0.00">
                                </div>
                                @error('base_cost')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Costo base de fabricación sin incluir materiales
                                </p>
                            </div>
                        </div>

                        {{-- Dimensiones Base --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Dimensiones Base *
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="length" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Largo (cm)
                                    </label>
                                    <input type="number" 
                                           id="length"
                                           wire:model.blur="base_dimensions.length" 
                                           min="0"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label for="width" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Ancho (cm)
                                    </label>
                                    <input type="number" 
                                           id="width"
                                           wire:model.blur="base_dimensions.width" 
                                           min="0"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label for="height" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                        Alto (cm)
                                    </label>
                                    <input type="number" 
                                           id="height"
                                           wire:model.blur="base_dimensions.height" 
                                           min="0"
                                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0">
                                </div>
                            </div>
                            @error('base_dimensions')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Estas dimensiones servirán como punto de partida que el cliente podrá modificar
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Imagen --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagen del Producto
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if ($image)
                                <div class="mb-4">
                                    <img src="{{ $image->temporaryUrl() }}" 
                                         class="mx-auto h-32 w-32 object-cover rounded-lg">
                                    <button type="button" 
                                            wire:click="$set('image', null)"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remover imagen
                                    </button>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Subir una imagen</span>
                                        <input id="image" wire:model="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">o arrastrar y soltar</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF hasta 2MB
                                </p>
                            @endif
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Visibilidad en galería (solo para productos de galería) --}}
                @if($product_type === 'gallery')
                    <div class="flex items-center">
                        <input id="is_gallery_visible" 
                               wire:model="is_gallery_visible" 
                               type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_gallery_visible" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Mostrar en galería pública
                        </label>
                    </div>
                @endif

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            wire:click="cancel"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span wire:loading.remove>
                            @if($product_type === 'gallery')
                                Crear Producto de Galería
                            @else
                                Crear Producto Personalizable
                            @endif
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i>Creando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
