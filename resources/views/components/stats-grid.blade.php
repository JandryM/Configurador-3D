@props([
    'columns' => 5,
])

<div class="grid grid-cols-1 md:grid-cols-2 @if($columns == 3) lg:grid-cols-3 @elseif($columns == 4) lg:grid-cols-4 @elseif($columns == 5) lg:grid-cols-5 @elseif($columns == 6) lg:grid-cols-6 @else lg:grid-cols-4 @endif gap-4 mb-6">
    {{ $slot }}
</div>
