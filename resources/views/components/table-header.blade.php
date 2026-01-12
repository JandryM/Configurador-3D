@props([
    'align' => 'left',
])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th {{ $attributes->merge(['class' => "$alignClass py-1 px-1 sm:py-1.5 sm:px-1.5 md:py-2 md:px-3 lg:py-2 lg:px-3 xl:py-2 xl:px-3 2xl:py-4 2xl:px-6 font-bold text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap"]) }}>
    {{ $slot }}
</th>
