@props([
    'title',
    'value',
    'icon',
    'iconColor' => 'text-slate-600', // New prop for brand colors
    'gradient' => null, // Kept for backward compat but unused in new design
    'hoverColor' => null, // Kept for backward compat but unused
])

<div class="group bg-white rounded-xl border border-slate-200 p-5 hover:border-slate-300 transition-all duration-200 hover:shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-1 truncate">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $value }}</h3>
            @isset($footer)
                <div class="mt-2">
                    {{ $footer }}
                </div>
            @endisset
        </div>
        <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center {{ $iconColor }} group-hover:scale-110 transition-transform duration-200">
            {{ $icon }}
        </div>
    </div>
</div>

