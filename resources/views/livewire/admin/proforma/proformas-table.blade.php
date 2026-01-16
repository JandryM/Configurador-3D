<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Proformas"
        description="Administra cotizaciones y propuestas comerciales"
        gradient="from-pink-400 to-fuchsia-500"
        :show-button="false"
        icon-gradient="from-pink-500 to-fuchsia-600"
    >
        <x-slot name="icon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
    </x-page-header>

    <!-- Estadísticas -->
    <x-stats-grid columns="3">
        <x-stat-card 
            title="Total Proformas" 
            :value="$total"
            gradient="from-pink-500 to-fuchsia-600"
            hover-color="pink-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Ordenadas" 
            :value="$allProformas->filter(fn($p) => DB::table('orders')->where('proforma_id', $p['id'])->exists())->count()"
            gradient="from-green-500 to-emerald-600"
            hover-color="green-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Expiradas" 
            :value="$allProformas->filter(fn($p) => $p['is_expired'])->count()"
            gradient="from-red-500 to-pink-500"
            hover-color="red-300"
        >
            <x-slot name="icon">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>
    </x-stats-grid>

    <!-- Tabla de proformas -->
    <x-table-container
        :has-pagination="true"
        :page="$page"
        :per-page="$perPage"
        :total="$total"
        item-name="proforma"
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
                        Buscar Proforma
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Número o cliente..."
                            class="w-full pl-8 sm:pl-9 md:pl-10 lg:pl-10 xl:pl-8 2xl:pl-10 pr-2 sm:pr-3 md:pr-4 lg:pr-4 xl:pr-3 2xl:pr-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-pink-500 focus:bg-white transition-all text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm hover:border-slate-300 shadow-lg"
                        >
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5 text-slate-400 absolute left-2 sm:left-2.5 md:left-3 lg:left-3 xl:left-2 2xl:left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por estado de orden -->
                <div class="flex items-end">
                    <button 
                        wire:click="toggleFilterOrdered"
                        class="px-2 sm:px-2.5 md:px-3 lg:px-4 xl:px-2.5 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 rounded-xl font-medium transition-all shadow-lg border-2 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm 
                        {{ $filterOrdered === true ? 'bg-green-100 border-green-500 text-green-800 hover:bg-green-200 hover:border-green-600 hover:shadow-xl' : 
                           ($filterOrdered === 'inverse' ? 'bg-red-100 border-red-500 text-red-800 hover:bg-red-200 hover:border-red-600 hover:shadow-xl' : 
                           'bg-slate-100 border-slate-300 text-slate-600 hover:bg-slate-200 hover:border-slate-400') }}"
                    >
                        <span class="flex items-center gap-1 sm:gap-1.5 md:gap-2 lg:gap-2 xl:gap-1.5 2xl:gap-2">
                            @if($filterOrdered === true)
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Ordenadas</span>
                            @elseif($filterOrdered === 'inverse')
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>No Ordenadas</span>
                            @else
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Ordenadas</span>
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Filtro por expiradas -->
                <div class="flex items-end">
                    <button 
                        wire:click="toggleFilterExpired"
                        class="px-2 sm:px-2.5 md:px-3 lg:px-4 xl:px-2.5 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 rounded-xl font-medium transition-all shadow-lg border-2 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm 
                        {{ $filterExpired === true ? 'bg-red-100 border-red-500 text-red-800 hover:bg-red-200 hover:border-red-600 hover:shadow-xl' : 
                           ($filterExpired === 'inverse' ? 'bg-green-100 border-green-500 text-green-800 hover:bg-green-200 hover:border-green-600 hover:shadow-xl' : 
                           'bg-slate-100 border-slate-300 text-slate-600 hover:bg-slate-200 hover:border-slate-400') }}"
                    >
                        <span class="flex items-center gap-1 sm:gap-1.5 md:gap-2 lg:gap-2 xl:gap-1.5 2xl:gap-2">
                            @if($filterExpired === true)
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Expiradas</span>
                            @elseif($filterExpired === 'inverse')
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>No Expiradas</span>
                            @else
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-4 lg:h-4 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Expiradas</span>
                            @endif
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-gradient-to-r from-pink-50 via-fuchsia-50 to-purple-50 border-b-2 border-pink-200 shadow-sm">
                <x-table-header>Número</x-table-header>
                <x-table-header>Cliente</x-table-header>
                <x-table-header>Fecha Expiración</x-table-header>
                <x-table-header>Cantidad de Productos</x-table-header>
                <x-table-header>Total</x-table-header>
                <x-table-header align="center">¿Ordenada?</x-table-header>
                <x-table-header align="center">¿Expirada?</x-table-header>
                <x-table-header align="center">Acciones</x-table-header>
            </tr>
        </thead>
        <tbody>
            @forelse($proformas as $proforma)
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                    <x-table-cell>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-fuchsia-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $proforma['number'] }}</p>
                                <p class="text-sm text-slate-500">Proforma</p>
                            </div>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <div>
                            <p class="font-medium text-slate-800">{{ $proforma['client'] }}</p>
                            <p class="text-sm text-slate-500">Cliente</p>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <span class="text-slate-700">
                            {{ $proforma['expiration_date'] ? \Carbon\Carbon::parse($proforma['expiration_date'])->format('d/m/Y H:i') : '-' }}
                        </span>
                    </x-table-cell>
                    <x-table-cell>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $proforma['total_quantity'] }} {{ $proforma['total_quantity'] == 1 ? 'producto' : 'productos' }}
                            </span>
                            <span class="text-xs text-slate-500">({{ $proforma['items_count'] }} {{ $proforma['items_count'] == 1 ? 'config' : 'configs' }})</span>
                        </div>
                    </x-table-cell>
                    <x-table-cell>
                        <span class="font-medium text-slate-800">
                            ${{ number_format($proforma['total_price'], 2) }}
                        </span>
                    </x-table-cell>
                    <x-table-cell align="center">
                        @php
                            $hasOrder = DB::table('orders')->where('proforma_id', $proforma['id'])->exists();
                        @endphp
                        @if($hasOrder)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Sí
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                No
                            </span>
                        @endif
                    </x-table-cell>
                    <x-table-cell align="center">
                        @if($proforma['is_expired'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Sí
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                No
                            </span>
                        @endif
                    </x-table-cell>
                    <x-table-cell align="center">
                        <div class="flex justify-center space-x-2">
                            <x-action-button 
                                color="blue"
                                tooltip="Ver proforma"
                                wire:click="showProforma({{ $proforma['id'] }})"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                            </x-action-button>
                            <x-action-button 
                                color="purple"
                                tooltip="Descargar PDF"
                                wire:click="downloadProformaPdf({{ $proforma['id'] }})"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </x-action-button>
                        </div>
                    </x-table-cell>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-8 text-slate-500">
                        No hay proformas registradas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-table-container>

    @if($showProformaModal && $selectedProforma)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                <!-- Panel del modal -->
                <div class="bg-slate-900 rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative border border-slate-700" style="max-height: 90vh; display: flex; flex-direction: column;">
                    <!-- Header -->
                    <div class="sticky top-0 bg-gradient-to-r from-pink-400 to-fuchsia-500 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Detalles de Proforma</h2>
                                <p class="text-sm text-pink-100">Información completa de la cotización</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeModal" class="p-2 text-white hover:bg-white/20 rounded-lg transition-colors duration-200 cursor-pointer relative group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </button>
                    </div>
                    <!-- Contenido -->
                    <div class="overflow-y-auto px-6 py-4 bg-slate-900 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800 hover:scrollbar-thumb-slate-500" style="flex: 1; scrollbar-width: thin; scrollbar-color: #475569 #1e293b;">
                        @if(empty($selectedProforma['items']) || count($selectedProforma['items']) == 0)
                            <div class="text-center text-gray-400 py-8">No hay ítems en esta proforma.</div>
                        @else
                            @include('livewire.proformas.proforma-admin', [
                                'items' => $selectedProforma['items'],
                                'user' => $selectedProforma['user'],
                                'total_price' => $selectedProforma['total_price'],
                                'number' => $selectedProforma['number'],
                                'expiration_date' => $selectedProforma['expiration_date'],
                                'is_expired' => $selectedProforma['is_expired'],
                                'showDownloadButton' => false
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
