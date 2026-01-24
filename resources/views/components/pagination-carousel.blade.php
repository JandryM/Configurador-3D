@props([
    'currentPage',
    'totalPages',
    'updateMethod',
    'color' => 'cyan'
])

<div x-data="{
    paginaActual: {{ $currentPage }},
    totalPaginas: {{ $totalPages }},
    metodo: '{{ $updateMethod }}',
    todasPaginas() {
        let arr = [];
        for (let i = 1; i <= this.totalPaginas; i++) arr.push(i);
        return arr;
    },
    carouselOffset() {
        let offset;
        if (this.totalPaginas <= 3) {
            offset = 0;
        } else if (this.paginaActual === 1) {
            offset = 0;
        } else if (this.paginaActual === this.totalPaginas) {
            offset = -((this.totalPaginas - 3) * 2.375);
        } else {
            offset = -((this.paginaActual - 2) * 2.375);
        }
        return offset + 'rem';
    },
    cambiarPagina(pagina) {
        this.paginaActual = pagina;
        $wire[this.metodo](pagina);
    }
}" 
class="flex gap-1.5 items-center">
    <button
        @click="cambiarPagina(1)"
        :disabled="paginaActual == 1"
        class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-{{ $color }}-400/40 disabled:opacity-40 disabled:cursor-not-allowed"
        aria-label="Ir a la primera página">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </button>
    <div class="relative w-[7.5rem] h-10 overflow-hidden flex items-center justify-start">
        <div class="flex gap-1.5 transition-transform duration-500 ease-in-out" :style="'transform: translateX(' + carouselOffset() + ');'">
            <template x-for="(i, idx) in todasPaginas()" :key="i">
                <button
                    @click="cambiarPagina(i)"
                    :class="
                        paginaActual == i
                            ? 'w-8 h-8 rounded-lg border-2 flex items-center justify-center text-xs font-bold bg-{{ $color }}-600 text-white border-{{ $color }}-400 shadow-lg scale-105 z-10'
                            : 'w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 hover:bg-white/10 hover:text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-{{ $color }}-400/40'
                    "
                    :aria-label="'Ir a página ' + i"
                    :aria-current="paginaActual == i ? 'page' : null"
                    style="transition: all 0.3s ease;">
                    <span x-text="i"></span>
                </button>
            </template>
        </div>
    </div>
    <button
        @click="cambiarPagina(totalPaginas)"
        :disabled="paginaActual == totalPaginas"
        class="w-8 h-8 rounded-lg border flex items-center justify-center text-xs font-semibold bg-white/5 text-white/70 border-white/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-{{ $color }}-400/40 disabled:opacity-40 disabled:cursor-not-allowed"
        aria-label="Ir a la última página">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
    </button>
</div>
