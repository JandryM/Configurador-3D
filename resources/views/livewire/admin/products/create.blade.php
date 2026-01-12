<!-- Modal overlay -->
<div x-data="{ show: @entangle('showCreateProductModal').defer }" x-show="show"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">

    <!-- Modal content -->
    <div @click.away="show = false; $wire.dispatch('closeCreateProductModal')"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95"
        class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">

        <!-- Modal header -->
        <div
            class="sticky top-0 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h2 class="text-2xl font-bold">Crear Nuevo Producto</h2>
            <button @click="show = false; $wire.dispatch('closeCreateProductModal')"
                class="text-white hover:text-gray-200 transition-colors">
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
                    <label for="name" class="block text-lg font-medium text-slate-700">Nombre</label>
                    <input type="text" id="name" wire:model.defer="name"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-lg font-medium text-slate-700">Descripción</label>
                    <textarea id="description" wire:model.defer="description"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        rows="4" required></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Tipo de Producto -->
                <div>
                    <label for="product_type" class="block text-lg font-medium text-slate-700">Tipo de Producto</label>
                    <select id="product_type" wire:model="product_type"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                        @foreach($productTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('product_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Precio (solo galería) -->
                @if($product_type === 'gallery')
                    <div>
                        <label for="price" class="block text-lg font-medium text-slate-700">Precio</label>
                        <input type="number" id="price" wire:model.defer="price" step="0.01"
                            class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif
                <!-- Costo base y dimensiones (solo personalizable) -->
                @if($product_type === 'customizable')
                    <div>
                        <label for="base_cost" class="block text-lg font-medium text-slate-700">Costo Base</label>
                        <input type="number" id="base_cost" wire:model.defer="base_cost" step="0.01"
                            class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('base_cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="base_dimensions_height" class="block text-lg font-medium text-slate-700">Alto
                                (cm)</label>
                            <input type="number" id="base_dimensions_height" wire:model.defer="base_dimensions.height"
                                step="0.01"
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="base_dimensions_width" class="block text-lg font-medium text-slate-700">Ancho
                                (cm)</label>
                            <input type="number" id="base_dimensions_width" wire:model.defer="base_dimensions.width"
                                step="0.01"
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>
                    @error('base_dimensions') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @endif
                <!-- Categoría -->
                <div>
                    <label for="category_id" class="block text-lg font-medium text-slate-700">Categoría</label>
                    <select id="category_id" wire:model="category_id"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Imagen -->
                <div>
                    <label for="image" class="block text-lg font-medium text-slate-700">Imagen</label>
                    <input type="file" id="image" wire:model="image"
                        class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @if($image)
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Vista previa" class="h-32 rounded-lg shadow-md">
                        </div>
                    @endif
                </div>
                <!-- Visible en Galería -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_gallery_visible" wire:model="is_gallery_visible"
                        class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label for="is_gallery_visible" class="ml-2 text-lg font-medium text-slate-700">¿Visible en
                        Galería?</label>
                </div>
                <!-- Permite personalización (solo personalizable) -->
                @if($product_type === 'customizable')
                    <div class="flex items-center">
                        <input type="checkbox" id="allows_customization" wire:model="allows_customization"
                            class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <label for="allows_customization" class="ml-2 text-lg font-medium text-slate-700">¿Permite
                            Personalización?</label>
                    </div>
                @endif
                <div class="flex gap-4">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Guardar
                        Producto</button>
                    <button type="button" wire:click="cancel"
                        class="w-full bg-gradient-to-r from-slate-400 to-slate-600 hover:from-slate-500 hover:to-slate-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Cancelar</button>
                </div>
                @if (session()->has('message'))
                    <div class="mt-4 text-green-600 font-semibold text-center">
                        {{ session('message') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>