<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Proformas"
        description="Administra cotizaciones y propuestas comerciales"
        :show-button="false"
        icon-gradient="from-orange-500 via-amber-500 to-teal-500"
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
            :value="$allProformas->count()"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Ordenadas" 
            :value="$allProformas->filter(fn($p) => DB::table('orders')->where('proforma_id', $p['id'])->exists())->count()"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Expiradas" 
            :value="$allProformas->filter(fn($p) => $p['is_expired'])->count()"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
        <div class="bg-white border-b border-slate-200 mb-4 px-4 py-3">
            <div class="flex flex-wrap gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Buscar Proforma
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Número o cliente..."
                            class="w-full pl-9 pr-3 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 text-sm transition-shadow"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtros de estado -->
                <div class="flex gap-2">
                    <button 
                        wire:click="toggleFilterOrdered"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors border {{ $filterOrdered === true ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}"
                    >
                        Ordenadas
                    </button>
                    <button 
                        wire:click="toggleFilterExpired"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors border {{ $filterExpired === true ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }}"
                    >
                        Expiradas
                    </button>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-slate-50 border-b-2 border-slate-200">
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
                            <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center shadow-sm">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
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
                                color="teal"
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
                                wire:click="openDownloadModal({{ $proforma['id'] }})"
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
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative border border-slate-200" style="max-height: 90vh; display: flex; flex-direction: column;">
                    <!-- Header -->
                    <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center border border-teal-100 shadow-sm">
                                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Detalles de Proforma</h2>
                                <p class="text-sm text-slate-600">Información completa de la cotización</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeModal" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors duration-200 cursor-pointer relative group">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Contenido -->
                    <div class="overflow-y-auto px-6 py-4 bg-white" style="flex: 1;">
                        @if(empty($selectedProforma['items']) || count($selectedProforma['items']) == 0)
                            <div class="text-center text-slate-500 py-8">No hay ítems en esta proforma.</div>
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

    <!-- Modal de Descarga -->
    @if($showDownloadModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1100;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeDownloadModal"></div>
                
                <!-- Panel del modal -->
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto text-left align-middle transition-all transform relative border border-slate-200">
                    <!-- Header -->
                    <div class="bg-white px-6 py-4 rounded-t-2xl flex justify-between items-center border-b border-slate-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center border border-orange-100 shadow-sm">
                                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Seleccionar Tipo de PDF</h3>
                                <p class="text-sm text-slate-600">Elige el formato que deseas descargar</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeDownloadModal" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="px-6 py-6 space-y-4 bg-white">
                        <!-- Botón PDF Administrativo -->
                        <button 
                            wire:click="downloadProformaPdf({{ $downloadProformaId }})"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-not-allowed"
                            wire:target="downloadProformaPdf,downloadProformaClientPdf"
                            class="w-full bg-white hover:bg-slate-50 border-2 border-slate-200 hover:border-slate-300 text-slate-900 px-6 py-4 rounded-lg font-medium shadow-sm transition-all duration-200 flex items-center justify-between group disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center relative">
                                    <!-- Spinner (visible cuando está cargando ESTE botón) -->
                                    <svg wire:loading wire:target="downloadProformaPdf" class="animate-spin h-6 w-6 text-white absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <!-- Icono normal (oculto cuando está cargando) -->
                                    <svg wire:loading.remove wire:target="downloadProformaPdf" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-lg">
                                        <span wire:loading.remove wire:target="downloadProformaPdf">PDF Administrativo</span>
                                        <span wire:loading wire:target="downloadProformaPdf">Generando PDF...</span>
                                    </div>
                                    <div class="text-sm text-slate-600">Incluye desglose completo de materiales y costos</div>
                                </div>
                            </div>
                            <svg wire:loading.remove wire:target="downloadProformaPdf" class="w-5 h-5 text-slate-600 transform group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Botón PDF del Cliente -->
                        <button 
                            wire:click="downloadProformaClientPdf({{ $downloadProformaId }})"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-not-allowed"
                            wire:target="downloadProformaPdf,downloadProformaClientPdf"
                            class="w-full bg-white hover:bg-slate-50 border-2 border-slate-200 hover:border-slate-300 text-slate-900 px-6 py-4 rounded-lg font-medium shadow-sm transition-all duration-200 flex items-center justify-between group disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-slate-900 rounded-lg flex items-center justify-center relative">
                                    <!-- Spinner (visible cuando está cargando ESTE botón) -->
                                    <svg wire:loading wire:target="downloadProformaClientPdf" class="animate-spin h-6 w-6 text-white absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <!-- Icono normal (oculto cuando está cargando) -->
                                    <svg wire:loading.remove wire:target="downloadProformaClientPdf" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-lg">
                                        <span wire:loading.remove wire:target="downloadProformaClientPdf">PDF del Cliente</span>
                                        <span wire:loading wire:target="downloadProformaClientPdf">Generando PDF...</span>
                                    </div>
                                    <div class="text-sm text-slate-600">Formato simplificado para el cliente final</div>
                                </div>
                            </div>
                            <svg wire:loading.remove wire:target="downloadProformaClientPdf" class="w-5 h-5 text-slate-600 transform group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
