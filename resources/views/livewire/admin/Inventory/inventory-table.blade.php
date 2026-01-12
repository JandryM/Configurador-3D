<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Inventario"
        description="Administra el stock de materiales y retazos disponibles"
        gradient="from-green-600 to-emerald-700"
        :show-button="false"
        icon-gradient="from-blue-500 to-cyan-600"
    >
        <x-slot name="icon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
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
            gradient="from-blue-500 to-cyan-600"
            hover-color="blue-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Bajo Stock" 
            :value="$lowStock"
            gradient="from-yellow-500 to-orange-600"
            hover-color="yellow-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Sin Stock" 
            :value="$outOfStock"
            gradient="from-red-500 to-pink-600"
            hover-color="red-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Retazos" 
            :value="$totalRemainders"
            gradient="from-purple-500 to-indigo-600"
            hover-color="purple-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
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
        <div class="bg-white border-b border-slate-200 shadow-lg mb-2 md:mb-3 lg:mb-4">
            <div class="flex flex-wrap gap-1 sm:gap-1.5 md:gap-2 lg:gap-3 xl:gap-3 2xl:gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px] sm:min-w-[220px] md:min-w-[250px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar Material
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nombre o descripción..."
                            class="w-full pl-8 sm:pl-9 md:pl-10 lg:pl-10 xl:pl-8 2xl:pl-10 pr-2 sm:pr-3 md:pr-4 lg:pr-4 xl:pr-3 2xl:pr-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm hover:border-slate-300 shadow-lg"
                        >
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5 text-slate-400 absolute left-2 sm:left-2.5 md:left-3 lg:left-3 xl:left-2 2xl:left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por categoría -->
                @php
                    $categories = \App\Models\Category::orderBy('name')->get();
                @endphp
                <div class="min-w-[180px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Categoría
                    </label>
                    <select 
                        wire:model.live="category_id"
                        class="w-full px-2 sm:px-3 md:px-4 lg:px-4 xl:px-3 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 active:scale-[0.98] transition-all duration-200 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm cursor-pointer shadow-md font-medium {{ $category_id !== '' ? 'border-green-400 bg-gradient-to-br from-green-100 to-green-50 text-green-900 shadow-lg hover:border-green-500 hover:shadow-xl hover:from-green-200 hover:to-green-100' : 'border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100/50 text-slate-800 hover:border-green-300 hover:shadow-lg hover:from-green-50 hover:to-slate-50' }} [&>option]:bg-white [&>option]:text-slate-800 [&>option]:py-2 [&>option:hover]:bg-gradient-to-r [&>option:hover]:from-green-50 [&>option:hover]:to-green-100 [&>option:checked]:bg-green-500 [&>option:checked]:text-white"
                    >
                        <option value="" class="py-2">✨ Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" class="py-2">
                                @if($cat->parent_id)
                                    &nbsp;&nbsp;└ {{ $cat->name }}
                                @else
                                    {{ $cat->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por tipo -->
                <div class="min-w-[140px] sm:min-w-[150px] md:min-w-[180px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Tipo
                    </label>
                    <select 
                        wire:model.live="filterByType"
                        class="w-full px-2 sm:px-3 md:px-4 lg:px-4 xl:px-3 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 active:scale-[0.98] transition-all duration-200 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm cursor-pointer shadow-md font-medium {{ $filterByType !== 'all' ? 'border-blue-400 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-900 shadow-lg hover:border-blue-500 hover:shadow-xl hover:from-blue-200 hover:to-blue-100' : 'border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100/50 text-slate-800 hover:border-blue-300 hover:shadow-lg hover:from-blue-50 hover:to-slate-50' }} [&>option]:bg-white [&>option]:text-slate-800 [&>option]:py-2 [&>option:hover]:bg-gradient-to-r [&>option:hover]:from-blue-50 [&>option:hover]:to-blue-100 [&>option:checked]:bg-blue-500 [&>option:checked]:text-white"
                    >
                        <option value="all" class="py-2">✨ Todos</option>
                        <option value="by_piece" class="py-2">📦 Por Pieza</option>
                        <option value="by_unit" class="py-2">📊 Por Unidad</option>
                    </select>
                </div>

                <!-- Filtros de estado -->
                <div>
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filtros
                    </label>
                    <div class="flex flex-wrap gap-1 sm:gap-1.5 md:gap-2 lg:gap-2 xl:gap-1.5 2xl:gap-2">
                        <button 
                            wire:click="toggleLowStockFilter"
                            class="px-2 sm:px-2.5 md:px-3 lg:px-4 xl:px-2.5 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 rounded-xl font-medium transition-all shadow-lg border-2 {{ $filterLowStock ? 'border-yellow-500 bg-yellow-100 text-yellow-900 ring-2 ring-yellow-300 hover:bg-yellow-200 hover:border-yellow-600 hover:shadow-xl' : 'border-yellow-200 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 hover:border-yellow-300' }} text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm"
                        >
                            <span class="flex items-center gap-1 sm:gap-1.5 md:gap-2 lg:gap-2 xl:gap-1.5 2xl:gap-2">
                                @if($filterLowStock)
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @endif
                                <span>Bajo Stock</span>
                            </span>
                        </button>

                        <button 
                            wire:click="toggleOutOfStockFilter"
                            class="px-2 sm:px-2.5 md:px-3 lg:px-4 xl:px-2.5 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 rounded-xl font-medium transition-all shadow-lg border-2 {{ $filterOutOfStock ? 'border-red-500 bg-red-100 text-red-900 ring-2 ring-red-300 hover:bg-red-200 hover:border-red-600 hover:shadow-xl' : 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100 hover:border-red-300' }} text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm"
                        >
                            <span class="flex items-center gap-1 sm:gap-1.5 md:gap-2 lg:gap-2 xl:gap-1.5 2xl:gap-2">
                                @if($filterOutOfStock)
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                @else
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                @endif
                                <span>Sin Stock</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-gradient-to-r from-blue-50 via-cyan-50 to-teal-50 border-b-2 border-cyan-200 shadow-sm">
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
                            <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-700 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $material->name }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($material->piece_size, 3) }}{{ $material->unit }} por pieza</p>
                            </div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            {{ $material->category->name ?? 'Sin categoría' }}
                        </span>
                    </x-table-cell>
                    <x-table-cell>
                        <div>
                            <p class="font-medium text-slate-800">{{ $material->stock_quantity }} {{ $material->unit_measure == 'unidad' ? 'unidades' : 'piezas' }}</p>
                            <p class="text-sm text-slate-500">= {{ number_format($stockInMeters, 3) }}{{ $material->unit }}</p>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        @if($remaindersCount > 0)
                            <button 
                                wire:click="openRemainderModal({{ $material->id }})"
                                class="text-blue-600 hover:text-blue-800 transition-colors font-medium text-sm"
                            >
                                {{ $remaindersCount }} retazo{{ $remaindersCount > 1 ? 's' : '' }}
                            </button>
                            <p class="text-sm text-slate-500">= {{ number_format($remaindersTotal, 3) }}{{ $material->unit }}</p>
                        @else
                            <span class="text-sm text-slate-400">Sin retazos</span>
                        @endif
                    </x-table-cell>
                    <x-table-cell>
                        <span class="font-semibold text-slate-800">
                            {{ number_format($totalAvailable, 3) }}{{ $material->unit }}
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Bajo Stock
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeAddStockModal"></div>
            <div class="bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden relative z-[60]" style="background-color: #1e293b !important;">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-cyan-400 to-blue-500">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6a4 4 0 00-4 4v2a2 2 0 002 2h16a2 2 0 002-2v-2a4 4 0 00-4-4z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">
                            Agregar Stock
                        </h3>
                    </div>
                    <button 
                        wire:click="closeAddStockModal"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-slate-700 rounded-xl p-4 mb-4 border border-slate-600">
                        <p class="font-semibold text-white">{{ $selectedMaterial->name }}</p>
                        <p class="text-sm text-slate-300 mt-1">
                            Stock actual: <span class="font-medium">{{ $selectedMaterial->stock_quantity }} {{ $selectedMaterial->unit == 'm' ? 'piezas' : 'unidades' }}</span>
                            <span class="text-slate-400">({{ number_format($selectedMaterial->stock_quantity * $selectedMaterial->piece_size, 3) }}{{ $selectedMaterial->unit }})</span>
                        </p>
                    </div>

                    <label class="block text-sm font-medium text-slate-200 mb-2">
                        Cantidad a agregar ({{ $selectedMaterial->unit == 'm' ? 'piezas' : 'unidades' }})
                    </label>
                    <input 
                        type="number" 
                        wire:model="quantity"
                        min="1"
                        class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Ingrese la cantidad"
                    >
                    @error('quantity')
                        <p class="text-red-500 text-xs mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </p>
                    @enderror

                @if($quantity > 0)
                    <div class="bg-green-900/30 rounded-xl p-4 mt-4 border border-green-700">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-medium text-green-300">Stock después de agregar:</p>
                        </div>
                        <p class="text-lg font-semibold text-green-400 ml-7">
                            {{ $selectedMaterial->stock_quantity + $quantity }} {{ $selectedMaterial->unit == 'm' ? 'piezas' : 'unidades' }}
                            <span class="text-sm font-normal text-green-500">
                                ({{ number_format(($selectedMaterial->stock_quantity + $quantity) * $selectedMaterial->piece_size, 3) }}{{ $selectedMaterial->unit }})
                            </span>
                        </p>
                    </div>
                @endif
                </div>

                <div class="flex gap-3 p-6 pt-0">
                    <button 
                        wire:click="closeAddStockModal"
                        class="flex-1 px-4 py-3 bg-slate-700 text-slate-200 rounded-xl hover:bg-slate-600 font-medium transition-all border border-slate-600"
                    >
                        Cancelar
                    </button>
                    <button 
                        wire:click="addStock"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl hover:shadow-lg font-medium transition-all"
                    >
                        Agregar Stock
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Ver Retazos -->
    @if($showRemainderModal && $selectedMaterial)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeRemainderModal"></div>
            <div class="bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col relative z-[60]" style="background-color: #1e293b !important;">
                <!-- Header con gradiente -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-pink-400 to-purple-500">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M9 17a4 4 0 01-4-4V7a4 4 0 014-4h6a4 4 0 014 4v6a4 4 0 01-4 4M9 17h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                Retazos Disponibles
                            </h3>
                            <p class="text-sm text-white text-opacity-90">{{ $selectedMaterial->name }}</p>
                        </div>
                    </div>
                    <button 
                        wire:click="closeRemainderModal"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    @if($selectedMaterial->remainders->count() > 0)
                        <div class="mb-6 bg-gradient-to-r from-purple-900/30 to-pink-900/30 rounded-xl p-4 border border-purple-700">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                                <p class="font-semibold text-white">
                                    Total en retazos: 
                                    <span class="text-purple-400">
                                        {{ number_format($selectedMaterial->remainders->sum('remaining_length'), 3) }}{{ $selectedMaterial->unit }}
                                    </span>
                                </p>
                            </div>
                            <p class="text-sm text-slate-300 ml-7 mt-1">
                                {{ $selectedMaterial->remainders->count() }} retazo{{ $selectedMaterial->remainders->count() > 1 ? 's' : '' }} disponible{{ $selectedMaterial->remainders->count() > 1 ? 's' : '' }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            @foreach($selectedMaterial->remainders as $index => $remainder)
                                <div class="bg-slate-700 rounded-xl p-4 border border-slate-600 transition-colors" x-data="{ openHistory: false }">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full text-white text-xs font-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                                <p class="text-sm font-medium text-slate-300">Retazo #{{ $index + 1 }} <span class="text-xs text-slate-500">(ID: {{ $remainder->id }})</span></p>
                                            </div>
                                            <p class="text-2xl font-bold text-purple-400">
                                                {{ number_format($remainder->remaining_length, 3) }}{{ $selectedMaterial->unit }}
                                            </p>
                                            @if($remainder->notes)
                                                <p class="text-sm text-slate-400 mt-2 pl-8">
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
                                                <div class="mt-3">
                                                    <button 
                                                        type="button" 
                                                        @click="openHistory = !openHistory"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-500/20 hover:bg-purple-500/30 text-purple-200 hover:text-purple-100 rounded-lg transition-all text-xs font-medium border border-purple-400/30"
                                                    >
                                                        <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': openHistory}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Historial ({{ $totalUsos }} uso{{ $totalUsos > 1 ? 's' : '' }})</span>
                                                    </button>
                                                    
                                                    <!-- Historial colapsable -->
                                                    <div 
                                                        x-show="openHistory" 
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                        x-transition:enter-end="opacity-100 transform scale-100"
                                                        x-transition:leave="transition ease-in duration-150"
                                                        x-transition:leave-start="opacity-100 transform scale-100"
                                                        x-transition:leave-end="opacity-0 transform scale-95"
                                                        class="bg-slate-800 border border-slate-600 rounded-xl mt-3 p-4 space-y-3 shadow-lg"
                                                        style="display: none;"
                                                    >
                                                        <div class="flex items-center gap-2 pb-3 border-b border-slate-600">
                                                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                            </svg>
                                                            <h4 class="text-sm font-semibold text-slate-200">Historial de Usos</h4>
                                                        </div>
                                                        
                                                        @foreach($groupedMovements as $orderId => $movements)
                                                            @php
                                                                $totalQuantity = $movements->sum(function($m) { return abs($m->quantity); });
                                                                $firstMovement = $movements->first();
                                                                $cortesCount = $movements->count();
                                                            @endphp
                                                            <div class="bg-slate-700/50 rounded-lg p-3 border-l-4 {{ $orderId ? 'border-red-400' : 'border-green-400' }}">
                                                                <div class="flex items-start justify-between gap-3">
                                                                    <div class="flex items-start gap-2 flex-1">
                                                                        <div class="w-8 h-8 rounded-lg {{ $orderId ? 'bg-red-500/20' : 'bg-green-500/20' }} flex items-center justify-center flex-shrink-0">
                                                                            @if($orderId)
                                                                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                                                </svg>
                                                                            @else
                                                                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                                                </svg>
                                                                            @endif
                                                                        </div>
                                                                        <div class="flex-1">
                                                                            <div class="flex items-center gap-2 mb-1">
                                                                                <p class="font-semibold {{ $orderId ? 'text-red-300' : 'text-green-300' }} text-sm">
                                                                                    {{ $orderId ? 'Orden #' . $orderId : 'Ajuste de inventario' }}
                                                                                </p>
                                                                                @if($cortesCount > 1)
                                                                                    <span class="inline-flex items-center gap-1 text-xs {{ $orderId ? 'text-red-300 bg-red-500/20' : 'text-green-300 bg-green-500/20' }} px-2 py-0.5 rounded-full">
                                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>
                                                                                        </svg>
                                                                                        {{ $cortesCount }} corte{{ $cortesCount > 1 ? 's' : '' }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="space-y-1">
                                                                                <div class="flex items-center gap-2 text-xs">
                                                                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                                    </svg>
                                                                                    <span class="text-slate-300">Cantidad:</span>
                                                                                    <span class="font-semibold text-white">{{ number_format($totalQuantity, 3) }}</span>
                                                                                    <span class="text-slate-400">{{ $selectedMaterial->unit }}</span>
                                                                                </div>
                                                                                <div class="flex items-center gap-2 text-xs text-slate-400">
                                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                                    </svg>
                                                                                    {{ \Carbon\Carbon::parse($firstMovement->created_at)->format('d/m/Y H:i') }}
                                                                                    @if($firstMovement->user)
                                                                                        <span class="text-slate-500">•</span>
                                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                                        </svg>
                                                                                        {{ $firstMovement->user->name }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        
                                                        <div class="pt-3 mt-1 border-t border-slate-600 flex items-center justify-between bg-purple-500/10 rounded-lg px-3 py-2">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs font-medium text-slate-300">Total usado del retazo:</span>
                                                            </div>
                                                            <span class="text-sm font-bold text-purple-300">
                                                                {{ number_format($remainder->movements->sum(function($m) { return abs($m->quantity); }), 3) }}{{ $selectedMaterial->unit }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Disponible
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-slate-400">No hay retazos disponibles para este material.</p>
                        </div>
                    @endif
                </div>

                <div class="p-6 pt-0">
                    <button 
                        wire:click="closeRemainderModal"
                        class="w-full px-4 py-3 bg-slate-700 text-slate-200 rounded-xl hover:bg-slate-600 font-medium transition-all border border-slate-600"
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
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeEditModal"></div>
            <div class="bg-slate-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col relative z-[60]" style="background-color: #1e293b !important;">
                <!-- Header con gradiente -->
                <div class="sticky top-0 bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-4 flex justify-between items-center z-10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-500">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a4 4 0 01-1.414.94l-4.243 1.415 1.415-4.243a4 4 0 01.94-1.414z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">
                            Editar Material
                        </h3>
                    </div>
                    <button 
                        wire:click="closeEditModal"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto flex-1">
                    <livewire:admin.materials.materials-form :material="$editingMaterial" :key="$editingMaterial->id" />
                </div>
            </div>
        </div>
    @endif
</div>