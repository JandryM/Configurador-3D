@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios')

@section('content')
<!-- Encabezado de la sección -->
<div class="fade-in mb-8">
    <div class="glass-card rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios</h1>
                    <p class="text-slate-600">Administra cuentas de usuarios y permisos del sistema</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas de usuarios -->
@php
    $totalUsers = \App\Models\User::count();
    $adminUsers = \App\Models\User::where('role', 'admin')->count();
    $suspendedUsers = \App\Models\User::where('is_suspended', true)->count();
    $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
    $recentLogins = \App\Models\User::where('last_login_at', '>=', now()->subDays(7))->count();
@endphp

<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <!-- Total de Usuarios -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</p>
                <p class="text-sm text-slate-600">Total Usuarios</p>
            </div>
        </div>
    </div>

    <!-- Administradores -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $adminUsers }}</p>
                <p class="text-sm text-slate-600">Administradores</p>
            </div>
        </div>
    </div>

    <!-- Usuarios Suspendidos -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $suspendedUsers }}</p>
                <p class="text-sm text-slate-600">Suspendidos</p>
            </div>
        </div>
    </div>

    <!-- Usuarios Verificados -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $verifiedUsers }}</p>
                <p class="text-sm text-slate-600">Verificados</p>
            </div>
        </div>
    </div>

    <!-- Logins Recientes -->
    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg mr-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800">{{ $recentLogins }}</p>
                <p class="text-sm text-slate-600">Últimos 7 días</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="glass-card rounded-2xl shadow-xl">
    <div class="p-6 border-b border-slate-200/50">
        <h2 class="text-xl font-bold text-slate-800">Lista de Usuarios</h2>
        <p class="text-slate-600 mt-1">Todos los usuarios registrados en el sistema</p>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Usuario</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Contacto</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Rol</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Estado</th>
                        <th class="text-left py-3 px-4 font-medium text-slate-700">Último Login</th>
                        <th class="text-center py-3 px-4 font-medium text-slate-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\User::latest()->get() as $user)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors" data-user-id="{{ $user->id }}">
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r {{ $user->isAdmin() ? 'from-amber-500 to-orange-600' : 'from-slate-600 to-slate-700' }} rounded-lg flex items-center justify-center shadow-md">
                                        <span class="text-sm font-bold text-white">
                                            {{ $user->initials() }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-500">
                                            @if($user->oauth_provider)
                                                <span class="inline-flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                    </svg>
                                                    {{ ucfirst($user->oauth_provider) }}
                                                </span>
                                            @else
                                                Registro normal
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div>
                                    <p class="text-slate-800">{{ $user->email }}</p>
                                    @if($user->phone)
                                        <p class="text-sm text-slate-500">{{ $user->phone }}</p>
                                    @endif
                                    @if($user->province)
                                        <p class="text-xs text-slate-400">{{ $user->city }}, {{ $user->province }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Cliente
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 user-status">
                                @php 
                                    $statusColor = $user->getAccountStatusColor();
                                    $status = $user->getAccountStatus();
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-md">
                                    @if($user->isSuspended())
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($user->email_verified_at)
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                    {{ $status }}
                                </span>
                                @if($user->isSuspended() && $user->suspended_until)
                                    <p class="text-xs text-slate-500 mt-1">
                                        Hasta: {{ $user->suspended_until->format('d/m/Y') }}
                                    </p>
                                @endif
                                @if($user->suspension_reason)
                                    <p class="text-xs text-slate-400 mt-1" title="{{ $user->suspension_reason }}">
                                        {{ Str::limit($user->suspension_reason, 20) }}
                                    </p>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($user->last_login_at)
                                    <span class="text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                    <p class="text-xs text-slate-500">{{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                                @else
                                    <span class="text-slate-400">Nunca</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 user-actions">
                                <div class="flex justify-center space-x-2">
                                    @if(!$user->isAdmin() || (\App\Models\User::where('role', 'admin')->count() > 1))
                                        <!-- Suspender/Reactivar -->
                                        @if($user->isSuspended())
                                            <button class="text-emerald-600 hover:text-emerald-800 transition-colors user-action-btn" 
                                                    title="Reactivar cuenta de usuario"
                                                    data-action="unsuspend" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <button class="text-orange-600 hover:text-orange-800 transition-colors user-action-btn" 
                                                    title="Suspender cuenta de usuario"
                                                    data-action="suspend" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                    
                                    @if(!$user->email_verified_at)
                                        <!-- Verificar email -->
                                        <button class="text-purple-600 hover:text-purple-800 transition-colors user-action-btn" 
                                                title="Verificar email manualmente"
                                                data-action="verify-email" 
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                    
                                    @if(!$user->isAdmin() || (\App\Models\User::where('role', 'admin')->count() > 1))
                                        <!-- Eliminar (solo si no es el único admin) -->
                                        <button class="text-red-600 hover:text-red-800 transition-colors user-action-btn" 
                                                title="Eliminar usuario permanentemente"
                                                data-action="delete" 
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">
                                No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para suspender usuario -->
<div id="suspendModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Suspender Usuario</h3>
                    <p class="text-slate-600">Suspender cuenta de <span id="suspendUserName" class="font-medium"></span></p>
                </div>
            </div>
            
            <form id="suspendForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Duración de la suspensión</label>
                    <select name="duration" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="">Seleccionar duración...</option>
                        <option value="1">1 día</option>
                        <option value="7">1 semana</option>
                        <option value="30">1 mes</option>
                        <option value="90">3 meses</option>
                        <option value="365">1 año</option>
                        <option value="permanent">Permanente</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Razón de la suspensión</label>
                    <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Describe la razón de la suspensión..." required maxlength="500"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Máximo 500 caracteres</p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" id="cancelSuspend" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                        Suspender Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación para reactivar -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div id="confirmIcon" class="w-12 h-12 rounded-xl flex items-center justify-center mr-4">
                    <!-- Icono dinámico -->
                </div>
                <div>
                    <h3 id="confirmTitle" class="text-lg font-bold text-slate-800"></h3>
                    <p id="confirmMessage" class="text-slate-600"></p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" id="cancelConfirm" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="confirmAction" class="flex-1 px-4 py-2 rounded-lg transition-colors">
                    <!-- Texto dinámico -->
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const suspendModal = document.getElementById('suspendModal');
    const confirmModal = document.getElementById('confirmModal');
    const suspendForm = document.getElementById('suspendForm');
    
    // Variables globales
    let currentUserId = null;
    let currentAction = null;
    
    // Event listeners para botones de acción
    document.querySelectorAll('.user-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentUserId = this.dataset.userId;
            currentAction = this.dataset.action;
            const userName = this.dataset.userName;
            
            if (currentAction === 'suspend') {
                showSuspendModal(userName);
            } else {
                showConfirmModal(currentAction, userName);
            }
        });
    });
    
    // Funciones para mostrar modales
    function showSuspendModal(userName) {
        document.getElementById('suspendUserName').textContent = userName;
        suspendModal.classList.remove('hidden');
        suspendModal.classList.add('flex');
    }
    
    function showConfirmModal(action, userName) {
        const confirmIcon = document.getElementById('confirmIcon');
        const confirmTitle = document.getElementById('confirmTitle');
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmActionBtn = document.getElementById('confirmAction');
        
        switch(action) {
            case 'unsuspend':
                confirmIcon.className = 'w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4';
                confirmIcon.innerHTML = `<svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                </svg>`;
                confirmTitle.textContent = 'Reactivar Usuario';
                confirmMessage.textContent = `¿Reactivar la cuenta de ${userName}?`;
                confirmActionBtn.textContent = 'Reactivar';
                confirmActionBtn.className = 'flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-colors';
                break;
                
            case 'verify-email':
                confirmIcon.className = 'w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4';
                confirmIcon.innerHTML = `<svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>`;
                confirmTitle.textContent = 'Verificar Email';
                confirmMessage.textContent = `¿Verificar manualmente el email de ${userName}?`;
                confirmActionBtn.textContent = 'Verificar';
                confirmActionBtn.className = 'flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors';
                break;
                
            case 'delete':
                confirmIcon.className = 'w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4';
                confirmIcon.innerHTML = `<svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>`;
                confirmTitle.textContent = 'Eliminar Usuario';
                confirmMessage.textContent = `¿Eliminar permanentemente a ${userName}? Esta acción no se puede deshacer.`;
                confirmActionBtn.textContent = 'Eliminar';
                confirmActionBtn.className = 'flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors';
                break;
        }
        
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    }
    
    // Event listeners para cerrar modales
    document.getElementById('cancelSuspend').addEventListener('click', () => {
        suspendModal.classList.add('hidden');
        suspendModal.classList.remove('flex');
        suspendForm.reset();
    });
    
    document.getElementById('cancelConfirm').addEventListener('click', () => {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    });
    
    // Submit del formulario de suspensión
    suspendForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Suspendiendo...';
        
        fetch(`/admin/users/${currentUserId}/suspend`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                updateUserRow(currentUserId, data.user);
                suspendModal.classList.add('hidden');
                suspendModal.classList.remove('flex');
                suspendForm.reset();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error de conexión. Intenta de nuevo.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    // Acción de confirmación
    document.getElementById('confirmAction').addEventListener('click', function() {
        const submitBtn = this;
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Procesando...';
        
        let url = '';
        let method = 'POST';
        
        switch(currentAction) {
            case 'unsuspend':
                url = `/admin/users/${currentUserId}/unsuspend`;
                break;
            case 'verify-email':
                url = `/admin/users/${currentUserId}/verify-email`;
                break;
            case 'delete':
                url = `/admin/users/${currentUserId}`;
                method = 'DELETE';
                break;
        }
        
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                
                if (currentAction === 'delete') {
                    removeUserRow(currentUserId);
                } else {
                    updateUserRow(currentUserId, data.user);
                }
                
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error de conexión. Intenta de nuevo.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    // Función para actualizar fila de usuario
    function updateUserRow(userId, userData) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            // Actualizar estado
            const statusCell = row.querySelector('.user-status');
            if (statusCell) {
                statusCell.innerHTML = `
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-${userData.status_color}-100 text-${userData.status_color}-800 rounded-md">
                        ${userData.status}
                    </span>
                `;
                
                if (userData.suspended_until) {
                    statusCell.innerHTML += `
                        <p class="text-xs text-slate-500 mt-1">Hasta: ${userData.suspended_until}</p>
                    `;
                }
            }
            
            // Actualizar botones de acción
            const actionsCell = row.querySelector('.user-actions');
            if (actionsCell) {
                location.reload(); // Recargar para actualizar botones correctamente
            }
        }
    }
    
    // Función para eliminar fila de usuario
    function removeUserRow(userId) {
        const row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            row.remove();
        }
    }
    
    // Función para mostrar notificaciones
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all transform translate-x-full`;
        
        if (type === 'success') {
            notification.className += ' bg-green-600 text-white';
        } else {
            notification.className += ' bg-red-600 text-white';
        }
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        // Animación de entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Eliminar después de 5 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
});
</script>
@endsection