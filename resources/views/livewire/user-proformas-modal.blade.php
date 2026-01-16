<div>
    <!-- Mostrar mensaje de error si existe -->
    @if(session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
            <div class="bg-red-600/90 text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border border-red-400/40 animate-fade-in">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <!-- Mostrar mensaje de éxito si existe -->
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-md px-4">
            <div class="bg-green-600/90 text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border border-green-400/40 animate-fade-in">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    <!-- Modal principal de listado de proformas -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
           <div x-data="{ confirmDelete: false, deleteMessage: null }" class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro -->
            <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative z-10 text-white" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Header -->
                <div class="sticky top-0 bg-black/50 backdrop-blur-sm border-b border-white/20 px-6 py-4 rounded-t-2xl flex justify-between items-center" wire:click="clearSelection">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Mis Proformas</h2>
                            <p class="text-sm text-white/80">Historial de cotizaciones guardadas</p>
                        </div>
                    </div>
                    <div class= "relative group">
                        <button type="button" wire:click="closeModal" class="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Cerrar
                        </span>
                    </div>
                </div>
                
                <!-- Contenido -->
                <div class="overflow-y-auto px-6 py-4" style="flex: 1;" wire:click="clearSelection">
                    @if(empty($proformas))
                        <div class="bg-yellow-500/20 backdrop-blur-sm border border-yellow-500/30 text-yellow-200 p-4 rounded-lg text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p>No tienes proformas guardadas.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white/5 backdrop-blur-sm border border-white/20 rounded-lg" wire:click.stop>
                                <thead>
                                    <tr class="bg-white/10 backdrop-blur-sm">
                                        <th class="px-4 py-3 border-b border-white/20 text-center text-sm font-semibold text-white w-12" onclick="event.stopPropagation()">
                                            <input type="checkbox" 
                                                   wire:model.live="selectAll"
                                                   class="w-4 h-4 rounded border-white/30 bg-white/10 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-0 cursor-pointer">
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Número
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Productos
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Fecha
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Estado
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white relative group">
                                            Total
                                        </th>
                                        <th class="px-4 py-3 border-b border-white/20 text-left text-sm font-semibold text-white min-w-[260px] w-[260px]">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maxRows = 5;
                                        $rowCount = count($proformas);
                                    @endphp
                                    @foreach($proformas as $proforma)
                                        <tr class="hover:bg-white/5 transition-colors cursor-pointer {{ in_array($proforma['id'], $selectedProformas) ? 'bg-cyan-500/10 border-l-4 border-cyan-500' : '' }}"
                                            @if(!$proforma['is_ordered']) wire:click="toggleProformaSelection({{ $proforma['id'] }})" @endif>
                                            <td class="px-4 py-3 border-b border-white/10 text-center" onclick="event.stopPropagation()">
                                                <input type="checkbox" 
                                                       wire:model.live="selectedProformas"
                                                       value="{{ $proforma['id'] }}"
                                                       @if($proforma['is_ordered']) disabled @endif
                                                       class="w-4 h-4 rounded border-white/30 bg-white/10 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-0 {{ $proforma['is_ordered'] ? 'opacity-30 cursor-not-allowed' : 'cursor-pointer' }}">
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-white relative group">
                                                {{ $proforma['number'] }}
                                                <span class="absolute bottom-full left-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Número único de identificación de la proforma
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80 relative group">
                                                {{ $proforma['total_quantity'] }} {{ $proforma['total_quantity'] == 1 ? 'producto' : 'productos' }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Cantidad total de productos incluidos en la proforma
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80 relative group">
                                                {{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y') }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Fecha de creación de la proforma
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm relative group">
                                                @if($proforma['is_expired'])
                                                    <span class="px-2 py-1 bg-red-500/20 backdrop-blur-sm border border-red-500/30 text-red-300 rounded-full text-xs font-semibold">
                                                        Expirada
                                                    </span>
                                                @elseif($proforma['is_ordered'])
                                                    <span class="px-2 py-1 bg-blue-500/20 backdrop-blur-sm border border-blue-500/30 text-blue-300 rounded-full text-xs font-semibold">
                                                        Ordenada
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-green-500/20 backdrop-blur-sm border border-green-500/30 text-green-300 rounded-full text-xs font-semibold">
                                                        Activa
                                                    </span>
                                                @endif
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Estado actual: Activa, Expirada u Ordenada
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-green-400 relative group">
                                                ${{ number_format($proforma['total_price'], 2) }}
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Precio total de la proforma incluyendo todos los productos
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-b border-white/10 min-w-[260px] w-[260px]" onclick="event.stopPropagation()">
                                                <div class="flex gap-2 min-w-[260px] w-[260px]">
                                                    <div class="relative group">
                                                        <button wire:click="showProforma({{ $proforma['id'] }})" 
                                                                class="px-4 text-xs font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-blue-600 to-cyan-700 hover:from-blue-700 hover:to-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl h-[38px] flex items-center justify-center cursor-pointer">
                                                            Ver
                                                        </button>
                                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Ver detalles de la proforma
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button wire:click="downloadProformaPdf({{ $proforma['id'] }})" 
                                                                class="px-4 text-xs font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl h-[38px] flex items-center justify-center cursor-pointer">
                                                            PDF
                                                        </button>
                                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Descargar documento PDF con todos los detalles
                                                        </span>
                                                    </div>
                                                    @if(!$proforma['is_expired'] && !$proforma['is_ordered'])
                                                    <div class="relative group">
                                                        <button wire:click="setConfirmOrderId({{ $proforma['id'] }})"
                                                                @if($confirmOrderId !== null) disabled @endif
                                                                class="px-3 text-xs font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold h-[38px] flex items-center justify-center whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                                            Ordenar Proforma
                                                        </button>
                                                        <span class="absolute bottom-full right-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                            Convertir esta proforma en orden de compra
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @for($i = 0; $i < $maxRows - $rowCount; $i++)
                                        <tr class="h-[56px]">
                                            <td class="px-4 py-3 border-b border-white/10 text-center">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-white">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm text-white/80">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10 text-sm font-semibold text-green-400">&nbsp;</td>
                                            <td class="px-4 py-3 border-b border-white/10">&nbsp;</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Controles de paginación -->
                <div class="px-6 pb-4 pt-2 border-t border-white/20" wire:click.stop>
                    <div class="flex justify-center items-center gap-3">
                        <nav aria-label="Paginación de proformas" class="flex items-center gap-3 w-full justify-center">
                            <div class="flex items-center w-full">
                                <div class="flex-1 flex justify-start gap-2">
                                        <div class="relative group">
                                            <button wire:click="anteriorPaginaProformas" @if($paginaProformas <= 1) disabled aria-disabled="true" @endif
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                                aria-label="Página anterior" tabindex="0">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                                <span class="hidden sm:inline"></span>
                                            </button>
                                            <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                Página anterior
                                            </span>
                                        </div>
                                        @if(count($selectedProformas) > 0)
                                            <div class="relative group">
                                                <button @click="confirmDelete = true"
                                                        :disabled="confirmDelete"
                                                        class="flex items-center gap-2 px-3 py-1.5 text-xs font-medium border border-red-600/40 rounded-lg text-white bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar ({{ count($selectedProformas) }})
                                                </button>
                                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                                    Eliminar permanentemente las proformas seleccionadas
                                                </span>
                                            </div>
                                        @endif
                                </div>
                                <div class="flex flex-col items-center">
                                    <div x-data="{
                                        paginaActual: @entangle('paginaProformas'),
                                        totalPaginas: {{ ceil($totalProformas / $proformasPorPagina) }},
                                        todasPaginas() {
                                            let arr = [];
                                            for (let i = 1; i <= this.totalPaginas; i++) arr.push(i);
                                            return arr;
                                        },
                                        carouselOffset() {
                                            // Calculate offset to show current page centered with 3 visible
                                            // Button width 2rem + gap 0.375rem = 2.375rem per button
                                            let offset;
                                            if (this.totalPaginas <= 3) {
                                                offset = 0;
                                            } else if (this.paginaActual === 1) {
                                                offset = 0;
                                            } else if (this.paginaActual === this.totalPaginas) {
                                                offset = -((this.totalPaginas - 3) * 2.375);
                                            } else {
                                                // Center the current page
                                                offset = -((this.paginaActual - 2) * 2.375);
                                            }
                                            return offset + 'rem';
                                        }
                                    }" class="flex gap-1.5 items-center">
                                        <!-- Botón ir al inicio -->
                                        <button
                                            @click="$wire.actualizarProformas(1); paginaActual = 1"
                                            :disabled="paginaActual == 1"
                                            class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-400/40 disabled:opacity-40 disabled:cursor-not-allowed"
                                            aria-label="Ir a la primera página"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                        </button>
                                        <!-- Carousel de números -->
                                        <div class="relative w-[7.5rem] h-10 overflow-hidden flex items-center justify-start">
                                            <div class="flex gap-1.5 transition-transform duration-500 ease-in-out" :style="'transform: translateX(' + carouselOffset() + ');'">
                                                <template x-for="(i, idx) in todasPaginas()" :key="i">
                                                    <button
                                                        @click="$wire.actualizarProformas(i); paginaActual = i"
                                                        :class="
                                                            paginaActual == i
                                                                ? 'w-8 h-8 rounded-lg border-2 flex items-center justify-center text-xs font-bold bg-cyan-600 text-white border-cyan-400 shadow-lg scale-105 z-10'
                                                                : 'w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 hover:bg-white/10 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-400/40'
                                                        "
                                                        :aria-label="'Ir a página ' + i"
                                                        :aria-current="paginaActual == i ? 'page' : null"
                                                        style="transition: all 0.3s ease;"
                                                    >
                                                        <span x-text="i"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <!-- Botón ir al final -->
                                        <button
                                            @click="$wire.actualizarProformas(totalPaginas); paginaActual = totalPaginas"
                                            :disabled="paginaActual == totalPaginas"
                                            class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-400/40 disabled:opacity-40 disabled:cursor-not-allowed"
                                            aria-label="Ir a la última página"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-1 flex justify-end">
                                    <div class="relative group">
                                        <button wire:click="siguientePaginaProformas" @if($paginaProformas >= ceil($totalProformas / $proformasPorPagina)) disabled aria-disabled="true" @endif
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/20 bg-white/5 text-white/80 font-medium hover:bg-white/10 hover:text-white focus:outline-none focus:ring-1 focus:ring-cyan-400/30 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed text-xs backdrop-blur-sm cursor-pointer"
                                            aria-label="Página siguiente" tabindex="0">
                                            <span class="hidden sm:inline"></span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                            Página siguiente
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                        <!-- Modal de confirmación de eliminación al nivel del contenedor principal -->
                        <div x-show="confirmDelete" x-transition.opacity.duration.300ms class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button @click="confirmDelete = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-red-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2 text-red-700">¿Confirmar eliminación?</h3>
                                    <p class="text-sm text-gray-700 mb-4 text-center">¿Estás seguro de que deseas eliminar {{ count($selectedProformas) }} {{ count($selectedProformas) == 1 ? 'proforma' : 'proformas' }} seleccionadas? Esta acción no se puede deshacer.</p>
                                    <!-- Banner de éxito/error -->
                                    <template x-if="deleteMessage">
                                        <div :class="deleteMessage.success ? 'bg-green-600/90 border-green-400/40' : 'bg-red-600/90 border-red-400/40'" class="text-white font-semibold rounded-lg shadow-lg px-6 py-4 flex items-center gap-3 border animate-fade-in mb-3 w-full" x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition.opacity.duration.500ms">
                                            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <template x-if="deleteMessage.success">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </template>
                                                <template x-if="!deleteMessage.success">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                                                </template>
                                            </svg>
                                            <span x-text="deleteMessage.message"></span>
                                        </div>
                                    </template>
                                    <div class="flex gap-3 mt-2">
                                        <button @click="confirmDelete = false"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button @click="$wire.deleteSelectedProformas().then(() => { deleteMessage = { success: true, message: 'Proforma(s) eliminada(s) exitosamente.' }; setTimeout(() => deleteMessage = null, 2000); confirmDelete = false; }).catch(() => { deleteMessage = { success: false, message: 'Hubo un error al eliminar.' }; setTimeout(() => deleteMessage = null, 2000); });"
                                                class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de confirmación de orden -->
                        @if($confirmOrderId !== null)
                        <div x-data="{}" x-init="setTimeout(() => window.scrollTo(0,0), 10)" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button wire:click="setConfirmOrderId(null)" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-amber-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2">¿Confirmar orden?</h3>
                                    @php
                                        $proformaConfirm = collect($proformas)->firstWhere('id', $confirmOrderId);
                                    @endphp
                                    <p class="text-sm text-gray-700 mb-4 text-center">
                                        @if($proformaConfirm && isset($proformaConfirm['number']))
                                            ¿Estás seguro de que deseas crear una orden con la proforma <span class="font-bold text-amber-700">#{{ $proformaConfirm['number'] }}</span>?
                                        @else
                                            ¿Estás seguro de que deseas crear una orden con esta proforma?
                                        @endif
                                    </p>
                                    <div class="flex gap-3 mt-2">
                                        <button wire:click="setConfirmOrderId(null)"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button wire:click="orderProforma({{ $confirmOrderId }})"
                                                class="px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700 transition">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal secundario para ver proforma individual -->
    @if($showProformaModal && $selectedProforma)
    <div class="fixed inset-0 z-[60] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <!-- Fondo oscuro más intenso -->
            <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeProformaModal"></div>
            
            <!-- Panel del modal -->
            <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative z-10 p-6 md:p-8 text-white" style="max-height: 90vh; display: flex; flex-direction: column;">
                <!-- Botón cerrar -->
                 <div class= "relative group">
                <button wire:click="closeProformaModal" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10 cursor-pointer">&times;</button>
                    <span class="absolute bottom-full right-0 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                        Cerrar
                    </span>
                </div>
                
                <!-- Contenido de la proforma -->
                <div class="overflow-y-auto max-h-[70vh]">
                    <h3 class="text-2xl font-bold mb-4">Proforma {{ $selectedProforma['number'] }}</h3>
                    
                    <div class="mb-6 p-4 bg-white/10 rounded-lg border border-white/20">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-white/70">Fecha de creación:</span>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($selectedProforma['created_at'])->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-white/70">Estado:</span>
                                <p class="font-semibold">
                                    @if($selectedProforma['is_expired'])
                                        <span class="text-red-400">Expirada</span>
                                    @elseif($selectedProforma['is_ordered'])
                                        <span class="text-blue-400">Ordenada</span>
                                    @else
                                        <span class="text-green-400">Activa</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-lg font-semibold mb-3">Configuraciones ({{ count($selectedProforma['items']) }} {{ count($selectedProforma['items']) == 1 ? 'ítem' : 'ítems' }})</h4>
                    
                    <div class="space-y-4">
                        @foreach($selectedProforma['items'] as $item)
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-bold text-lg">{{ $item['product_name'] }}</h5>
                                <span class="text-cyan-300 font-bold text-lg">${{ number_format($item['price'], 2) }}</span>
                            </div>
                            @if(!empty($item['parameters']))
                            <div class="grid grid-cols-2 gap-3 text-sm text-white/80 mt-3">
                                @if(isset($item['parameters']['width']))
                                <div>
                                    <span class="text-white/60">Dimensiones:</span>
                                    <p>{{ number_format($item['parameters']['width'], 2) }}m × {{ number_format($item['parameters']['height'], 2) }}m</p>
                                </div>
                                @endif
                                @if(isset($item['parameters']['color']))
                                <div>
                                    <span class="text-white/60">Color:</span>
                                    <p>{{ $item['parameters']['color'] }}</p>
                                </div>
                                @endif
                                <div>
                                    <span class="text-white/60">Cantidad:</span>
                                    <p class="font-semibold">{{ $item['quantity'] }} {{ $item['quantity'] == 1 ? 'unidad' : 'unidades' }}</p>
                                </div>
                                <div>
                                    <span class="text-white/60">Precio unitario:</span>
                                    <p class="font-semibold">${{ number_format($item['price'] / $item['quantity'], 2) }}</p>
                                </div>
                            </div>
                            @endif
                            @if(!$selectedProforma['is_expired'] && !$selectedProforma['is_ordered'])
                            <div class="mt-3 flex justify-end">
                                <div class= "relative group">
                                <button wire:click="deleteProformaItem({{ $item['id'] }})"
                                        class="px-3 py-1.5 text-xs font-medium border border-red-600/40 rounded-lg text-white bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 hover:scale-[1.02] hover:shadow-xl"
                                        >
                                    🗑 Eliminar configuración
                                </button>
                                <span class="absolute bottom-full left-1/1 -translate-x-1/1 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                    Eliminar esta configuración de la proforma
                                </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-600/30 to-cyan-600/30 rounded-lg border border-blue-400/30">
                        <div class="flex justify-between items-center">
                            <span class="text-white font-semibold text-lg">Total de la Proforma:</span>
                            <span class="text-3xl font-bold text-white">${{ number_format($selectedProforma['total_price'], 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="mt-6 pt-6 border-t border-white/20 flex justify-end gap-3">
                    <div class="relative group">
                        <button wire:click="downloadProformaPdf({{ $selectedProformaId }})" class="px-4 py-2 text-sm font-medium border border-green-600/40 rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 hover:scale-[1.02] hover:shadow-xl cursor-pointer">
                            Descargar PDF
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Descargar documento PDF con todos los detalles
                        </span>
                    </div>
                    @if(!$selectedProforma['is_expired'] && !$selectedProforma['is_ordered'])
                    <div x-data="{ showConfirmOrder: false }" class="relative group">
                        <button @click="showConfirmOrder = true"
                                class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl border border-amber-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 font-semibold">
                            Ordenar Proforma
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Convertir esta proforma en orden de compra
                        </span>
                        <!-- Modal de confirmación para ordenar proforma -->
                        <div x-show="showConfirmOrder" x-transition.opacity.duration.300ms class="fixed inset-0 z-[200] flex items-center justify-center bg-black/40 backdrop-blur-sm">
                            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full text-gray-900 relative">
                                <button @click="showConfirmOrder = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
                                <div class="flex flex-col items-center">
                                    <svg class="w-10 h-10 text-amber-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                    <h3 class="text-lg font-bold mb-2">¿Confirmar orden?</h3>
                                    <p class="text-sm text-gray-700 mb-4 text-center">
                                        @if(!empty($selectedProforma) && isset($selectedProforma['number']))
                                            ¿Estás seguro de que deseas crear una orden con la proforma <span class="font-bold text-amber-700">#{{ $selectedProforma['number'] }}</span>?
                                        @else
                                            ¿Estás seguro de que deseas crear una orden con esta proforma?
                                        @endif
                                    </p>
                                    <div class="flex gap-3 mt-2">
                                        <button @click="showConfirmOrder = false"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300 transition">Cancelar</button>
                                        <button @click="$wire.orderProforma({{ $selectedProformaId }}); showConfirmOrder = false;"
                                                class="px-4 py-2 rounded-lg bg-amber-600 text-white font-semibold hover:bg-amber-700 transition">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
