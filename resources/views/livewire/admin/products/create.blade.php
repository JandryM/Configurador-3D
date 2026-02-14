<!-- Modal overlay -->
<div x-data="{ show: @entangle('showCreateProductModal').defer }" x-show="show"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">

    <!-- Modal content -->
    <div @click.away="show = false; $wire.dispatch('closeCreateProductModal')"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
        class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-slate-100">

        <!-- Modal header -->
        <div
            class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 rounded-t-xl flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-slate-50 text-slate-700">
                     <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Crear Nuevo Producto</h2>
            </div>
            <button @click="show = false; $wire.dispatch('closeCreateProductModal')"
                class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-6">
            <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                    <input type="text" id="name" wire:model.defer="name"
                        class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                        placeholder="Nombre del producto"
                        required>
                    @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                    <textarea id="description" wire:model.defer="description"
                        class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                        rows="4" placeholder="Descripción detallada del producto" required></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <!-- Tipo de Producto -->
                <div>
                    <label for="product_type" class="block text-sm font-medium text-slate-700 mb-1">Tipo de Producto</label>
                    <select id="product_type" wire:model.live="product_type"
                        class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all cursor-pointer"
                        required>
                        @foreach($productTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('product_type') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <!-- Precio (solo galería) -->
                @if($product_type === 'gallery')
                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Precio</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 text-lg pointer-events-none">$</span>
                            <input type="number" id="price" wire:model.defer="price" step="0.01"
                                class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                                placeholder="0.00"
                                required>
                        </div>
                        @error('price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @endif
                <!-- Costo base y dimensiones (solo personalizable) -->
                @if($product_type === 'customizable')
                    <div>
                        <label for="base_cost" class="block text-sm font-medium text-slate-700 mb-1">Costo Base</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 text-lg pointer-events-none">$</span>
                            <input type="number" id="base_cost" wire:model.defer="base_cost" step="0.01"
                                class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                                placeholder="0.00"
                                required>
                        </div>
                        @error('base_cost') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="base_dimensions_height" class="block text-sm font-medium text-slate-700 mb-1">Alto
                                (cm)</label>
                            <input type="number" id="base_dimensions_height" wire:model.defer="base_dimensions.height"
                                step="0.01"
                                class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                                placeholder="0.00"
                                required>
                        </div>
                        <div>
                            <label for="base_dimensions_width" class="block text-sm font-medium text-slate-700 mb-1">Ancho
                                (cm)</label>
                            <input type="number" id="base_dimensions_width" wire:model.defer="base_dimensions.width"
                                step="0.01"
                                class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400"
                                placeholder="0.00"
                                required>
                        </div>
                    </div>
                    @error('base_dimensions') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                @endif
                <!-- Categoría -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                    <select id="category_id" wire:model="category_id"
                        class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all cursor-pointer"
                        required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <!-- Imagen -->
                <div>
                    <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Imagen</label>
                    <input type="file" id="image" wire:model="image"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 transition-all cursor-pointer">
                    @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    @if($image)
                        <div class="mt-3">
                            <p class="text-xs text-slate-500 mb-1">Vista previa:</p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Vista previa" class="h-32 rounded-lg shadow-sm border border-slate-200 object-cover">
                        </div>
                    @endif
                </div>
                <!-- Visible en Galería -->
                <div class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                     <div class="flex items-center h-5">
                        <input type="checkbox" id="is_gallery_visible" wire:model="is_gallery_visible"
                            class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900 cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_gallery_visible" class="font-medium text-slate-800 cursor-pointer">¿Visible en Galería?</label>
                        <p class="text-xs text-slate-500">Este producto aparecerá en la galería pública del sitio.</p>
                    </div>
                </div>
                <!-- Permite personalización (solo personalizable) -->
                @if($product_type === 'customizable')
                    <div class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="allows_customization" wire:model="allows_customization"
                                class="w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900 cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="allows_customization" class="font-medium text-slate-800 cursor-pointer">¿Permite Personalización?</label>
                            <p class="text-xs text-slate-500">Los usuarios podrán personalizar este producto.</p>
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="cancel"
                        class="flex-1 px-4 py-3 bg-white text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium transition-all cursor-pointer">Cancelar</button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm hover:shadow-md font-medium transition-all cursor-pointer">
                        Guardar Producto
                    </button>
                </div>
                @if (session()->has('message'))
                    <div class="mt-4 p-3 bg-green-50 text-green-700 rounded-lg text-center text-sm font-medium border border-green-200">
                        {{ session('message') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>