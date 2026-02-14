<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Editar Producto</h1>
            <button wire:click="cancel" 
                    class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <form wire:submit="save" class="p-6 space-y-6">
                
                {{-- Nombre del producto --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                        Nombre del Producto *
                    </label>
                    <input type="text" 
                           id="name"
                           wire:model.blur="name" 
                           class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                           placeholder="Ej: Ventana de Aluminio Moderna">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
                        Descripción *
                    </label>
                    <textarea id="description"
                              wire:model.blur="description" 
                              rows="4"
                              class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                              placeholder="Describe las características y beneficios del producto..."></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Precio y Categoría --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Precio --}}
                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">
                            Precio (USD) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500">$</span>
                            <input type="number" 
                                   id="price"
                                   wire:model.blur="price" 
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-8 pr-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Categoría --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">
                            Categoría *
                        </label>
                        <select id="category_id"
                                wire:model.blur="category_id" 
                                class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all cursor-pointer">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Imagen --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-slate-700 mb-1">
                        Imagen del Producto
                    </label>
                    
                    {{-- Imagen actual --}}
                    @if ($currentImage)
                        <div class="mb-4">
                            <p class="text-xs text-slate-500 mb-2">Imagen actual:</p>
                            <div class="relative inline-block group">
                                <img src="{{ Storage::url($currentImage) }}" 
                                     class="h-32 w-32 object-cover rounded-lg border border-slate-200">
                                <button type="button" 
                                        wire:click="removeCurrentImage"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 shadow-sm cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Nueva imagen --}}
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-slate-400 transition-colors bg-slate-50">
                        <div class="space-y-1 text-center">
                            @if ($image)
                                <div class="mb-4">
                                    <p class="text-xs text-slate-500 mb-2">Nueva imagen:</p>
                                    <img src="{{ $image->temporaryUrl() }}" 
                                         class="mx-auto h-32 w-32 object-cover rounded-lg border border-slate-200">
                                    <button type="button" 
                                            wire:click="$set('image', null)"
                                            class="mt-2 text-sm text-red-500 hover:text-red-700 font-medium cursor-pointer">
                                        Remover nueva imagen
                                    </button>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-slate-900 hover:text-slate-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-slate-900">
                                        <span>{{ $currentImage ? 'Cambiar imagen' : 'Subir una imagen' }}</span>
                                        <input id="image" wire:model="image" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">o arrastrar y soltar</p>
                                </div>
                                <p class="text-xs text-slate-500">
                                    PNG, JPG, GIF hasta 2MB
                                </p>
                            @endif
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Visibilidad en galería --}}
                <div class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <input id="is_gallery_visible" 
                           wire:model="is_gallery_visible" 
                           type="checkbox" 
                           class="h-4 w-4 text-slate-900 focus:ring-slate-900 border-slate-300 rounded cursor-pointer">
                    <label for="is_gallery_visible" class="ml-2 block text-sm font-medium text-slate-700 cursor-pointer">
                        Mostrar en galería pública
                    </label>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" 
                            wire:click="cancel"
                            class="px-4 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium transition-all cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm hover:shadow-md font-medium transition-all cursor-pointer">
                        <span wire:loading.remove>Actualizar Producto</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Actualizando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
