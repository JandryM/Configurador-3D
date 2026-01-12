@props([
    'title',
    'description' => null,
    'icon' => 'user',
    'gradient' => 'from-blue-600 to-indigo-700',
    'showButton' => false,
    'buttonText' => 'Agregar',
    'buttonAction' => null,
    'buttonLink' => null,
])

<div class="mb-6 animate-in fade-in slide-in-from-top-2 duration-500">
    <div class="!bg-gradient-to-r {{ $gradient }} rounded-2xl shadow-lg p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                        {{ $icon }}
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1">{{ $title }}</h1>
                    @if($description)
                        <p class="text-blue-100 text-sm">{{ $description }}</p>
                    @endif
                </div>
            </div>
            @if($showButton)
                <div class="flex justify-end sm:justify-start">
                    @if($buttonLink)
                        <a href="{{ $buttonLink }}"
                            class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 font-medium text-sm whitespace-nowrap">
                            {{ $buttonIcon ?? '' }}
                            <span>{{ $buttonText }}</span>
                        </a>
                    @else
                        <button
                            {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 font-medium text-sm whitespace-nowrap']) }}
                        >
                            {{ $buttonIcon ?? '' }}
                            <span>{{ $buttonText }}</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
