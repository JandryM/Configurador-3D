@props([
    'hasPagination' => false,
    'page' => null,
    'perPage' => null,
    'total' => null,
    'itemName' => 'items',
])

<style>
    /* Scrollbar horizontal solo visible cuando es necesaria */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
        width: 0px; /* Ocultar scrollbar vertical */
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: transparent;
        border-radius: 9999px;
        transition: background-color 0.3s ease;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background-color: transparent;
        border-radius: 9999px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: rgb(165 180 252);
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb:hover {
        background-color: rgb(129 140 248);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:active {
        background-color: rgb(129 140 248);
    }
</style>

<div {{ $attributes->merge(['class' => '!bg-blue-50 rounded-2xl !border !border-custom-blue/20 shadow-lg']) }}>
    <div class="p-6">
        <div 
            class="overflow-x-auto custom-scrollbar"
            x-data="{}"
            @wheel="
                const delta = $event.deltaY;
                const maxScrollLeft = $el.scrollWidth - $el.clientWidth;
                const currentScrollLeft = $el.scrollLeft;
                
                // Si está en el límite derecho y sigue scrolleando a la derecha
                if (delta > 0 && currentScrollLeft >= maxScrollLeft) {
                    return; // Permitir scroll normal (vertical)
                }
                
                // Si está en el límite izquierdo y sigue scrolleando a la izquierda
                if (delta < 0 && currentScrollLeft <= 0) {
                    return; // Permitir scroll normal (vertical)
                }
                
                // Si hay espacio para scroll horizontal, hacer scroll horizontal
                if (maxScrollLeft > 0) {
                    $event.preventDefault();
                    $el.scrollLeft += delta;
                }
            "
        >
            <table class="w-full min-w-max">
                {{ $slot }}
            </table>
        </div>
        
        @if($hasPagination && $total > 0)
            <div class="mt-6">
                <x-pagination 
                    :page="$page" 
                    :perPage="$perPage" 
                    :total="$total"
                    :itemName="$itemName"
                />
            </div>
        @endif
    </div>
</div>
