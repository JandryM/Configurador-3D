@props([
    'title',
    'value',
    'icon',
    'gradient' => 'from-blue-500 to-blue-600',
    'hoverColor' => 'blue-300',
])

<div class="group !bg-white rounded-2xl shadow-md !border !border-slate-100 p-5 hover:shadow-xl hover:scale-105 transition-all duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-slate-600 mb-2">{{ $title }}</p>
            <p class="text-3xl font-bold text-slate-900">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 !bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center shadow-lg shadow-{{ $hoverColor }}/30 group-hover:scale-110 transition-transform duration-300">
            {{ $icon }}
        </div>
    </div>
</div>
