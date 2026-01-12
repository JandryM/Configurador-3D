@props([
    'tooltip' => '',
    'color' => 'blue',
])

<div class="relative group">
    <button {{ $attributes->merge(['class' => "text-{$color}-600 hover:text-{$color}-800 transition-colors user-action-btn cursor-pointer p-0.5 sm:p-1 lg:p-0.5 xl:p-0.5 2xl:p-2"]) }}>
        {{ $slot }}
    </button>
    @if($tooltip)
        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-slate-800 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-lg">
            {{ $tooltip }}
        </span>
    @endif
</div>
