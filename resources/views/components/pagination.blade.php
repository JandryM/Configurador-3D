@props([
    'page' => 1,
    'perPage' => 10,
    'total' => 0,
    'perPageOptions' => [10, 25, 50, 100],
    'itemName' => 'registros'
])

@php
    $totalPages = (int) ceil($total / $perPage);
    $from = ($page - 1) * $perPage + 1;
    $to = min($page * $perPage, $total);
@endphp

@if($total > 0)
    <div class="glass-card rounded-xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <!-- Info de registros -->
            <div class="flex items-center space-x-4">
                <span class="text-slate-600">
                    Mostrando
                    <span class="font-semibold text-slate-800">{{ $from }}</span>
                    a
                    <span class="font-semibold text-slate-800">{{ $to }}</span>
                    de
                    <span class="font-semibold text-slate-800">{{ $total }}</span>
                    {{ $itemName }}
                </span>
            </div>

            <!-- Selector de tamaño de página -->
            <div class="flex items-center space-x-2">
                <span class="text-sm text-slate-600">Mostrar:</span>
                @foreach($perPageOptions as $size)
                    <button 
                        wire:click="$set('perPage', {{ $size }})"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-all
                            @if($perPage == $size)
                                bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-md
                            @else
                                bg-slate-100 text-slate-600 hover:bg-slate-200
                            @endif
                        "
                    >
                        {{ $size }}
                    </button>
                @endforeach
            </div>

            <!-- Navegación con carrusel -->
            <div class="flex items-center space-x-2">
                <!-- Ir al inicio -->
                <button 
                    wire:click="firstPage"
                    @if($page <= 1) disabled @endif
                    class="p-2 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-600 text-white hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Ir al inicio"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Página anterior -->
                <button 
                    wire:click="previousPage"
                    @if($page <= 1) disabled @endif
                    class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Página anterior"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <!-- Indicador de página -->
                <div class="flex items-center space-x-2 px-4 py-2 bg-slate-100 rounded-lg">
                    <span class="text-sm text-slate-600">Página</span>
                    <span class="font-semibold text-slate-800">{{ $page }}</span>
                    <span class="text-sm text-slate-600">de</span>
                    <span class="font-semibold text-slate-800">{{ $totalPages }}</span>
                </div>

                <!-- Página siguiente -->
                <button 
                    wire:click="nextPage"
                    @if($page >= $totalPages) disabled @endif
                    class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Página siguiente"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <!-- Ir al final -->
                <button 
                    wire:click="lastPage"
                    @if($page >= $totalPages) disabled @endif
                    class="p-2 rounded-lg bg-gradient-to-r from-blue-500 to-cyan-600 text-white hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Ir al final"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endif
