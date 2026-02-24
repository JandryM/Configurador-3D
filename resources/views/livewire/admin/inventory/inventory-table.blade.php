<div>
    @php
        $categoryTranslations = [
            'Mesh' => 'Malla',
            'Window' => 'Ventana',
            'Door' => 'Puerta',
        ];
    @endphp
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Inventario"
        description="Administra el stock de materiales y retazos disponibles"
        :show-button="false"
    >
        <x-slot name="icon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
        </x-slot>
    </x-page-header>

    <!-- Mensajes de éxito/error -->
    @if (session()->has('success'))
        <div class="mb-4 animate-in fade-in slide-in-from-top-2 duration-500"
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 2000)"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white/70 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl p-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 animate-in fade-in slide-in-from-top-2 duration-500"
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 2000)"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white/70 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl p-6 bg-gradient-to-r from-red-500 to-rose-600 text-white">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Estadísticas de inventario -->
    @php
        $totalMaterials = \App\Models\Material::where('is_active', true)->count();
        $lowStock = \App\Models\Material::where('is_active', true)->whereRaw('stock_quantity <= min_stock_alert')->whereRaw('stock_quantity > 0')->count();
        $outOfStock = \App\Models\Material::where('is_active', true)->where('stock_quantity', 0)->whereDoesntHave('remainders', function ($q) { $q->where('status', 'available'); })->count();
        $totalRemainders = \App\Models\MaterialRemainder::where('status', 'available')->count();
    @endphp
    
    <x-stats-grid columns="4">
        <x-stat-card 
            title="Total Materiales" 
            :value="$totalMaterials"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Stock Bajo" 
            :value="$lowStock"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Sin Stock" 
            :value="$outOfStock"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Retazos" 
            :value="$totalRemainders"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            </x-slot>
        </x-stat-card>
    </x-stats-grid>

    <!-- Tabla de inventario -->
    <x-table-container 
        :has-pagination="true" 
        :page="$page" 
        :per-page="$perPage" 
        :total="$total"
        item-name="materiales"
    >
        <!-- Filtros y búsqueda dentro de la tabla -->
        <div class="bg-blue-50 border-b border-custom-blue/20 mb-4 px-4 py-3">
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
                        <svg class="w-4 h-4 text-teal-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por categoría -->
                @php
                    $categories = \App\Models\Category::orderBy('name')->get();
                @endphp
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Categoría
                    </label>
                    <select 
                        wire:model.live="category_id"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">
                                @if($cat->parent_id)
                                    &nbsp;&nbsp;└ {{ $categoryTranslations[$cat->name] ?? $cat->name }}
                                @else
                                    {{ $categoryTranslations[$cat->name] ?? $cat->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
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
                        <option value="by_piece">Por Pieza</option>
                        <option value="by_unit">Por Unidad</option>
                    </select>
                </div>

                <!-- Filtros de estado -->
                <div class="flex gap-2">
                    <button 
                        wire:click="toggleLowStockFilter"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors border {{ $filterLowStock ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}"
                    >
                        Bajo Stock
                    </button>
                    <button 
                        wire:click="toggleOutOfStockFilter"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors border {{ $filterOutOfStock ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}"
                    >
                        Sin Stock
                    </button>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-blue-50 border-b border-custom-blue-200">
                <x-table-header>Material</x-table-header>
                <x-table-header>Categoría</x-table-header>
                <x-table-header>Stock</x-table-header>
                <x-table-header>Retazos</x-table-header>
                <x-table-header>Total Disponible</x-table-header>
                <x-table-header align="center">Estado</x-table-header>
                <x-table-header align="center">Acciones</x-table-header>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
                @php
                    $remaindersCount = $material->remainders->count();
                    $remaindersTotal = $material->remainders->sum('remaining_length');
                    $totalAvailable = $material->total_available;
                    $stockInMeters = $material->stock_quantity * $material->piece_size;
                    
                    // Determinar estado
                    $isOutOfStock = $material->stock_quantity == 0 && $remaindersCount == 0;
                    $isLowStock = !$isOutOfStock && $material->stock_quantity <= $material->min_stock_alert;
                    $isGoodStock = !$isOutOfStock && !$isLowStock;
                @endphp
                
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                    <x-table-cell>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center shadow-sm border border-custom-blue flex-shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $material->name }}</p>
                                @if($material->unit_measure == 'unidad')
                                    <p class="text-sm text-slate-500">{{ (int)$material->stock_quantity }} {{ $material->stock_quantity == 1 ? 'unidad' : 'unidades' }}</p>
                                @else
                                    <p class="text-sm text-slate-500">{{ number_format($material->piece_size, 3) }}@if($material->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif por pieza</p>
                                @endif
                            </div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            {{ $categoryTranslations[$material->category->name] ?? $material->category->name ?? 'Sin categoría' }}
                        </span>
                    </x-table-cell>
                    <x-table-cell>
                        <div>
                            <p class="font-medium text-slate-800">{{ $material->stock_quantity }} {{ $material->unit_measure == 'unidad' ? 'unidades' : 'piezas' }}</p>
                            @if($material->unit_measure != 'unidad')
                                <p class="text-sm text-slate-500">= {{ number_format($stockInMeters, 3) }}@if($material->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif</p>
                            @endif
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        @if($remaindersCount > 0)
                                    <button 
                                        wire:click="openRemainderModal({{ $material->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 transition-all text-xs font-bold border border-blue-100 group shadow-sm hover:shadow-md hover:-translate-y-0.5 cursor-pointer"
                                    >
                                        <span>{{ (int)$remaindersCount }} retazo{{ $remaindersCount > 1 ? 's' : '' }}</span>
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    <p class="text-[11px] font-medium text-slate-400 mt-1 pl-1">= {{ number_format($remaindersTotal, 3) }}@if($material->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif</p>
                                @else
                                    <span class="text-xs font-medium text-slate-400 px-2 py-1 bg-slate-50 rounded-lg">Sin retazos</span>
                                @endif
                    </x-table-cell>
                    <x-table-cell>
                        <span class="font-semibold text-slate-800">
                            @if($material->unit_measure == 'unidad')
                                {{ (int)$material->stock_quantity }} {{ $material->stock_quantity == 1 ? 'unidad' : 'unidades' }}
                            @else
                                {{ number_format($totalAvailable, 3) }}@if($material->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif
                            @endif
                        </span>
                    </x-table-cell>
                    <x-table-cell align="center">
                        @if($isOutOfStock)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Sin Stock
                            </span>
                        @elseif($isLowStock)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Bajo Stock
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Disponible
                            </span>
                        @endif
                    </x-table-cell>
                    <x-table-cell align="center">
                        <div class="flex justify-center space-x-2">
                            <x-action-button 
                                color="indigo"
                                tooltip="Editar material"
                                wire:click="editMaterial({{ $material->id }})"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </x-action-button>
                            
                            <x-action-button 
                                color="blue"
                                tooltip="Agregar stock"
                                wire:click="openAddStockModal({{ $material->id }})"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </x-action-button>
                        </div>
                    </x-table-cell>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-500">
                        @if(!empty($search) || $filterLowStock || $filterOutOfStock || $filterByType !== 'all')
                            No se encontraron materiales con los filtros aplicados.
                        @else
                            No hay materiales registrados en el inventario.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-table-container>

    <!-- Modal: Agregar Stock -->
    @if($showAddStockModal && $selectedMaterial)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeAddStockModal"></div>
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto overflow-hidden relative z-[60] transform transition-all scale-100">
                <!-- Header -->
                <div class="bg-custom-blue px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6a4 4 0 00-4 4v2a2 2 0 002 2h16a2 2 0 002-2v-2a4 4 0 00-4-4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                Agregar Stock
                            </h3>
                            <p class="text-sm text-teal-200">Añadir existencias al inventario</p>
                        </div>
                    </div>
                    <button 
                        wire:click="closeAddStockModal"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg p-2 transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-slate-50 rounded-lg p-4 mb-5 border border-slate-200">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-slate-800">{{ $selectedMaterial->name }}</p>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-teal-200 text-teal-600">
                                {{ $selectedMaterial->category->name ?? 'Sin categoría' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600">
                            <span class="font-medium">Stock actual:</span>
                            <span>{{ $selectedMaterial->stock_quantity }} {{ $selectedMaterial->unit_measure == 'unidad' ? 'unidades' : 'piezas' }}</span>
                            @if($selectedMaterial->unit_measure != 'unidad')
                                <span class="text-slate-400">({{ number_format($selectedMaterial->stock_quantity * $selectedMaterial->piece_size, 3) }}@if($selectedMaterial->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif)</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Cantidad a agregar ({{ $selectedMaterial->unit_measure == 'unidad' ? 'unidades' : 'piezas' }})
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="quantity"
                                min="1"
                                class="w-full px-4 py-2.5 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 text-sm transition-shadow shadow-sm"
                                placeholder="0"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 text-sm">
                                    {{ $selectedMaterial->unit_measure == 'unidad' ? 'und' : 'pzas' }}
                                </span>
                            </div>
                        </div>
                        @error('quantity')
                            <p class="text-red-600 text-xs mt-1.5 flex items-center font-medium">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    @if($quantity > 0)
                        <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100 animate-in fade-in slide-in-from-top-1 duration-200">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-emerald-800 uppercase tracking-wide">Nuevo Stock Proyectado</p>
                            </div>
                            <div class="pl-7">
                                <p class="text-lg font-bold text-emerald-700">
                                    {{ $selectedMaterial->stock_quantity + $quantity }} {{ $selectedMaterial->unit_measure == 'unidad' ? 'unidades' : 'piezas' }}
                                </p>
                                @if($selectedMaterial->unit_measure != 'unidad')
                                    <p class="text-sm text-emerald-600">
                                        Total: {{ number_format(($selectedMaterial->stock_quantity + $quantity) * $selectedMaterial->piece_size, 3) }}@if($selectedMaterial->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 justify-end">
                    <button 
                        wire:click="closeAddStockModal"
                        class="px-4 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 hover:text-slate-900 font-medium text-sm transition-colors shadow-sm cursor-pointer"
                    >
                        Cancelar
                    </button>
                    <button 
                        wire:click="addStock"
                        class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-medium text-sm transition-all shadow-sm hover:shadow-md flex items-center gap-2 cursor-pointer"
                    >
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Ver Retazos -->
    @if($showRemainderModal && $selectedMaterial)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeRemainderModal"></div>
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden flex flex-col relative z-[60] transform transition-all scale-100">
                <!-- Header -->
                <div class="bg-custom-blue px-6 py-4 border-b border-slate-100 flex justify-between items-center z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M9 17a4 4 0 01-4-4V7a4 4 0 014-4h6a4 4 0 014 4v6a4 4 0 01-4 4M9 17h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                Retazos Disponibles
                            </h3>
                            <p class="text-sm text-teal-200 font-medium">{{ $selectedMaterial->name }}</p>
                        </div>
                    </div>
                    <button 
                        wire:click="closeRemainderModal"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg p-2 transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                    @if($selectedMaterial->remainders->count() > 0)
                        <div class="mb-6 bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-slate-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-slate-800">
                                    Total en retazos: 
                                    <span class="text-slate-700 font-bold">
                                        {{ number_format($selectedMaterial->remainders->sum('remaining_length'), 3) }}@if($selectedMaterial->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif
                                    </span>
                                </p>
                            </div>
                            <p class="text-sm text-slate-600 ml-7 mt-0.5 font-medium">
                                {{ $selectedMaterial->remainders->count() }} retazo{{ $selectedMaterial->remainders->count() > 1 ? 's' : '' }} disponible{{ $selectedMaterial->remainders->count() > 1 ? 's' : '' }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            @foreach($selectedMaterial->remainders as $index => $remainder)
                                <div class="bg-white hover:bg-slate-50 rounded-xl p-4 border border-slate-200 transition-all shadow-sm group" x-data="{ openHistory: false }">
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold border border-slate-200">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-700">Retazo #{{ $index + 1 }}</p>
                                                    <p class="text-xs text-slate-400">ID: {{ $remainder->id }}</p>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-300 shadow-sm">
                                                <svg class="w-3 h-3 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Disponible
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-slate-800 tracking-tight pl-11">
                                                {{ number_format($remainder->remaining_length, 3) }}<span class="text-sm font-normal text-slate-500 ml-1">@if($selectedMaterial->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif</span>
                                            </p>
                                            @if($remainder->notes)
                                                <p class="text-sm text-slate-500 mt-2 pl-11 flex items-start gap-1.5">
                                                    <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    {{ $remainder->notes }}
                                                </p>
                                            @endif
                                            
                                            <!-- Botón para ver historial -->
                                            @if($remainder->movements->count() > 0)
                                                @php
                                                    // Agrupar movimientos por orden
                                                    $groupedMovements = $remainder->movements->groupBy('order_id');
                                                    $totalUsos = $groupedMovements->count();
                                                @endphp
                                                <div class="mt-4 pl-11">
                                                    <button 
                                                        type="button" 
                                                        @click="openHistory = !openHistory"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-teal-200 text-teal-700 hover:text-teal-900 rounded-lg transition-all text-xs font-medium border border-teal-300 cursor-pointer"
                                                    >
                                                        <svg class="w-3.5 h-3.5 transition-transform text-slate-500" :class="{'rotate-180': openHistory}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Historial ({{ $totalUsos }} uso{{ $totalUsos > 1 ? 's' : '' }})</span>
                                                    </button>
                                                    
                                                    <!-- Historial colapsable -->
                                                    <div 
                                                        x-show="openHistory" 
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                                        x-transition:leave="transition ease-in duration-150"
                                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                        class="bg-white border border-slate-200 rounded-xl mt-3 p-4 space-y-3 shadow-sm"
                                                        style="display: none;"
                                                    >
                                                        <div class="flex items-center gap-2 pb-2.5 border-b border-slate-200 mb-1">
                                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Historial de Usos</h4>
                                                        </div>
                                                        
                                                        @foreach($groupedMovements as $orderId => $movements)
                                                            @php
                                                                $totalQuantity = $movements->sum(function($m) { return abs($m->quantity); });
                                                                $firstMovement = $movements->first();
                                                                $cortesCount = $movements->count();
                                                            @endphp
                                                            <div class="bg-white rounded-lg p-3 border-l-4 border-red-400 shadow-sm hover:shadow-md transition-shadow">
                                                                <div class="flex items-start justify-between gap-3">
                                                                    <div class="flex items-start gap-2 flex-1">
                                                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                                                                            @if($orderId)
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                                                </svg>
                                                                            @else
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                                                </svg>
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex-1">
                                                                            <div class="flex items-center gap-2 mb-1">
                                                                                <p class="font-semibold text-slate-700 text-sm">
                                                                                    {{ $orderId ? 'Orden #' . $orderId : 'Ajuste de inventario' }}
                                                                                </p>
                                                                                @if($cortesCount > 1)
                                                                                    <span class="inline-flex items-center gap-1 text-[10px] uppercase font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>
                                                                                        </svg>
                                                                                        {{ $cortesCount }} Cortes
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            
                                                                            <div class="text-xs text-slate-500 space-y-0.5">
                                                                                <p>{{ $firstMovement->created_at->format('d/m/Y H:i') }} · {{ $firstMovement->user->name ?? 'Usuario' }}</p>
                                                                                @if($orderId && $firstMovement->order)
                                                                                    <p class="text-slate-400">Cliente: {{ $firstMovement->order->client_name ?? 'N/A' }}</p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="text-right">
                                                                            <span class="block text-sm font-bold text-slate-700">
                                                                                -{{ number_format($totalQuantity, 3) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        
                                                        <div class="pt-3 mt-1 border-t border-slate-200 flex items-center justify-between bg-red-50 rounded-lg px-3 py-2 border border-red-100">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs font-bold text-red-700 uppercase">Total usado del retazo:</span>
                                                            </div>
                                                            <span class="text-sm font-bold text-red-700">
                                                                {{ number_format($remainder->movements->sum(function($m) { return abs($m->quantity); }), 3) }}@if($selectedMaterial->unit_measure == 'metros_cuadrados')m²@else{{ 'm' }}@endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">No hay retazos disponibles para este material.</p>
                            <p class="text-sm text-slate-400 mt-1">Los retazos aparecerán aquí cuando se generen sobrantes de cortes.</p>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button 
                        wire:click="closeRemainderModal"
                        class="w-full px-4 py-2.5 bg-white text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 font-medium transition-all border border-slate-300 shadow-sm cursor-pointer"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Editar Material -->
    @if($showEditModal && $editingMaterial)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="closeEditModal"></div>
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col relative z-[60] transform transition-all scale-100">
                <!-- Header -->
                <div class="bg-custom-blue px-6 py-4 border-b border-slate-100 flex justify-between items-center z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-cyan-50 text-cyan-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.94l-4.243 1.415 1.415-4.243a4 4 0 01.94-1.414z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                Editar Material
                            </h3>
                            <p class="text-sm text-teal-200">Modificar información del material</p>
                        </div>
                    </div>
                    <button 
                        wire:click="closeEditModal"
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg p-2 transition-colors cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                    <livewire:admin.materials.materials-form :material="$editingMaterial" :key="$editingMaterial->id" />
                </div>
            </div>
        </div>
    @endif
</div>