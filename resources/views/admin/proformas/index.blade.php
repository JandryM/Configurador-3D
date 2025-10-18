@extends('layouts.admin')

@section('title', 'Gestión de Proformas')
@section('page-title', 'Proformas')

@section('content')
<!-- Encabezado de la sección -->
<div class="fade-in mb-8">
    <div class="glass-card rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Gestión de Proformas</h1>
                    <p class="text-slate-600">Administra cotizaciones y propuestas comerciales</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Nueva Proforma</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de proformas -->
@php
    // Simulamos datos de proformas para demostración
    $totalProformas = 15;
    $pendingProformas = 5;
    $approvedProformas = 8;
    $rejectedProformas = 2;
    $totalValue = 45000;
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total de Proformas -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalProformas }}</p>
                <p class="text-sm text-slate-600">Total Proformas</p>
            </div>
        </div>
    </div>

    <!-- Proformas Pendientes -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $pendingProformas }}</p>
                <p class="text-sm text-slate-600">Pendientes</p>
            </div>
        </div>
    </div>

    <!-- Proformas Aprobadas -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $approvedProformas }}</p>
                <p class="text-sm text-slate-600">Aprobadas</p>
            </div>
        </div>
    </div>

    <!-- Valor Total -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
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

<!-- Tabla de proformas -->
<div class="glass-card rounded-2xl shadow-xl">
    <div class="p-6 border-b border-slate-200/50">
        <h2 class="text-xl font-bold text-slate-800">Lista de Proformas</h2>
        <p class="text-slate-600 mt-1">Todas las cotizaciones y propuestas comerciales</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Número</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Cliente</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Fecha</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Monto</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Estado</th>
                        <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Datos de ejemplo para proformas
                        $proformas = [
                            [
                                'number' => 'PRF-001',
                                'client' => 'Juan Pérez',
                                'date' => '2025-01-15',
                                'amount' => 2500,
                                'status' => 'pending'
                            ],
                            [
                                'number' => 'PRF-002',
                                'client' => 'María García',
                                'date' => '2025-01-14',
                                'amount' => 5000,
                                'status' => 'approved'
                            ],
                            [
                                'number' => 'PRF-003',
                                'client' => 'Carlos López',
                                'date' => '2025-01-13',
                                'amount' => 1800,
                                'status' => 'rejected'
                            ],
                            [
                                'number' => 'PRF-004',
                                'client' => 'Ana Martínez',
                                'date' => '2025-01-12',
                                'amount' => 3200,
                                'status' => 'pending'
                            ],
                            [
                                'number' => 'PRF-005',
                                'client' => 'Roberto Silva',
                                'date' => '2025-01-10',
                                'amount' => 4500,
                                'status' => 'approved'
                            ]
                        ];
                    @endphp
                    
                    @forelse($proformas as $proforma)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-violet-600 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $proforma['number'] }}</p>
                                        <p class="text-sm text-slate-500">Proforma</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <p class="font-medium text-slate-800">{{ $proforma['client'] }}</p>
                                    <p class="text-sm text-slate-500">Cliente</p>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-slate-700">
                                    {{ date('d/m/Y', strtotime($proforma['date'])) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-medium text-slate-800">
                                    ${{ number_format($proforma['amount'], 0) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($proforma['status'] === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-md">
                                        Pendiente
                                    </span>
                                @elseif($proforma['status'] === 'approved')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-md">
                                        Aprobada
                                    </span>
                                @elseif($proforma['status'] === 'rejected')
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-md">
                                        Rechazada
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex justify-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 transition-colors" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                        </svg>
                                    </button>
                                    <button class="text-purple-600 hover:text-purple-800 transition-colors" title="Descargar PDF">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar">
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
                                No hay proformas registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection