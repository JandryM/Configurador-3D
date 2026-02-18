<div>
    {{-- Encabezado de la sección --}}
    <x-page-header 
        title="Gestión de Usuarios"
        description="Administra cuentas de usuarios y permisos del sistema"
        :show-button="true"
        button-text="Nuevo Usuario"
        button-color="sky"
        x-data
        x-on:click="$wire.openCreateModal()"
    >
    </x-page-header>

    {{-- Mensaje flash --}}
    <x-flash-message type="success" />

    {{-- Estadísticas --}}
    <x-stats-grid columns="4">
        <x-stat-card 
            title="Total Usuarios" 
            :value="$totalUsers"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Suspendidos" 
            :value="$suspendedUsers"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Verificados" 
            :value="$verifiedUsers"
            icon-color="text-teal-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card 
            title="Últimos 7 días" 
            :value="$recentLogins"
            icon-color="text-orange-600"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
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
        <div class="bg-blue-50 border-b border-custom-blue/20 mb-4 px-4 py-3">
            <div class="flex flex-wrap gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Buscar Usuario
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Nombre o email..."
                            class="w-full pl-9 pr-3 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 text-sm transition-shadow"
                        >
                        <svg class="w-4 h-4 text-sky-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por rol -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Rol
                    </label>
                    <select 
                        wire:model.live="roleFilter"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="all">Todos</option>
                        <option value="admin">Administrador</option>
                        <option value="owner">Propietario</option>
                        <option value="client">Cliente</option>
                    </select>
                </div>

                <!-- Filtro por estado -->
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Estado
                    </label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full pl-3 pr-8 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 text-sm cursor-pointer hover:border-slate-400 transition-colors"
                    >
                        <option value="all">Todos</option>
                        <option value="active">Activo</option>
                        <option value="suspended">Suspendido</option>
                        <option value="unverified">No Verificado</option>
                    </select>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-blue-50 border-b border-custom-blue/20">
                <x-table-header>Usuario</x-table-header>
                <x-table-header>Contacto</x-table-header>
                <x-table-header>Rol</x-table-header>
                <x-table-header>Estado</x-table-header>
                <x-table-header>Último Login</x-table-header>
                <x-table-header align="center">Acciones</x-table-header>
            </tr>
        </thead>
                    <tbody>
        {{-- Mostrar usuario logeado como primer elemento --}}
        <tr class="border-b border-custom-blue/10 bg-blue-50/80 hover:bg-blue-100/40 transition-all duration-150">
            <x-table-cell>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 shadow-md text-white font-bold text-sm" 
                             style="background: linear-gradient(135deg, 
                                @if(ord(auth()->user()->initials()[0]) % 3 == 0)
                                    #ff6b6b, #ff8c42
                                @elseif(ord(auth()->user()->initials()[0]) % 3 == 1)
                                    #4c6ef5, #5c7cfa
                                @else
                                    #00d9a3, #00b894
                                @endif
                             )">
                            <span>{{ auth()->user()->initials() }}</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-custom-blue/40 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 bg-custom-blue/15 text-custom-blue text-xs font-semibold rounded-full border border-custom-blue/30 whitespace-nowrap">
                                Tú
                            </span>
                        </div>
                        @if(auth()->user()->oauth_provider && strtolower(auth()->user()->oauth_provider) === 'google')
                            <div class="inline-flex items-center gap-1 text-sm text-slate-500">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#F2F2F2"/>
                                    <path d="M19.6 12.2c0-.82-.1-1.42-.25-2.05H12v3.72h4.3c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z" fill="#4285F4"/>
                                    <path d="M13.46 15.13c-.83.8-2.18 1.9-4.46 1.9-3.37 0-6.18-2.74-6.18-6.12 0-3.38 2.81-6.12 6.18-6.12 1.88 0 3.14.61 4.33 1.75l2.45-2.41C16.27 2.5 14.21 1.5 12 1.5 6.48 1.5 2 6.01 2 11.5s4.48 10 10 10c2.7 0 4.76-.88 6.3-2.64l-2.13-1.73z" fill="#34A853"/>
                                    <path d="M12 22c2.1 0 3.92-.64 5.23-1.82l-3.16-2.45c-.91.64-2.05 1.08-3.39 1.08-2.27 0-4.26-1.48-4.99-3.64H3.42v2.5C4.74 20.33 8.15 22 12 22z" fill="#EA4335"/>
                                    <path d="M7.07 14.17c-.18-.64-.27-1.31-.27-2s.1-1.36.26-2v-2.5H3.42C2.85 7.87 2.5 9.61 2.5 11.5s.35 3.63.92 5.17l3.16-2.5z" fill="#FBBC04"/>
                                </svg>
                                <span class="font-medium">Google</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1 text-sm text-slate-500">
                                <svg class="w-4 h-4 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded p-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                </svg>
                                <span class="font-medium text-slate-700">Local</span>
                            </div>
                        @endif
                    </div>
                </div>
            </x-table-cell>
            <x-table-cell>
                <div class="min-w-0">
                    <p class="text-slate-800 font-medium truncate">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->phone)
                        <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->phone }}</p>
                    @endif
                    @if(auth()->user()->province)
                        <p class="text-xs text-slate-400">{{ auth()->user()->city }}, {{ auth()->user()->province }}</p>
                    @endif
                </div>
            </x-table-cell>
            <x-table-cell>
                @if(auth()->user()->isAdmin())
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-orange-500/20 to-red-600/20 text-orange-700 rounded-full border border-orange-500/30">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0110 1.944 11.954 11.954 0 0117.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Administrador
                    </span>
                @elseif(auth()->user()->isOwner())
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-purple-500/20 to-indigo-600/20 text-purple-700 rounded-full border border-purple-500/30">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        Propietario
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-blue-500/20 to-cyan-600/20 text-blue-700 rounded-full border border-blue-500/30">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        Cliente
                    </span>
                @endif
            </x-table-cell>
            <x-table-cell>
                @php 
                    $statusColor = auth()->user()->getAccountStatusColor();
                    $status = auth()->user()->getAccountStatus();
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-md whitespace-nowrap">
                    @if(auth()->user()->isSuspended())
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif(auth()->user()->email_verified_at)
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
            </x-table-cell>
            <x-table-cell>
                @if(auth()->user()->last_login_at)
                    <span class="text-slate-700">{{ auth()->user()->last_login_at->locale('es')->diffForHumans() }}</span>
                    <p class="text-sm text-slate-500 whitespace-nowrap">{{ auth()->user()->last_login_at->format('d/m/Y H:i') }}</p>
                @else
                    <span class="text-slate-400">Nunca</span>
                @endif
            </x-table-cell>
            <x-table-cell align="center">
                <span class="text-xs font-medium text-slate-400 italic">Sin acciones</span>
            </x-table-cell>
        </tr>

        @forelse ($users as $user)
            <tr class="border-b border-custom-blue/10 hover:bg-blue-50/50 transition-all duration-150">
                <x-table-cell>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br rounded-full flex items-center justify-center flex-shrink-0 shadow-md text-white font-bold text-sm" 
                                             style="background: linear-gradient(135deg, 
                                                @if(ord($user->initials()[0]) % 3 == 0)
                                                    #ff6b6b, #ff8c42
                                                @elseif(ord($user->initials()[0]) % 3 == 1)
                                                    #4c6ef5, #5c7cfa
                                                @else
                                                    #00d9a3, #00b894
                                                @endif
                                             )">
                                            <span>
                                                {{ $user->initials() }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="relative group w-full">
                                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                            </div>
                                            <p class="text-sm text-slate-500 truncate">
                                                @if($user->oauth_provider && strtolower($user->oauth_provider) === 'google')
                                                    <span class="inline-flex items-center gap-1">
                                                        <!-- Google Logo a Color -->
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <circle cx="12" cy="12" r="10" fill="#F2F2F2"/>
                                                            <path d="M19.6 12.2c0-.82-.1-1.42-.25-2.05H12v3.72h4.3c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z" fill="#4285F4"/>
                                                            <path d="M13.46 15.13c-.83.8-2.18 1.9-4.46 1.9-3.37 0-6.18-2.74-6.18-6.12 0-3.38 2.81-6.12 6.18-6.12 1.88 0 3.14.61 4.33 1.75l2.45-2.41C16.27 2.5 14.21 1.5 12 1.5 6.48 1.5 2 6.01 2 11.5s4.48 10 10 10c2.7 0 4.76-.88 6.3-2.64l-2.13-1.73z" fill="#34A853"/>
                                                            <path d="M12 22c2.1 0 3.92-.64 5.23-1.82l-3.16-2.45c-.91.64-2.05 1.08-3.39 1.08-2.27 0-4.26-1.48-4.99-3.64H3.42v2.5C4.74 20.33 8.15 22 12 22z" fill="#EA4335"/>
                                                            <path d="M7.07 14.17c-.18-.64-.27-1.31-.27-2s.1-1.36.26-2v-2.5H3.42C2.85 7.87 2.5 9.61 2.5 11.5s.35 3.63.92 5.17l3.16-2.5z" fill="#FBBC04"/>
                                                        </svg>
                                                        <span class="text-xs font-medium">Google</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1">
                                                        <!-- Local Registration Badge with Colors -->
                                                        <svg class="w-4 h-4 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded p-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                                                        </svg>
                                                        <span class="text-xs font-medium text-slate-700">Local</span>
                                                    </span>
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-orange-500/20 to-red-600/20 text-orange-700 rounded-full border border-orange-500/30">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Administrador
                                        </span>
                                    @elseif($user->isOwner())
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-purple-500/20 to-indigo-600/20 text-purple-700 rounded-full border border-purple-500/30">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            Propietario
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-blue-500/20 to-cyan-600/20 text-blue-700 rounded-full border border-blue-500/30">
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
                                        <span class="text-slate-700">{{ $user->last_login_at->locale('es')->diffForHumans() }}</span>
                                        <p class="text-sm text-slate-500 whitespace-nowrap">{{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                                    @else
                                        <span class="text-slate-400">Nunca</span>
                                    @endif
                </x-table-cell>
                <x-table-cell align="center">
                                    <div class="flex justify-center space-x-2">
                                        @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
                                            <!-- Cambiar Rol (solo admin) -->
                                            <x-action-button 
                                                color="blue"
                                                tooltip="Cambiar rol del usuario"
                                                wire:click="openChangeRoleModal({{ $user->id }})"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                                </svg>
                                            </x-action-button>
                                        @endif
                                        
                                        @if(!$user->isAdmin() || (\App\Models\User::where('role', 'admin')->count() > 1))
                                            <!-- Suspender/Reactivar -->
                                            @if($user->isSuspended())
                                                <x-action-button 
                                                    color="teal"
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
            <div class="!bg-blue-50 rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Header -->
                <div class="sticky top-0 bg-blue-50 border-b border-custom-blue/20 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                    <div class="flex items-center space-x-3">
                        @if($actionType === 'delete')
                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center border border-red-100">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @elseif ($actionType === 'suspend')
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center border border-orange-100">
                                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @elseif ($actionType === 'unsuspend')
                            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center border border-teal-100">
                                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @elseif ($actionType === 'verify-email')
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-slate-800" style="color: #1e293b !important;">
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
                    </div>
                    <button type="button" class="text-slate-400 hover:text-slate-500" wire:click="closeUserModal">
                        <span class="sr-only">Cerrar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
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
                <div class="bg-blue-50/50 border border-custom-blue/20 rounded-xl p-4 mb-6 space-y-2">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Usuario</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $selectedUser->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold text-slate-800 break-all">{{ $selectedUser->email }}</p>
                        </div>
                    </div>
                </div>
                    @if ($actionType === 'suspend')
                        <form wire:submit.prevent="suspend">
                            <div class="mb-4">
                                <label class="block text-sm font-medium !text-slate-700 mb-2">Duración de la suspensión</label>
                                <select class="w-full px-3 py-2 !border !border-custom-blue/30 rounded-lg !bg-white !text-slate-800" wire:model="suspensionDays" required>
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
                                <textarea class="w-full px-3 py-2 !border !border-custom-blue/30 rounded-lg !bg-white !text-slate-800" wire:model="actionReason" required rows="3"></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" class="flex-1 px-4 py-3 bg-blue-100/50 text-slate-700 rounded-lg hover:bg-blue-100 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                                <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:from-slate-800 hover:to-slate-900 shadow-sm font-medium transition-all">Suspender</button>
                            </div>
                        </form>
                    @elseif ($actionType === 'unsuspend')
                        <p class="text-slate-600 text-center mb-6">¿Estás seguro de reactivar este usuario? Recuperará el acceso completo al sistema.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-blue-100/50 text-slate-700 rounded-lg hover:bg-blue-100 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 shadow-sm font-medium transition-all" wire:click="unsuspend">Reactivar</button>
                        </div>
                    @elseif ($actionType === 'verify-email')
                        <p class="text-slate-600 text-center mb-6">El email de este usuario será marcado como verificado sin necesidad de confirmación.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-blue-100/50 text-slate-700 rounded-lg hover:bg-blue-100 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 shadow-sm font-medium transition-all" wire:click="verifyEmail">Verificar</button>
                        </div>
                    @elseif ($actionType === 'delete')
                        <p class="text-slate-600 text-center mb-6">Esta acción es <strong class="text-slate-900">irreversible</strong>. Se eliminarán todos los datos asociados a este usuario.</p>
                        <div class="flex gap-3">
                            <button type="button" class="flex-1 px-4 py-3 bg-blue-100/50 text-slate-700 rounded-lg hover:bg-blue-100 font-medium transition-all" wire:click="closeUserModal">Cancelar</button>
                            <button type="button" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 shadow-sm font-medium transition-all" wire:click="delete">Eliminar</button>
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
            <div class="!bg-blue-50 rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Icono principal -->
                <div class="flex items-center justify-center mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center bg-blue-100/30 border border-custom-blue/30">
                        <svg class="w-8 h-8 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Título -->
                <h3 class="text-xl font-bold text-slate-900 text-center mb-1">Detalles de Usuario</h3>
                <p class="text-slate-500 text-center mb-6 text-sm">Información completa del usuario</p>
                
                <!-- Información del usuario -->
                <div class="bg-blue-50/50 border border-custom-blue/20 rounded-xl p-4 mb-6 space-y-3">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Nombre</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $userDetails->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold text-slate-800 break-all">{{ $userDetails->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Rol</p>
                            <p class="text-sm font-semibold text-slate-800">{{ ucfirst($userDetails->role) }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 {{ $userDetails->is_suspended ? 'text-red-500' : 'text-teal-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($userDetails->is_suspended)
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Estado</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $userDetails->is_suspended ? 'Suspendido' : 'Activo' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 {{ $userDetails->email_verified_at ? 'text-teal-500' : 'text-orange-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($userDetails->email_verified_at)
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Verificado</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $userDetails->email_verified_at ? 'Sí' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Último Login</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $userDetails->last_login_at ? $userDetails->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Botón cerrar -->
                <button type="button" class="w-full px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all" wire:click="closeUserDetailsModal">
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

    {{-- Modal de cambio de rol --}}
    @if ($showChangeRoleModal && $selectedUser)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/50"
            x-data
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="!bg-blue-50 rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">
                
                <!-- Icono del modal -->
                <div class="flex items-center justify-center mb-6">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100/30 border border-custom-blue/30">
                        <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Título -->
                <h3 class="text-xl font-bold text-slate-900 text-center mb-1">
                    Cambiar Rol de Usuario
                </h3>

                <!-- Información del usuario -->
                <div class="bg-blue-50/50 border border-custom-blue/20 rounded-xl p-4 mb-6 space-y-2">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Usuario</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $selectedUser->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                            <p class="text-sm font-semibold text-slate-800 break-all">{{ $selectedUser->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-lg bg-white border border-custom-blue/30 flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Rol Actual</p>
                            @if($selectedUser->isAdmin())
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-orange-500/20 to-red-600/20 text-orange-700 rounded-full border border-orange-500/30">Administrador</span>
                            @elseif($selectedUser->isOwner())
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-purple-500/20 to-indigo-600/20 text-purple-700 rounded-full border border-purple-500/30">Propietario</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-gradient-to-r from-blue-500/20 to-cyan-600/20 text-blue-700 rounded-full border border-blue-500/30">Cliente</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="changeRole">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nuevo Rol</label>
                        <select class="w-full px-3 py-2 border border-custom-blue/30 rounded-lg bg-white text-slate-800 focus:ring-2 focus:ring-slate-900 focus:border-slate-900" wire:model="newRole" required>
                            <option value="">Seleccionar rol...</option>
                            <option value="admin">Administrador</option>
                            <option value="owner">Propietario</option>
                            <option value="client">Cliente</option>
                        </select>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                        <p class="text-xs text-yellow-800">
                            <strong>Advertencia:</strong> Cambiar el rol modificará los permisos y accesos del usuario en el sistema.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" class="flex-1 px-4 py-3 bg-blue-100/50 text-slate-700 rounded-lg hover:bg-blue-100 font-medium transition-all" wire:click="closeChangeRoleModal">Cancelar</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm font-medium transition-all">Cambiar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>