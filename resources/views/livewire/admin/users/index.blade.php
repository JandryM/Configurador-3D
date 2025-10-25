<div>
    {{-- Mensaje flash --}}
    @if (session()->has('message'))
        <div class="fade-in mb-4">
            <div class="glass-card rounded-2xl shadow-xl p-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                {{ session('message') }}
            </div>
        </div>
    @endif

    {{-- Encabezado de la sección --}}
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
                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Agregar Usuario
                        </a>
                    </div>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
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


    {{-- Tabla de usuarios --}}
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
                        @forelse ($users as $user)
                            <tr class="border-b border-slate-200/50 hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center shadow">
                                            <span class="text-white font-medium text-sm">
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
                                    @elseif($user->isOwner())
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Propietario
                                        </span>
                                    @elseif($user->isSeller())
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Vendedor
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
                                                            wire:click="openUserModal({{ $user->id }}, 'unsuspend')">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                    <button class="text-orange-600 hover:text-orange-800 transition-colors user-action-btn" 
                                                            title="Suspender cuenta de usuario"
                                                            wire:click="openUserModal({{ $user->id }}, 'suspend')">
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
                                                        wire:click="openUserModal({{ $user->id }}, 'verify-email')">
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
                                                        wire:click="openUserModal({{ $user->id }}, 'delete')">
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
            
            {{-- Paginación --}}
            @if($users->hasPages())
                <div class="px-6 py-3 border-t border-slate-200/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de acción de usuario --}}
    @if ($showUserModal && $selectedUser)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">
                                @if ($actionType === 'suspend')
                                    Suspender Usuario
                                @elseif ($actionType === 'unsuspend')
                                    Reactivar Usuario
                                @elseif ($actionType === 'verify-email')
                                    Verificar Email
                                @elseif ($actionType === 'delete')
                                    Eliminar Usuario
                                @endif
                            </h3>
                            <p class="text-slate-600">Usuario: {{ $selectedUser->name }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <p><strong>Email:</strong> {{ $selectedUser->email }}</p>
                    </div>
                    @if ($actionType === 'suspend')
                        <form wire:submit.prevent="suspend">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Duración de la suspensión</label>
                                <select class="w-full px-3 py-2 border border-slate-300 rounded-lg" wire:model="suspensionDays" required>
                                    <option value="1">1 día</option>
                                    <option value="7">7 días</option>
                                    <option value="30">30 días</option>
                                    <option value="90">90 días</option>
                                    <option value="365">1 año</option>
                                    <option value="permanent">Permanente</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Razón de la suspensión</label>
                                <textarea class="w-full px-3 py-2 border border-slate-300 rounded-lg" wire:model="actionReason" required rows="3"></textarea>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" wire:click="closeUserModal">Cancelar</button>
                                <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-lg">Suspender</button>
                            </div>
                        </form>
                    @elseif ($actionType === 'unsuspend')
                        <div class="flex space-x-3">
                            <button type="button" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 bg-emerald-600 text-white px-4 py-2 rounded-lg" wire:click="unsuspend">Reactivar</button>
                        </div>
                    @elseif ($actionType === 'verify-email')
                        <div class="flex space-x-3">
                            <button type="button" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg" wire:click="verifyEmail">Verificar</button>
                        </div>
                    @elseif ($actionType === 'delete')
                        <div class="flex space-x-3">
                            <button type="button" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg" wire:click="delete">Eliminar</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de detalles de usuario --}}
    @if ($showUserDetailsModal && $userDetails)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Detalles de usuario</h3>
                            <p class="text-slate-600">Información completa del usuario</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <p><strong>Nombre:</strong> {{ $userDetails->name }}</p>
                        <p><strong>Email:</strong> {{ $userDetails->email }}</p>
                        <p><strong>Rol:</strong> {{ $userDetails->role }}</p>
                        <p><strong>Estado:</strong> {{ $userDetails->is_suspended ? 'Suspendido' : 'Activo' }}</p>
                        <p><strong>Verificado:</strong> {{ $userDetails->email_verified_at ? 'Sí' : 'No' }}</p>
                        <p><strong>Último login:</strong> {{ $userDetails->last_login_at ? $userDetails->last_login_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <button type="button" class="w-full px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors" wire:click="closeUserDetailsModal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush