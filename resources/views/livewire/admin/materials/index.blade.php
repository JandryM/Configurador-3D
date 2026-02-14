<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show" x-transition.opacity.500ms class="mb-6">
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center space-x-3 shadow">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity.500ms class="mb-6">
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center space-x-3 shadow">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Materiales"
        description="Administra el inventario y precios de materiales"
        :show-button="auth()->user()->role === 'admin' || auth()->user()->role === 'owner'"
        button-text="Nuevo Material"
        wire:click="createMaterial"
    >
        <x-slot name="buttonIcon">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </x-slot>
    </x-page-header>

    <!-- Modal de Creación -->
    <div x-data="{ open: @entangle('showCreateModal') }" x-show="open" x-transition.opacity x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto border border-slate-100" @click.away="open = false">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">
                    Nuevo Material
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <livewire:admin.materials.materials-form />
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div x-data="{ open: @entangle('showEditModal') }" x-show="open" x-transition.opacity x-cloak class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto border border-slate-100" @click.away="open = false">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">
                    Editar Material
                </h3>
                <button wire:click="closeEditModal" class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                @if($editingMaterial)
                    <livewire:admin.materials.materials-form 
                        :material="$editingMaterial" 
                        :key="'edit-'.$editingMaterial->id" />
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar -->
    <div x-data="{ open: @entangle('showDeleteModal') }" 
        x-show="open" 
        x-transition.opacity 
        x-cloak 
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[60]">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 border border-slate-100" @click.away="open = false">
            <div class="p-6">
                <!-- Icono de advertencia -->
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- Contenido -->
                <h3 class="text-xl font-bold text-slate-800 text-center mb-2">
                    Confirmar Eliminación
                </h3>
                
                @if($materialToDelete)
                <p class="text-slate-600 text-center mb-2">
                   ¿Estás seguro de eliminar el material?
                </p>
                <p class="text-slate-800 font-semibold text-center mb-4 px-4 py-2 bg-slate-100 rounded-lg">
                    {{ $materialToDelete->name }}
                </p>
                <p class="text-slate-600 text-center mb-6 text-sm">
                    Esta acción no se puede deshacer.
                </p>
                @endif
                
                <!-- Botones -->
                <div class="flex gap-3">
                    <button wire:click="closeDeleteModal" 
                            type="button"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all">
                        Cancelar
                    </button>
                    <button wire:click="deleteMaterial" 
                            wire:loading.attr="disabled"
                            type="button"
                            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-sm font-medium transition-all cursor-pointer">
                        <span wire:loading.remove>Eliminar</span>
                        <span wire:loading>
                            Eliminando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de materiales -->
    @php
        $totalMaterials = \App\Models\Material::count();
        $byPieceMaterials = \App\Models\Material::where('is_by_piece', true)->count();
        $byDimensionMaterials = \App\Models\Material::where('has_dimensions', true)->count();
        $totalUnits = \App\Models\Unit::count();
        $totalValue = \App\Models\Material::sum('unit_price');
    @endphp

    <x-stats-grid columns="4">
        <!-- Total de Materiales -->
        <x-stat-card 
            title="Total Materiales" 
            :value="$totalMaterials"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- Por Piezas -->
        <x-stat-card 
            title="Por Piezas" 
            :value="$byPieceMaterials"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- Por Dimensiones -->
        <x-stat-card 
            title="Unidades" 
            :value="$totalUnits"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zM7 8a1 1 0 000 2h.01a1 1 0 000-2H7z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <!-- Valor Total -->
        <x-stat-card 
            title="Valor Total" 
            :value="'$' . number_format($totalValue, 0)"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>
    </x-stats-grid>

    <!-- Tabla de materiales -->
    <x-table-container
        :has-pagination="true"
        :page="$materials->currentPage()"
        :per-page="$materials->perPage()"
        :total="$materials->total()"
        item-name="materiales"
    >
        <!-- Filtros y búsqueda dentro de la tabla -->
        <div class="bg-white border-b border-slate-200 mb-4 px-4 py-3">
            <div class="flex flex-wrap gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Buscar Material
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nombre o descripción..."
                            class="w-full pl-9 pr-3 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 text-sm transition-shadow"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por tipo -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Tipo
                    </label>
                    <select 
                        wire:model.live="filterByType"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="all">Todos</option>
                        <option value="by_piece">Por Piezas</option>
                        <option value="by_unit">Por Unidad</option>
                    </select>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <x-table-header>Material</x-table-header>
                <x-table-header>Categoría</x-table-header>
                <x-table-header>Precio</x-table-header>
                <x-table-header>Tipo</x-table-header>
                <x-table-header>Medida</x-table-header>
                @if($userRole === 'admin' || $userRole === 'owner')
                    <x-table-header align="center">Acciones</x-table-header>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                    <x-table-cell>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center shadow-sm border border-slate-200">
                                <span class="text-sm font-bold text-slate-700">
                                    {{ strtoupper(substr($material->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $material->name }}</p>
                                <p class="text-sm text-slate-500">{{ $material->description ?? 'Sin descripción' }}</p>
                            </div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">
                            {{ $material->category?->name ?? 'General' }}
                        </span>
                    </x-table-cell>
                    <x-table-cell>
                        <div>
                            <span class="font-medium text-slate-800">${{ number_format($material->unit_price, 2) }}</span>
                            <p class="text-xs text-slate-500">por {{ $material->unit_measure }}</p>
                            @if($material->is_by_piece || $material->has_dimensions)
                                <p class="text-xs text-slate-400 mt-0.5">Pieza: ${{ number_format($material->piece_price, 2) }}</p>
                            @endif
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        @if($material->is_by_piece)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded-full border border-slate-200">
                                Por Piezas
                            </span>
                        @elseif($material->has_dimensions)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded-full border border-slate-200">
                                Por Dimensiones
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded-full border border-slate-200">
                                Por Unidad
                            </span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        @if($material->is_by_piece)
                            <span class="text-slate-700 font-medium">{{ number_format($material->piece_size, 3) }} {{ $material->unit_measure }}</span>
                        @elseif($material->has_dimensions)
                            @if($material->width && $material->height)
                                <span class="text-slate-700 font-medium">{{ $material->width }}m x {{ $material->height }}m</span>
                            @else
                                <span class="text-slate-700 font-medium">{{ number_format($material->piece_size, 3) }} {{ $material->unit_measure }}</span>
                            @endif
                        @else
                            <span class="text-slate-700 font-medium">{{ $material->unit_measure }}</span>
                        @endif
                    </x-table-cell>
                    @if($userRole === 'admin' || $userRole === 'owner')
                        <x-table-cell align="center">
                            <div class="flex justify-center space-x-2">
                                <x-action-button 
                                    color="blue"
                                    tooltip="Editar material"
                                    wire:click="editMaterial({{ $material->id }})"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </x-action-button>

                                <x-action-button 
                                    color="red"
                                    tooltip="Eliminar material"
                                    wire:click="confirmDelete({{ $material->id }})"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </x-action-button>
                            </div>
                        </x-table-cell>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex flex-col items-center justify-center text-slate-500">
                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium text-slate-900">No hay materiales</p>
                            <p class="text-sm">No se han encontrado materiales registrados.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-table-container>
</div>