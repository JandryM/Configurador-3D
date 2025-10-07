<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-gradient-to-b from-zinc-50 via-white to-zinc-50 dark:border-zinc-700 dark:bg-gradient-to-b dark:from-zinc-900 dark:via-gray-900 dark:to-zinc-900 shadow-xl">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- Logo con gradiente -->
            <div class="relative mb-6">
                <a href="{{ (auth()->check() && auth()->user()->isAdmin()) ? route('admin.dashboard') : route('dashboard') }}" 
                   class="me-5 flex items-center space-x-3 rtl:space-x-reverse p-4 rounded-2xl bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-600 hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-white/20" 
                   wire:navigate>
                    <div class="relative">
                        <x-app-logo class="w-8 h-8 drop-shadow-lg" />
                        <div class="absolute inset-0 bg-white/10 rounded-lg"></div>
                    </div>
                    <span class="font-bold text-white text-lg drop-shadow-sm">Quality</span>
                </a>
            </div>

            <!-- Navegación principal con estilos modernos -->
            <div class="space-y-6 px-4">
                <!-- Platform Section -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-2xl"></div>
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 border border-blue-200/50 dark:border-blue-700/30 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mr-2"></div>
                            {{ __('Platform') }}
                        </h3>
                        <div class="space-y-2">
                            <flux:navlist.item 
                                icon="home" 
                                :href="auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard')" 
                                :current="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard')" 
                                wire:navigate
                                class="rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-300 border border-transparent hover:border-blue-200/50 dark:hover:border-blue-700/30">
                                {{ __('Dashboard') }}
                            </flux:navlist.item>
                            
                            @if(auth()->user()->isAdmin())
                                <flux:navlist.item 
                                    icon="users" 
                                    :href="route('admin.users')" 
                                    :current="request()->routeIs('admin.users')" 
                                    wire:navigate
                                    class="rounded-xl hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 dark:hover:from-purple-900/20 dark:hover:to-pink-900/20 transition-all duration-300 border border-transparent hover:border-purple-200/50 dark:hover:border-purple-700/30">
                                    {{ __('Gestión de Usuarios') }}
                                </flux:navlist.item>
                                <flux:navlist.item 
                                    icon="cube" 
                                    :href="route('admin.products.index')" 
                                    :current="request()->routeIs('admin.products.*')" 
                                    wire:navigate
                                    class="rounded-xl hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-green-900/20 dark:hover:to-emerald-900/20 transition-all duration-300 border border-transparent hover:border-green-200/50 dark:hover:border-green-700/30">
                                    {{ __('Gestión de Productos') }}
                                </flux:navlist.item>
                                <flux:navlist.item 
                                    icon="shield-check" 
                                    :href="route('admin.dashboard')" 
                                    :current="request()->routeIs('admin.dashboard')" 
                                    wire:navigate
                                    class="rounded-xl hover:bg-gradient-to-r hover:from-yellow-50 hover:to-orange-50 dark:hover:from-yellow-900/20 dark:hover:to-orange-900/20 transition-all duration-300 border border-transparent hover:border-yellow-200/50 dark:hover:border-yellow-700/30">
                                    {{ __('Panel Admin') }}
                                </flux:navlist.item>
                            @endif
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isClient())
                <!-- Mi Cuenta Section -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 rounded-2xl"></div>
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 border border-purple-200/50 dark:border-purple-700/30 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full mr-2"></div>
                            {{ __('Mi Cuenta') }}
                        </h3>
                        <div class="space-y-2">
                            <flux:navlist.item 
                                icon="user" 
                                :href="route('settings.profile')" 
                                :current="request()->routeIs('settings.profile')" 
                                wire:navigate
                                class="rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-300 border border-transparent hover:border-blue-200/50 dark:hover:border-blue-700/30">
                                {{ __('Mi Perfil') }}
                            </flux:navlist.item>
                            <flux:navlist.item 
                                icon="key" 
                                :href="route('settings.password')" 
                                :current="request()->routeIs('settings.password')" 
                                wire:navigate
                                class="rounded-xl hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 dark:hover:from-green-900/20 dark:hover:to-emerald-900/20 transition-all duration-300 border border-transparent hover:border-green-200/50 dark:hover:border-green-700/30">
                                {{ __('Seguridad') }}
                            </flux:navlist.item>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Galería Section -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-2xl"></div>
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 border border-green-200/50 dark:border-green-700/30 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full mr-2"></div>
                            {{ __('Galería') }}
                        </h3>
                        <div class="space-y-2">
                            <flux:navlist.item 
                                icon="photo" 
                                :href="route('galeria')" 
                                :current="request()->routeIs('galeria')" 
                                wire:navigate
                                class="rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-300 border border-transparent hover:border-blue-200/50 dark:hover:border-blue-700/30">
                                {{ __('Ver Galería') }}
                            </flux:navlist.item>
                            @if(auth()->user()->isClient())
                                <div class="rounded-xl p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 border border-gray-200/50 dark:border-gray-600/50">
                                    <div class="flex items-center opacity-50">
                                        <flux:icon.heart class="w-4 h-4 mr-2 text-gray-400" />
                                        <span class="text-sm text-gray-400">{{ __('Mis Favoritos') }}</span>
                                        <span class="ml-auto text-xs text-gray-400">(Próximamente)</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sistema de Calidad Section -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/10 dark:to-orange-900/10 rounded-2xl"></div>
                    <div class="relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 border border-yellow-200/50 dark:border-yellow-700/30 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-full mr-2"></div>
                            {{ __('Sistema de Calidad') }}
                        </h3>
                        <div class="space-y-2">
                            <div class="rounded-xl p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 border border-gray-200/50 dark:border-gray-600/50">
                                <div class="flex items-center opacity-50">
                                    <flux:icon.clipboard-document-list class="w-4 h-4 mr-2 text-gray-400" />
                                    <span class="text-sm text-gray-400">{{ __('Mis Procesos') }}</span>
                                    <span class="ml-auto text-xs text-gray-400">(Próximamente)</span>
                                </div>
                            </div>
                            <div class="rounded-xl p-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 border border-gray-200/50 dark:border-gray-600/50">
                                <div class="flex items-center opacity-50">
                                    <flux:icon.chart-bar class="w-4 h-4 mr-2 text-gray-400" />
                                    <span class="text-sm text-gray-400">{{ __('Reportes') }}</span>
                                    <span class="ml-auto text-xs text-gray-400">(Próximamente)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <flux:spacer />

            <!-- Enlaces del footer con estilos modernos -->
            <div class="px-4 space-y-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-xl"></div>
                    <a href="{{ route('home') }}" 
                       target="_blank" 
                       wire:navigate
                       class="relative flex items-center p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-indigo-200/50 dark:border-indigo-700/30 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mr-3 group-hover:from-blue-700 group-hover:to-indigo-700 transition-all duration-300">
                            <flux:icon.information-circle class="w-4 h-4 text-white" />
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                            {{ __('Sitio Web Principal') }}
                        </span>
                    </a>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/10 dark:to-pink-900/10 rounded-xl"></div>
                    <a href="{{ route('proforma') }}" 
                       target="_blank" 
                       wire:navigate
                       class="relative flex items-center p-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-purple-200/50 dark:border-purple-700/30 shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mr-3 group-hover:from-purple-700 group-hover:to-pink-700 transition-all duration-300">
                            <flux:icon.document-text class="w-4 h-4 text-white" />
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">
                            {{ __('Solicitar Proforma') }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Desktop User Menu con estilos modernos -->
            <div class="px-4 pb-4">
                <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-600 rounded-2xl"></div>
                        <div class="relative bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-2xl p-4 border border-white/50 dark:border-gray-700/50 shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                            <flux:profile
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                                icon:trailing="chevrons-up-down"
                                class="group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                    </div>

                    <flux:menu class="w-[220px] bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border border-gray-200/50 dark:border-gray-700/50 shadow-2xl rounded-2xl">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-3 py-3 text-start text-sm bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-t-2xl border-b border-gray-200/50 dark:border-gray-700/50">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                                        <span class="flex h-full w-full items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-bold shadow-lg">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</span>
                                        <div class="flex items-center gap-1 mt-1">
                                            @php
                                                $status = auth()->user()->getAccountStatus();
                                                $color = auth()->user()->getAccountStatusColor();
                                            @endphp
                                            <span class="inline-block w-2 h-2 rounded-full shadow-sm
                                                @if($color === 'green') bg-gradient-to-r from-green-400 to-emerald-500
                                                @elseif($color === 'yellow') bg-gradient-to-r from-yellow-400 to-orange-500
                                                @elseif($color === 'orange') bg-gradient-to-r from-orange-400 to-red-500
                                                @elseif($color === 'red') bg-gradient-to-r from-red-400 to-red-600
                                                @else bg-gradient-to-r from-gray-400 to-gray-500
                                                @endif"></span>
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">{{ $status }}</span>
                                            @if(auth()->user()->isAdmin())
                                                <span class="ml-1 text-xs font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate 
                                          class="rounded-lg mx-2 my-1 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-blue-900/20 dark:hover:to-indigo-900/20 transition-all duration-300">
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full" onsubmit="if(window.authSync) window.authSync.notifyLogout();">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" 
                                          class="w-full rounded-lg mx-2 my-1 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 dark:hover:from-red-900/20 dark:hover:to-pink-900/20 transition-all duration-300">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>

            <!-- Mobile Responsive Menu -->
            <div class="lg:hidden" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-full" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-full">
                <div class="fixed inset-0 z-40 flex">
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" x-on:click="open = false"></div>
                    
                    <div class="relative flex w-full max-w-xs flex-1 flex-col bg-gradient-to-br from-slate-900 via-gray-900 to-zinc-900 shadow-2xl">
                        <div class="absolute right-0 top-0 -mr-12 pt-2">
                            <button type="button" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white/50 bg-white/10 backdrop-blur-sm" x-on:click="open = false">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="h-0 flex-1 overflow-y-auto pb-4">
                            <!-- Mobile Logo -->
                            <div class="flex shrink-0 items-center px-6 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-xl blur-lg opacity-60 animate-pulse"></div>
                                        <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 p-3 rounded-xl shadow-2xl">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 8.172V5L8 4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h1 class="text-xl font-bold text-white">Quality</h1>
                                        <p class="text-sm text-gray-300 font-medium">Management</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Navigation -->
                            <nav class="mt-2 space-y-2 px-4">
                                <!-- Platform Section -->
                                <div class="mb-6">
                                    <h3 class="mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Plataforma</h3>
                                    <div class="space-y-1">
                                        <a href="{{ route('dashboard') }}" wire:navigate
                                           class="group flex items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-blue-600/10 to-indigo-600/10 border border-blue-600/20 hover:from-blue-600/20 hover:to-indigo-600/20 text-blue-100 hover:text-white">
                                            <svg class="mr-3 h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7 5 5 5-5"></path>
                                            </svg>
                                            Dashboard
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.products.index') }}" wire:navigate
                                               class="group flex items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-blue-600/10 to-indigo-600/10 border border-blue-600/20 hover:from-blue-600/20 hover:to-indigo-600/20 text-blue-100 hover:text-white">
                                                <svg class="mr-3 h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 4.5v15M15 4.5v15"></path>
                                                </svg>
                                                Productos
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Account Section -->
                                <div class="mb-6">
                                    <h3 class="mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Cuenta</h3>
                                    <div class="space-y-1">
                                        <a href="{{ route('settings.profile') }}" wire:navigate
                                           class="group flex items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-purple-600/10 to-pink-600/10 border border-purple-600/20 hover:from-purple-600/20 hover:to-pink-600/20 text-purple-100 hover:text-white">
                                            <svg class="mr-3 h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Perfil
                                        </a>
                                    </div>
                                </div>

                                <!-- Gallery Section -->
                                <div class="mb-6">
                                    <h3 class="mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Galería</h3>
                                    <div class="space-y-1">
                                        <a href="/gallery" 
                                           class="group flex items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-green-600/10 to-emerald-600/10 border border-green-600/20 hover:from-green-600/20 hover:to-emerald-600/20 text-green-100 hover:text-white">
                                            <svg class="mr-3 h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Nuestros Trabajos
                                        </a>
                                    </div>
                                </div>

                                <!-- Quality System Section -->
                                <div class="mb-6">
                                    <h3 class="mb-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistema de Calidad</h3>
                                    <div class="space-y-1">
                                        <a href="/quality" 
                                           class="group flex items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-yellow-600/10 to-orange-600/10 border border-yellow-600/20 hover:from-yellow-600/20 hover:to-orange-600/20 text-yellow-100 hover:text-white">
                                            <svg class="mr-3 h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Control de Calidad
                                        </a>
                                    </div>
                                </div>
                            </nav>

                            <!-- Mobile User Profile -->
                            <div class="border-t border-gray-700/50 px-4 pt-4 mt-6">
                                <div class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-gray-800/50 to-gray-700/50 border border-gray-600/30 backdrop-blur-sm">
                                    <div class="relative">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-bold shadow-lg">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                        @php
                                            $color = auth()->user()->getAccountStatusColor();
                                        @endphp
                                        <span class="absolute -bottom-1 -right-1 block h-3 w-3 rounded-full ring-2 ring-gray-800
                                            @if($color === 'green') bg-gradient-to-r from-green-400 to-emerald-500
                                            @elseif($color === 'yellow') bg-gradient-to-r from-yellow-400 to-orange-500
                                            @elseif($color === 'orange') bg-gradient-to-r from-orange-400 to-red-500
                                            @elseif($color === 'red') bg-gradient-to-r from-red-400 to-red-600
                                            @else bg-gradient-to-r from-gray-400 to-gray-500
                                            @endif"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-300 truncate">{{ auth()->user()->email }}</p>
                                        @if(auth()->user()->isAdmin())
                                            <span class="inline-block text-xs font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Admin</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <form method="POST" action="{{ route('logout') }}" onsubmit="if(window.authSync) window.authSync.notifyLogout();">
                                        @csrf
                                        <button type="submit" class="group flex w-full items-center rounded-xl px-3 py-3 text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg bg-gradient-to-r from-red-600/10 to-pink-600/10 border border-red-600/20 hover:from-red-600/20 hover:to-pink-600/20 text-red-100 hover:text-white">
                                            <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </flux:sidebar>
</aside>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full" onsubmit="if(window.authSync) window.authSync.notifyLogout();">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
