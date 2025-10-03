<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Producto</h1>
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

                {{-- Precio y Categoría --}}
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
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Categoría *
                        </label>
                        <select id="category"
                                wire:model.blur="category" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Imagen --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Imagen del Producto
                    </label>
                    
                    {{-- Imagen actual --}}
                    @if ($currentImage)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagen actual:</p>
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($currentImage) }}" 
                                     class="h-32 w-32 object-cover rounded-lg">
                                <button type="button" 
                                        wire:click="removeCurrentImage"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Nueva imagen --}}
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if ($image)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Nueva imagen:</p>
                                    <img src="{{ $image->temporaryUrl() }}" 
                                         class="mx-auto h-32 w-32 object-cover rounded-lg">
                                    <button type="button" 
                                            wire:click="$set('image', null)"
                                            class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remover nueva imagen
                                    </button>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ $currentImage ? 'Cambiar imagen' : 'Subir una imagen' }}</span>
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

                {{-- Visibilidad en galería --}}
                <div class="flex items-center">
                    <input id="is_gallery_visible" 
                           wire:model="is_gallery_visible" 
                           type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_gallery_visible" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Mostrar en galería pública
                    </label>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            wire:click="cancel"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span wire:loading.remove>Actualizar Producto</span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Sección de Materiales -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mt-6">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Gestión de Materiales y Costos</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Configura los materiales necesarios para calcular el costo de producción
                </p>
            </div>
            <div class="p-6">
                <livewire:admin.products.product-materials :product="$product" />
            </div>
        </div>
    </div>
</div>
