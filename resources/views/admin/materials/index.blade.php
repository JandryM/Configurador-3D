@extends('layouts.admin')

@section('title', 'Gestión de Materiales')
@section('page-title', 'Materiales')

@section('content')
<!-- Encabezado de la sección -->
<div class="fade-in mb-8">
    <div class="glass-card rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Gestión de Materiales</h1>
                    <p class="text-slate-600">Administra el inventario y precios de materiales</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Nuevo Material</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de materiales -->
@php
    $totalMaterials = \App\Models\Material::count();
    $byPieceMaterials = \App\Models\Material::where('is_by_piece', true)->count();
    $byDimensionMaterials = \App\Models\Material::where('has_dimensions', true)->count();
    $totalValue = \App\Models\Material::sum('unit_price');
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total de Materiales -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalMaterials }}</p>
                <p class="text-sm text-slate-600">Total Materiales</p>
            </div>
        </div>
    </div>

    <!-- Por Piezas -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $byPieceMaterials }}</p>
                <p class="text-sm text-slate-600">Por Piezas</p>
            </div>
        </div>
    </div>

    <!-- Por Dimensiones -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $byDimensionMaterials }}</p>
                <p class="text-sm text-slate-600">Por Dimensiones</p>
            </div>
        </div>
    </div>

    <!-- Valor Total -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">${{ number_format($totalValue, 0) }}</p>
                <p class="text-sm text-slate-600">Valor Total</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de materiales -->
<div class="glass-card rounded-2xl shadow-xl">
    <div class="p-6 border-b border-slate-200/50">
        <h2 class="text-xl font-bold text-slate-800">Lista de Materiales</h2>
        <p class="text-slate-600 mt-1">Todos los materiales registrados en el inventario</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Material</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Categoría</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Precio</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Tipo</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Medida</th>
                        <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Material::latest()->get() as $material)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                                        <span class="text-sm font-bold text-white">
                                            {{ strtoupper(substr($material->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $material->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $material->description ?? 'Sin descripción' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">
                                    {{ $material->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <span class="font-medium text-slate-800">${{ number_format($material->unit_price, 2) }}</span>
                                    <p class="text-sm text-slate-500">por {{ $material->unit_measure }}</p>
                                    @if($material->is_by_piece)
                                        <p class="text-xs text-green-600">Pieza: ${{ number_format($material->piece_price, 2) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($material->is_by_piece)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-md">
                                        Por Piezas
                                    </span>
                                @elseif($material->has_dimensions)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-md">
                                        Por Dimensiones
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-md">
                                        Por Unidad
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($material->is_by_piece)
                                    <span class="text-slate-700">{{ $material->piece_size }} {{ $material->unit_measure }}</span>
                                @elseif($material->has_dimensions)
                                    <span class="text-slate-700">{{ $material->width }}m x {{ $material->height }}m</span>
                                @else
                                    <span class="text-slate-700">{{ $material->unit_measure }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex justify-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">
                                No hay materiales registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection