@props([
    'type' => 'success',
    'message' => null,
])

@php
    $gradients = [
        'success' => 'from-green-500 to-emerald-600',
        'error' => 'from-red-500 to-rose-600',
        'warning' => 'from-yellow-500 to-orange-600',
        'info' => 'from-blue-500 to-indigo-600',
    ];
    
    $icons = [
        'success' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'error' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        'warning' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
        'info' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    ];
    
    $gradient = $gradients[$type] ?? $gradients['success'];
    $icon = $icons[$type] ?? $icons['success'];
@endphp

@if (session()->has('message') || $message)
<div class="mb-6 animate-in fade-in slide-in-from-top-2 duration-500"
    x-data="{ show: true }" 
    x-show="show" 
    x-init="setTimeout(() => show = false, 3000)"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="!bg-gradient-to-r {{ $gradient }} rounded-2xl shadow-xl p-5 text-white flex items-center gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
            {!! $icon !!}
        </div>
        <div class="flex-1">
            <p class="font-medium text-lg">{{ $message ?? session('message') }}</p>
        </div>
        <button @click="show = false" class="flex-shrink-0 hover:bg-white/10 rounded-lg p-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
@endif
