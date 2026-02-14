@props([
    'title',
    'description' => null,
    'icon' => null,
    'showButton' => false,
    'buttonText' => 'Agregar',
    'buttonAction' => null,
    'buttonLink' => null,
])

<div class="mb-8 animate-in fade-in slide-in-from-top-2 duration-500">
    <div class="bg-gradient-to-r from-orange-500 via-amber-500 to-teal-500 rounded-xl shadow-lg p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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
                        class="inline-flex items-center gap-2 bg-white hover:bg-orange-50 text-orange-600 px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        {{ $buttonIcon ?? '' }}
                        <span>{{ $buttonText }}</span>
                    </a>
                @else
                    <button
                        {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 bg-white hover:bg-orange-50 text-orange-600 px-5 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm cursor-pointer']) }}
                    >
                        {{ $buttonIcon ?? '' }}
                        <span>{{ $buttonText }}</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

