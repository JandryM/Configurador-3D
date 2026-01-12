<div>
    {{-- Encabezado de la sección --}}
    <x-page-header 
        title="Gestión de Usuarios"
        description="Administra cuentas de usuarios y permisos del sistema"
        gradient="from-blue-400 to-blue-500"
        :show-button="true"
        button-text="Agregar Usuario"
        x-data
        x-on:click="$wire.openCreateModal()"
    >
        <x-slot name="icon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4.5 xl:h-4.5 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-slot name="buttonIcon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-3.5 lg:h-3.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
    </x-page-header>

    {{-- Mensaje flash --}}
    <x-flash-message type="success" />

    {{-- Estadísticas --}}
    <x-stats-grid columns="4">
        <x-stat-card 
            title="Total Usuarios" 
            :value="$totalUsers"
            gradient="from-blue-500 to-blue-600"
            hover-color="blue-300"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>


        <x-stat-card 
            title="Suspendidos" 
            :value="$suspendedUsers"
            gradient="from-red-500 to-red-600"
            hover-color="red-300"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Verificados" 
            :value="$verifiedUsers"
            gradient="from-green-500 to-green-600"
            hover-color="green-300"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Últimos 7 días" 
            :value="$recentLogins"
            gradient="from-purple-500 to-purple-600"
            hover-color="purple-300"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>
    </x-stats-grid>


    {{-- Tabla de usuarios --}}
    <x-table-container
        :has-pagination="true"
        :page="$page"
        :per-page="$perPage"
        :total="$total"
        item-name="usuarios"
    >
        <!-- Filtros y búsqueda dentro de la tabla -->
        <div class="bg-white border-b border-slate-200 shadow-lg mb-2 md:mb-3 lg:mb-4">
            <div class="flex flex-wrap gap-1 sm:gap-1.5 md:gap-2 lg:gap-3 xl:gap-3 2xl:gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px] sm:min-w-[220px] md:min-w-[250px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar Usuario
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nombre o email..."
                            class="w-full pl-8 sm:pl-9 md:pl-10 lg:pl-10 xl:pl-8 2xl:pl-10 pr-2 sm:pr-3 md:pr-4 lg:pr-4 xl:pr-3 2xl:pr-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm hover:border-slate-300 shadow-lg"
                        >
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5 text-slate-400 absolute left-2 sm:left-2.5 md:left-3 lg:left-3 xl:left-2 2xl:left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por rol -->
                <div class="min-w-[140px] sm:min-w-[150px] md:min-w-[180px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Rol
                    </label>
                    <select 
                        wire:model.live="roleFilter"
                        class="w-full px-2 sm:px-3 md:px-4 lg:px-4 xl:px-3 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 active:scale-[0.98] transition-all duration-200 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm cursor-pointer shadow-md font-medium {{ $roleFilter !== 'all' ? 'border-blue-400 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-900 shadow-lg hover:border-blue-500 hover:shadow-xl hover:from-blue-200 hover:to-blue-100' : 'border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100/50 text-slate-800 hover:border-blue-300 hover:shadow-lg hover:from-blue-50 hover:to-slate-50' }} [&>option]:bg-white [&>option]:text-slate-800 [&>option]:py-2 [&>option:hover]:bg-gradient-to-r [&>option:hover]:from-blue-50 [&>option:hover]:to-blue-100 [&>option:checked]:bg-blue-500 [&>option:checked]:text-white"
                    >
                        <option value="all" class="py-2">✨ Todos</option>
                        <option value="admin" class="py-2">👑 Administrador</option>
                        <option value="owner" class="py-2">🏢 Propietario</option>
                        <option value="seller" class="py-2">💼 Vendedor</option>
                        <option value="client" class="py-2">👤 Cliente</option>
                    </select>
                </div>

                <!-- Filtro por estado -->
                <div class="min-w-[140px] sm:min-w-[150px] md:min-w-[180px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Estado
                    </label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full px-2 sm:px-3 md:px-4 lg:px-4 xl:px-3 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 active:scale-[0.98] transition-all duration-200 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm cursor-pointer shadow-md font-medium {{ $statusFilter !== 'all' ? 'border-blue-400 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-900 shadow-lg hover:border-blue-500 hover:shadow-xl hover:from-blue-200 hover:to-blue-100' : 'border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100/50 text-slate-800 hover:border-blue-300 hover:shadow-lg hover:from-blue-50 hover:to-slate-50' }} [&>option]:bg-white [&>option]:text-slate-800 [&>option]:py-2 [&>option:hover]:bg-gradient-to-r [&>option:hover]:from-blue-50 [&>option:hover]:to-blue-100 [&>option:checked]:bg-blue-500 [&>option:checked]:text-white"
                    >
                        <option value="all" class="py-2">✨ Todos</option>
                        <option value="active" class="py-2">✅ Activo</option>
                        <option value="suspended" class="py-2">🚫 Suspendido</option>
                        <option value="unverified" class="py-2">⚠️ No Verificado</option>
                    </select>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 border-b-2 border-indigo-200 shadow-sm">
                <x-table-header>Usuario</x-table-header>
                <x-table-header>Contacto</x-table-header>
                <x-table-header>Rol</x-table-header>
                <x-table-header>Estado</x-table-header>
                <x-table-header>Último Login</x-table-header>
                <x-table-header align="center">Acciones</x-table-header>
            </tr>
        </thead>
                    <tbody>
        @forelse ($users as $user)
            <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                <x-table-cell>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center shadow flex-shrink-0">
                                            <span class="text-white font-medium text-sm">
                                                {{ $user->initials() }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="relative group w-full">
                                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                            </div>
                                            <p class="text-sm text-slate-500 truncate">
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
                </x-table-cell>
                <x-table-cell>
                                    <div class="min-w-0">
                                        <div class="relative group w-full">
                                            <p class="text-slate-800 truncate w-full">{{ $user->email }}</p>
                                            <span class="absolute top-full left-0 mt-2 px-3 py-2 text-xs font-medium text-white bg-slate-800 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none whitespace-nowrap z-50 shadow-lg">
                                                {{ $user->email }}
                                            </span>
                                        </div>
                                        @if($user->phone)
                                            <p class="text-xs text-slate-500">{{ $user->phone }}</p>
                                        @endif
                                        @if($user->province)
                                            <p class="text-xs text-slate-400">{{ $user->city }}, {{ $user->province }}</p>
                                        @endif
                                    </div>
                </x-table-cell>
                <x-table-cell>
                                    @if($user->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Admin
                                        </span>
                                    @elseif($user->isOwner())
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Propietario
                                        </span>
                                    @elseif($user->isSeller())
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Vendedor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cliente
                                        </span>
                                    @endif
                </x-table-cell>
                <x-table-cell>
                                    @php 
                                        $statusColor = $user->getAccountStatusColor();
                                        $status = $user->getAccountStatus();
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-md whitespace-nowrap">
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
                </x-table-cell>
                <x-table-cell>
                                    @if($user->last_login_at)
                                        <span class="text-slate-700">{{ $user->last_login_at->diffForHumans() }}</span>
                                        <p class="text-sm text-slate-500 whitespace-nowrap">{{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                                    @else
                                        <span class="text-slate-400">Nunca</span>
                                    @endif
                </x-table-cell>
                <x-table-cell align="center">
                                    <div class="flex justify-center space-x-2">
                                        @if(!$user->isAdmin() || (\App\Models\User::where('role', 'admin')->count() > 1))
                                            <!-- Suspender/Reactivar -->
                                            @if($user->isSuspended())
                                                <x-action-button 
                                                    color="emerald"
                                                    tooltip="Reactivar cuenta de usuario"
                                                    wire:click="openUserModal({{ $user->id }}, 'unsuspend')"
                                                >
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </x-action-button>
                                            @else
                                                <x-action-button 
                                                    color="orange"
                                                    tooltip="Suspender cuenta de usuario"
                                                    wire:click="openUserModal({{ $user->id }}, 'suspend')"
                                                >
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </x-action-button>
                                            @endif
                                        @endif
                                        
                                        @if(!$user->email_verified_at)
                                            <!-- Verificar email -->
                                            <x-action-button 
                                                color="purple"
                                                tooltip="Verificar email del usuario"
                                                wire:click="openUserModal({{ $user->id }}, 'verify-email')"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                            </x-action-button>
                                        @endif
                                        
                                        @if(!$user->isAdmin() || (\App\Models\User::where('role', 'admin')->count() > 1))
                                            <!-- Eliminar (solo si no es el único admin) -->
                                            <x-action-button 
                                                color="red"
                                                tooltip="Eliminar usuario"
                                                wire:click="openUserModal({{ $user->id }}, 'delete')"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </x-action-button>
                                        @endif
                                    </div>
                </x-table-cell>
            </tr>
        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500">
                    No hay usuarios registrados
                </td>
            </tr>
        @endforelse
                    </tbody>
    </x-table-container>

    {{-- Modal de acción de usuario --}}
    @if ($showUserModal && $selectedUser)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50"
            x-data
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="!bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Icono del modal -->
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center {{ $actionType === 'suspend' ? 'bg-gradient-to-br from-orange-100 to-red-100' : ($actionType === 'unsuspend' ? 'bg-gradient-to-br from-emerald-100 to-green-100' : ($actionType === 'verify-email' ? 'bg-gradient-to-br from-purple-100 to-indigo-100' : 'bg-gradient-to-br from-red-100 to-rose-100')) }}">
                        @if ($actionType === 'suspend')
                            <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif ($actionType === 'unsuspend')
                            <svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif ($actionType === 'verify-email')
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </div>
                </div>

                <!-- Título -->
                <h3 class="text-xl font-bold text-slate-800 text-center mb-2" style="color: #1e293b !important;">
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

                <!-- Información del usuario -->
                <div class="!bg-gradient-to-br !from-slate-50 !to-blue-50 rounded-xl p-4 mb-6 space-y-2">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Usuario</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ $selectedUser->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold !text-slate-800 break-all">{{ $selectedUser->email }}</p>
                        </div>
                    </div>
                </div>
                    @if ($actionType === 'suspend')
                        <form wire:submit.prevent="suspend">
                            <div class="mb-4">
                                <label class="block text-sm font-medium !text-slate-700 mb-2">Duración de la suspensión</label>
                                <select class="w-full px-3 py-2 !border !border-slate-300 rounded-lg !bg-white !text-slate-800" wire:model="suspensionDays" required>
                                    <option value="1">1 día</option>
                                    <option value="7">7 días</option>
                                    <option value="30">30 días</option>
                                    <option value="90">90 días</option>
                                    <option value="365">1 año</option>
                                    <option value="permanent">Permanente</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium !text-slate-700 mb-2">Razón de la suspensión</label>
                                <textarea class="w-full px-3 py-2 !border !border-slate-300 rounded-lg !bg-white !text-slate-800" wire:model="actionReason" required rows="3"></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-xl hover:shadow-lg font-medium transition-all">Suspender</button>
                            </div>
                        </form>
                    @elseif ($actionType === 'unsuspend')
                        <p class="!text-slate-600 text-center mb-6">¿Estás seguro de reactivar este usuario? Recuperará el acceso completo al sistema.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:shadow-lg font-medium transition-all" wire:click="unsuspend">Reactivar</button>
                        </div>
                    @elseif ($actionType === 'verify-email')
                        <p class="!text-slate-600 text-center mb-6">El email de este usuario será marcado como verificado sin necesidad de confirmación.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:shadow-lg font-medium transition-all" wire:click="verifyEmail">Verificar</button>
                        </div>
                    @elseif ($actionType === 'delete')
                        <p class="!text-slate-600 text-center mb-6">⚠️ Esta acción es <strong class="!text-slate-800">irreversible</strong>. Se eliminarán todos los datos asociados a este usuario.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl hover:shadow-lg font-medium transition-all" wire:click="delete">Eliminar</button>
                        </div>
                    @endif
            </div>
        </div>
    @endif

    {{-- Modal de detalles de usuario --}}
    @if ($showUserDetailsModal && $userDetails)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50"
            x-data
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="!bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Icono principal -->
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center !bg-gradient-to-br !from-blue-100 !to-cyan-100">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Título -->
                <h3 class="text-xl font-bold !text-slate-800 text-center mb-2">Detalles de Usuario</h3>
                <p class="!text-slate-600 text-center mb-6">Información completa del usuario</p>
                
                <!-- Información del usuario -->
                <div class="!bg-gradient-to-br !from-slate-50 !to-blue-50 rounded-xl p-4 mb-6 space-y-3">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Nombre</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ $userDetails->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold !text-slate-800 break-all">{{ $userDetails->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg !bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Rol</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ ucfirst($userDetails->role) }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg {{ $userDetails->is_suspended ? '!bg-red-100' : '!bg-green-100' }} flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 {{ $userDetails->is_suspended ? 'text-red-600' : 'text-green-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($userDetails->is_suspended)
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Estado</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ $userDetails->is_suspended ? 'Suspendido' : 'Activo' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg {{ $userDetails->email_verified_at ? '!bg-green-100' : '!bg-yellow-100' }} flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 {{ $userDetails->email_verified_at ? 'text-green-600' : 'text-yellow-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($userDetails->email_verified_at)
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Verificado</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ $userDetails->email_verified_at ? 'Sí' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg !bg-purple-100 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium !text-slate-500 uppercase tracking-wide">Último Login</p>
                            <p class="text-sm font-semibold !text-slate-800">{{ $userDetails->last_login_at ? $userDetails->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Botón cerrar -->
                <button type="button" class="w-full px-4 py-3 bg-gradient-to-r from-slate-200 to-slate-300 text-slate-700 rounded-xl hover:from-slate-300 hover:to-slate-400 font-medium transition-all" wire:click="closeUserDetailsModal">
                    Cerrar
                </button>
            </div>
        </div>
    @endif
    
    {{-- Modal de creación de usuario --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50"
            x-data
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.self="$wire.set('showCreateModal', false)">
            <div class="max-w-md w-full relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                @livewire('admin.users.users-create')
            </div>
        </div>
    @endif
</div>