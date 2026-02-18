<div x-data="{ 
    showCreateProductModal: @entangle('showCreateProductModal').live,
    showEditProductModal: @entangle('showEditProductModal').live
}">
    @php
        $categoryTranslations = [
            'Mesh' => 'Malla',
            'Window' => 'Ventana',
            'Door' => 'Puerta',
        ];
    @endphp
    <!-- Modal de confirmación de visibilidad -->
    @if($showVisibilityConfirmModal && $pendingVisibilityProductId)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5);"
        x-data
        x-init="$nextTick(() => {})"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[70]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Icono del modal -->
            <div class="flex items-center justify-center mb-6">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-slate-50 border border-slate-100">
                    <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h3 class="text-xl font-bold text-slate-800 text-center mb-2" wire:key="modal-title-{{ $pendingVisibilityProductId }}-{{ $pendingVisibilityValue ? 'show' : 'hide' }}">
                @if($pendingVisibilityAction === 'gallery')
                    @if($pendingVisibilityValue)
                        Mostrar producto en galería
                    @else
                        Ocultar producto de galería
                    @endif
                @else
                    @if($pendingVisibilityValue)
                        Mostrar producto en personalización
                    @else
                        Ocultar producto de personalización
                    @endif
                @endif
            </h3>

            <!-- Descripción -->
            <p class="text-slate-600 text-center mb-6" wire:key="modal-desc-{{ $pendingVisibilityProductId }}-{{ $pendingVisibilityValue ? 'show' : 'hide' }}">
                @if($pendingVisibilityAction === 'gallery')
                    @if($pendingVisibilityValue)
                        Este producto será visible para todos los visitantes en la galería pública. Los clientes podrán verlo y añadirlo a su carrito.
                    @else
                        Este producto dejará de aparecer en la galería pública. Los clientes no podrán verlo ni comprarlo hasta que lo vuelvas a mostrar.
                    @endif
                @else
                    @if($pendingVisibilityValue)
                        Este producto estará disponible en el menú de personalización. Los clientes podrán acceder al configurador 3D para personalizarlo según sus necesidades.
                    @else
                        Este producto será ocultado del menú de personalización. Los clientes no podrán acceder a su configurador ni solicitarlo como producto personalizable.
                    @endif
                @endif
            </p>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="button" 
                    wire:click="closeVisibilityConfirmModal" 
                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all">
                    Cancelar
                </button>
                <button type="button" 
                    wire:click="confirmVisibilityChange" 
                    class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm font-medium transition-all">
                    @if($pendingVisibilityValue)
                        Mostrar Producto
                    @else
                        Ocultar Producto
                    @endif
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Productos"
        description="Administra el catálogo de productos y sus configuraciones"
        :show-button="auth()->user()->role === 'admin' || auth()->user()->role === 'owner'"
        button-text="Nuevo Producto"
        button-color="amber"
        x-data
        x-on:click="showCreateProductModal = true"
    >
    </x-page-header>

    @if (session()->has('message'))
        <div class="mb-4 animate-in fade-in slide-in-from-top-2 duration-500" 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 1500)"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-teal-50 border border-teal-200 rounded-lg shadow-sm p-4 text-teal-800">
                {{ session('message') }}
            </div>
        </div>
    @endif
    
    <!-- Modal unificado para crear/editar producto -->
    <div x-show="showCreateProductModal || showEditProductModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5);">
        <style>
            [x-cloak] { display: none !important; }
        </style>
        <div x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative z-[60] border border-slate-100">
            <!-- Modal header -->
            <div class="sticky top-0 px-6 py-4 flex justify-between items-center z-10 bg-custom-blue border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <h2 class="text-xl font-bold text-slate-100">
                        <span x-show="showEditProductModal">Editar Producto</span>
                        <span x-show="showCreateProductModal">Crear Nuevo Producto</span>
                    </h2>
                </div>
                <button @click="showCreateProductModal = false; showEditProductModal = false" wire:click="closeCreateProductModal" class="text-slate-400 hover:text-slate-600 rounded-lg p-1 transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6">
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-500/90 border border-red-500 rounded-xl">
                        <p class="text-red-200 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <form @if($showEditProductModal) wire:submit.prevent="saveEditProduct" @else wire:submit.prevent="saveProduct" @endif enctype="multipart/form-data" class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <label for="@if($showEditProductModal)edit_name@else name @endif" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                        @if($showEditProductModal)
                            <input type="text" id="edit_name" wire:model.defer="edit_name" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="Nombre del producto" required>
                            @error('edit_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @else
                            <input type="text" id="name" wire:model.defer="name" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="Nombre del producto" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>
                    <!-- Descripción -->
                    <div>
                        <label for="@if($showEditProductModal)edit_description@else description @endif" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                        @if($showEditProductModal)
                            <textarea id="edit_description" wire:model.defer="edit_description" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" rows="2" placeholder="Descripción del producto" required></textarea>
                            @error('edit_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @else
                            <textarea id="description" wire:model.defer="description" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" rows="2" placeholder="Descripción del producto" required></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        @endif
                    </div>
                    <!-- Grid: Precio y Categoría -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="@if($showEditProductModal)edit_price@else price @endif" class="block text-sm font-medium text-slate-700 mb-1">Precio</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 text-lg pointer-events-none">$</span>
                                @if($showEditProductModal)
                                    <input type="text" id="edit_price" wire:model.defer="edit_price" maxlength="8" pattern="^\d{1,5}(\.\d{0,2})?$" step="0.01" min="0" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="0.00" required oninput="let val = this.value.replace(/[^0-9.]/g, ''); let parts = val.split('.'); if(parts.length > 2) val = parts[0] + '.' + parts.slice(1).join(''); if(parts[1] && parts[1].length > 2) val = parts[0] + '.' + parts[1].substring(0, 2); this.value = val;" onblur="if(this.value && this.value.trim() !== ''){ let v = parseFloat(this.value); if(!isNaN(v) && v >= 0 && v <= 99999.99) this.value = v.toFixed(2); else this.value = ''; }">
                                    @error('edit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @else
                                    <input type="text" id="price" wire:model.defer="price" maxlength="8" pattern="^\d{1,5}(\.\d{0,2})?$" step="0.01" min="0" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" placeholder="0.00" required oninput="let val = this.value.replace(/[^0-9.]/g, ''); let parts = val.split('.'); if(parts.length > 2) val = parts[0] + '.' + parts.slice(1).join(''); if(parts[1] && parts[1].length > 2) val = parts[0] + '.' + parts[1].substring(0, 2); this.value = val;" onblur="if(this.value && this.value.trim() !== ''){ let v = parseFloat(this.value); if(!isNaN(v) && v >= 0 && v <= 99999.99) this.value = v.toFixed(2); else this.value = ''; }">
                                    @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                @endif
                            </div>
                        </div>
                        <div>
                            <label for="@if($showEditProductModal)edit_category_id@else new_category_id @endif" class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                            @if($showEditProductModal)
                                <select id="edit_category_id" wire:model="edit_category_id" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $categoryTranslations[$category->name] ?? $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @else
                                <select id="new_category_id" wire:model="new_category_id" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $categoryTranslations[$category->name] ?? $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('new_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @endif
                        </div>
                    </div>
                    <!-- Imagen -->
                    <div>
                        <label for="@if($showEditProductModal)edit_image@else image @endif" class="block text-sm font-medium text-slate-700 mb-2">Imagen</label>
                        @if($showEditProductModal)
                            <input type="file" id="edit_image" wire:model="edit_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/bmp,image/svg+xml,image/webp,image/avif" class="cursor-pointer w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800" @if(!$edit_current_image) required @endif>
                            @if($edit_current_image)
                                <p class="text-slate-400 text-xs mt-1">Si no seleccionas una nueva imagen, se mantendrá la actual.</p>
                            @endif
                            @error('edit_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @php
                                $hasImage = false;
                                $previewUrl = null;
                                $canPreview = false;
                                if($edit_image) {
                                    try {
                                        $previewUrl = $edit_image->temporaryUrl();
                                        $canPreview = true;
                                        $hasImage = true;
                                    } catch (\Exception $e) {
                                        $canPreview = false;
                                        $hasImage = true;
                                    }
                                } elseif($edit_current_image) {
                                    $hasImage = true;
                                    $previewUrl = Storage::url($edit_current_image);
                                    $canPreview = true;
                                }
                            @endphp
                            @if($hasImage)
                                <div class="relative w-full h-64 bg-white border border-custom-blue/30 rounded-2xl overflow-hidden mt-2 flex items-center justify-center">
                                    @if($canPreview)
                                        <img src="{{ $previewUrl }}" alt="Vista previa" class="w-full h-64 object-cover rounded-2xl shadow-xl border border-custom-blue/30 bg-white">
                                        <button type="button" wire:click="removeEditImage" class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold shadow-lg z-20 hover:bg-red-700 transition-colors" title="Quitar imagen seleccionada">X</button>
                                    @else
                                        <div class="bg-blue-50 border border-custom-blue/30 rounded-2xl p-4 flex flex-col items-center justify-center w-full h-64">
                                            <svg class="w-12 h-12 text-slate-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-slate-200 text-base font-medium text-center">Archivo seleccionado</p>
                                            <p class="text-slate-400 text-xs text-center">Vista previa no disponible</p>
                                            <button type="button" wire:click="removeEditImage" class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold shadow-lg z-20 hover:bg-red-700 transition-colors mt-2" title="Quitar imagen seleccionada">X</button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <input type="file" id="image" wire:model="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/bmp,image/svg+xml,image/webp,image/avif" class="cursor-pointer w-full px-4 py-3 bg-slate-50 border border-slate-300 text-slate-900 rounded-xl focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800" required>
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @php
                                $hasImage = false;
                                $previewUrl = null;
                                $canPreview = false;
                                if($image) {
                                    try {
                                        $previewUrl = $image->temporaryUrl();
                                        $canPreview = true;
                                        $hasImage = true;
                                    } catch (\Exception $e) {
                                        $canPreview = false;
                                        $hasImage = true;
                                    }
                                }
                            @endphp
                            @if($hasImage)
                                <div class="relative w-full h-64 bg-white border border-custom-blue/30 rounded-2xl overflow-hidden mt-2 flex items-center justify-center">
                                    @if($canPreview)
                                        <img src="{{ $previewUrl }}" alt="Vista previa" class="w-full h-64 object-cover rounded-2xl shadow-xl border border-custom-blue/30 bg-white">
                                        <button type="button" wire:click="removeImage" class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold shadow-lg z-20 hover:bg-red-700 transition-colors" title="Quitar imagen seleccionada">X</button>
                                    @else
                                        <div class="bg-blue-50 border border-custom-blue/30 rounded-2xl p-4 flex flex-col items-center justify-center w-full h-64">
                                            <svg class="w-12 h-12 text-slate-400 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-slate-200 text-base font-medium text-center">Archivo seleccionado</p>
                                            <p class="text-slate-400 text-xs text-center">Vista previa no disponible</p>
                                            <button type="button" wire:click="removeImage" class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center font-bold shadow-lg z-20 hover:bg-red-700 transition-colors mt-2" title="Quitar imagen seleccionada">X</button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                    <!-- Toggle Switch: Visible en Galería -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div>
                            <label :for="showEditProductModal ? 'edit_is_gallery_visible' : 'is_gallery_visible'" class="text-sm font-medium text-slate-800">Visible en Galería</label>
                            <p class="text-xs text-slate-500 mt-0.5">El producto aparecerá en la galería pública</p>
                        </div>
                        @if($showEditProductModal)
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="edit_is_gallery_visible" wire:model="edit_is_gallery_visible" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                            </label>
                        @else
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_gallery_visible" wire:model="is_gallery_visible" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-slate-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                            </label>
                        @endif
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateProductModal = false; showEditProductModal = false" wire:click="closeCreateProductModal" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all cursor-pointer">Cancelar</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm font-medium transition-all cursor-pointer">
                            <span x-show="showEditProductModal">Guardar Cambios</span>
                            <span x-show="showCreateProductModal">Guardar Producto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Estadísticas de productos -->
    <x-stats-grid columns="4">
        <x-stat-card 
            title="Total Productos" 
            :value="$totalProducts"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Galería" 
            :value="$galleryProducts"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Personalizables" 
            :value="$customizableProducts"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Visibles" 
            :value="$visibleProducts"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>
    </x-stats-grid>

    <!-- Tabla de productos -->
    <x-table-container 
        :has-pagination="true" 
        :page="$page" 
        :per-page="$perPage" 
        :total="$total"
        item-name="productos"
    >
        <!-- Filtros y búsqueda dentro de la tabla -->
        <div class="bg-blue-50 border-b border-custom-blue/20 mb-4 px-4 py-3">
            <div class="flex flex-wrap gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Buscar Producto
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nombre o descripción..."
                            class="w-full pl-9 pr-3 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 text-sm transition-shadow"
                        >
                        <svg class="w-4 h-4 text-amber-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por categoría -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Categoría
                    </label>
                    <select 
                        wire:model.live="category_id"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="" class="py-2">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="py-2">{{ $categoryTranslations[$category->name] ?? $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por tipo -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tipo
                    </label>
                    <select 
                        wire:model.live="productType"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="" class="py-2">Todos</option>
                        <option value="gallery" class="py-2">Galería</option>
                        <option value="customizable" class="py-2">Personalizable</option>
                    </select>
                </div>

                <!-- Filtro por visibilidad -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Visibilidad
                    </label>
                    <select 
                        wire:model.live="galleryVisible"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="" class="py-2">Todos</option>
                        <option value="1" class="py-2">Visible</option>
                        <option value="0" class="py-2">Oculto</option>
                    </select>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-blue-50 border-b border-custom-blue/20">
                <x-table-header>Producto</x-table-header>
                <x-table-header>Tipo</x-table-header>
                <x-table-header>Precio</x-table-header>
                <x-table-header>Estado</x-table-header>
                <x-table-header>Creado</x-table-header>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                    <x-table-header align="center">Acciones</x-table-header>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                    <x-table-cell>
                        <div class="flex items-center space-x-3">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover shadow-md flex-shrink-0 border border-custom-blue">
                            @else
                                <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 border border-custom-blue">
                                    <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 max-w-xs">
                                <div class="relative group w-full">
                                    <p class="font-medium text-slate-800 w-full">{{ $product->name }}</p>
                                </div>
                                <div class="relative group w-full">
                                    <p class="text-sm text-slate-500 truncate w-full">{{ Str::limit($product->description, 50) }}</p>
                                    <span class="absolute bottom-full left-0 mb-2 px-3 py-2 text-xs font-medium text-white bg-slate-800 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none max-w-xs z-50 shadow-lg">
                                        {{ $product->description }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        @if($product->product_type === 'gallery')
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                Galería
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                Personalizable
                            </span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        @if($product->price)
                            <span class="font-medium text-slate-800">${{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="text-slate-500 text-xs">Cotizar</span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        @if($product->product_type === 'customizable')
                            @if($product->allows_customization)
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full whitespace-nowrap">
                                    Visible en personalización
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full whitespace-nowrap">
                                    Oculto en personalización
                                </span>
                            @endif
                        @else
                            @if($product->is_gallery_visible)
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full whitespace-nowrap">
                                    Visible en galería
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full whitespace-nowrap">
                                    Oculto en galería
                                </span>
                            @endif
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        <span class="text-slate-700">{{ $product->created_at->format('d/m/Y') }}</span>
                    </x-table-cell>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'owner')
                        <x-table-cell align="center">
                            <div class="flex justify-center space-x-2">
                                @if($product->product_type === 'customizable')
                                    <x-action-button 
                                        :color="$product->allows_customization ? 'green' : 'gray'"
                                        :tooltip="$product->allows_customization ? 'Ocultar producto' : 'Mostrar producto'"
                                        wire:click="toggleAllowsCustomization({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        @if($product->allows_customization)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </x-action-button>
                                @else
                                    <x-action-button 
                                        :color="$product->is_gallery_visible ? 'green' : 'gray'"
                                        :tooltip="$product->is_gallery_visible ? 'Ocultar de galería' : 'Mostrar en galería'"
                                        wire:click="toggleGalleryVisibility({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                    >
                                        @if($product->is_gallery_visible)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.96 9.96 0 012.197-6.13M6.13 6.13l11.74 11.74M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-5.12" />
                                            </svg>
                                        @endif
                                    </x-action-button>
                                    
                                    <x-action-button 
                                        color="blue"
                                        tooltip="Editar producto"
                                        @click="$wire.openEditProductModal({{ $product->id }}); showEditProductModal = true"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </x-action-button>
                                    
                                    <x-action-button 
                                        color="red"
                                        tooltip="Eliminar producto"
                                        wire:click="openDeleteConfirmModal({{ $product->id }})"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </x-action-button>
                                @endif
                            </div>
                        </x-table-cell>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500">
                        No hay productos registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-table-container>

    <!-- Modal de confirmación de eliminación -->
    @if($showDeleteConfirmModal && $pendingDeleteProductId)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.5);"
        x-data
        x-init="$nextTick(() => {})"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[70]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Icono de advertencia -->
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h3 class="text-xl font-bold text-slate-800 text-center mb-2">
                ¿Eliminar producto?
            </h3>

            <!-- Descripción -->
            <p class="text-slate-600 text-center mb-2">
                Estás a punto de eliminar el producto:
            </p>
            <p class="text-slate-800 font-semibold text-center mb-4 px-4 py-2 bg-slate-100 rounded-lg">
                {{ $pendingDeleteProductName }}
            </p>
            <p class="text-slate-600 text-center mb-6 text-sm">
                Esta acción no se puede deshacer. El producto y su imagen serán eliminados permanentemente del sistema.
            </p>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="button" 
                    wire:click="closeDeleteConfirmModal" 
                    class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all">
                    Cancelar
                </button>
                <button type="button" 
                    wire:click="confirmDeleteProduct" 
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-sm font-medium transition-all cursor-pointer">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
