@props([
    'title',
    'description' => null,
    'icon' => null,
    'showButton' => false,
    'buttonText' => 'Agregar',
    'buttonAction' => null,
    'buttonLink' => null,
    'buttonColor' => 'orange',
])

@php
    $colorClasses = [
        'sky' => 'bg-white hover:bg-sky-50 text-sky-600 hover:text-sky-700',
        'amber' => 'bg-white hover:bg-amber-50 text-amber-600 hover:text-amber-700',
        'red' => 'bg-white hover:bg-red-50 text-red-600 hover:text-red-700',
        'orange' => 'bg-white hover:bg-orange-50 text-orange-600 hover:text-orange-700',
    ];
    $buttonClass = $colorClasses[$buttonColor] ?? $colorClasses['orange'];
@endphp

<div class="mb-8 animate-in fade-in slide-in-from-top-2 duration-500">
    <div class="bg-custom-blue/95 rounded-xl shadow-lg p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">{{ $title }}</h1>
            @if($description)
                <p class="text-white/90 mt-1 text-sm">{{ $description }}</p>
            @endif
        </div>

        @if($showButton)
            <div>
                @if($buttonLink)
                    <a href="{{ $buttonLink }}"
                        class="inline-flex items-center gap-2 {{ $buttonClass }} px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        {{ $buttonIcon ?? '' }}
                        <span>{{ $buttonText }}</span>
                    </a>
                @else
                    <button
                        {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 ' . $buttonClass . ' px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm cursor-pointer']) }}
                    >
                        {{ $buttonIcon ?? '' }}
                        <span>{{ $buttonText }}</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

