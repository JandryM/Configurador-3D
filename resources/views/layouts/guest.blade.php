<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.core.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.theme.min.css">
        <style>
            /* Estilos para navbar */
            .nav-link {
                position: relative;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: -5px;
                left: 50%;
                background: linear-gradient(to right, #2563eb, #4f46e5);
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }
            .nav-link:hover::after {
                width: 100%;
            }
            
            /* Glide bullets personalizado */
            .glide__bullet--active {
                background: white !important;
                transform: scale(1.2);
            }
            
            /* Mejores sombras y efectos */
            nav {
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            /* Dropdown hover effect */
            .group:hover .group-hover\:opacity-100 {
                opacity: 1;
            }
            .group:hover .group-hover\:visible {
                visibility: visible;
            }
            
            /* Avatar gradient animation */
            .avatar-gradient {
                background: linear-gradient(45deg, #2563eb, #4f46e5, #7c3aed);
                background-size: 200% 200%;
                animation: gradient-shift 3s ease infinite;
            }
            
            @keyframes gradient-shift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Wiggle animation para botón usuario (solo si hay proformas) */
            @keyframes wiggle {
                0%   { transform: rotate(0deg); }
                5%   { transform: rotate(-6deg); }
                11%  { transform: rotate(6deg); }
                16%  { transform: rotate(-4deg); }
                19%  { transform: rotate(0deg); }
                100% { transform: rotate(0deg); }
            }
            .animate-wiggle {
                animation: wiggle 2.5s ease-in-out infinite;
                transform-origin: center;
            }
            .animate-wiggle:hover {
                animation: none;
            }
        </style>
        @livewireStyles
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-gray-900" x-data x-init="
        // Inicializar stores de Alpine.js
        window.Alpine && Alpine.store('loginModal') === undefined ? Alpine.store('loginModal', { open: false }) : null;
        window.Alpine && Alpine.store('registerModal') === undefined ? Alpine.store('registerModal', { open: false }) : null;
        
        // Verificar si se debe mostrar algún modal automáticamente
        @if(session('show_login_modal'))
            setTimeout(() => {
                if (window.Alpine && Alpine.store('loginModal')) {
                    Alpine.store('loginModal').open = true;
                }
            }, 100);
        @endif
        
        @if(session('show_register_modal'))
            setTimeout(() => {
                if (window.Alpine && Alpine.store('registerModal')) {
                    Alpine.store('registerModal').open = true;
                }
            }, 100);
        @endif
    ">
        <!-- Navegación -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-black/70 backdrop-blur-md transition-all duration-300">
            <div class="container mx-auto px-3 sm:px-4 md:px-5 lg:px-6 py-1.5 sm:py-2 md:py-2.5 lg:py-3 relative">
                <div class="flex items-center justify-between w-full">
                    <!-- Logo izquierdo -->
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="w-8 h-8 sm:w-10 sm:h-10 md:w-11 md:h-11 lg:w-12 lg:h-12 xl:w-14 xl:h-14 rounded-lg sm:rounded-xl drop-shadow-[0_5px_10px_rgba(0,255,255,0.9)] object-cover">
                        <span class="text-base sm:text-lg md:text-xl lg:text-xl xl:text-2xl font-bold text-white">Quality</span>
                    </div>
                    <!-- Navegación Desktop -->
                    <div class="hidden lg:flex items-center space-x-2 md:space-x-3 lg:space-x-4 xl:space-x-6 flex-1 justify-center">
                        <a href="{{ url('/')}}#inicio" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-xs md:text-sm xl:text-base">Inicio</a>
                        <a href="{{ url('/')}}#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-xs md:text-sm xl:text-base">Nosotros</a>
                        <a href="{{ url('/')}}#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-xs md:text-sm xl:text-base">Servicios</a>
                        <a href="{{ url('/')}}#galeria" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-xs md:text-sm xl:text-base">Galería</a>
                        <div x-data="{ open: false, closeTimeout: null }" class="relative group">
                            <button @mouseenter="open = true; clearTimeout(closeTimeout)" @mouseleave="closeTimeout = setTimeout(() => open = false, 200)" @click="open = !open" type="button"
                                class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium bg-gradient-to-r from-green-500 to-blue-500 px-2 py-1 md:px-2.5 md:py-1.5 xl:px-4 xl:py-2 rounded-lg font-semibold ml-1 md:ml-2 text-xs md:text-sm xl:text-base flex items-center gap-1 shadow-lg shadow-green-500/20 hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                Proforma
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div x-show="open" @mouseenter="open = true; clearTimeout(closeTimeout)" @mouseleave="closeTimeout = setTimeout(() => open = false, 200)"
                                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 mt-2 w-64 bg-white dark:bg-gray-900 rounded-xl shadow-2xl z-50 py-2 ring-1 ring-blue-400/10 border border-blue-400/10"
                                style="display: none; min-width: 220px;" x-cloak>
                                @if(isset($customizableProducts) && count($customizableProducts))
                                    @foreach($customizableProducts as $product)
                                        <a href="{{ route('configurador', $product->slug) }}"
                                           class="flex items-center gap-2 px-5 py-2 text-gray-800 dark:text-gray-100 hover:bg-gradient-to-r hover:from-green-100 hover:to-blue-100 dark:hover:from-blue-900/40 dark:hover:to-green-900/30 transition-colors duration-200 text-sm font-medium rounded-lg group/item">
                                            <span class="inline-block w-2 h-2 rounded-full bg-gradient-to-r from-green-400 to-blue-400 group-hover/item:scale-125 transition-transform"></span>
                                            <span>{{ $product->name }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="block px-5 py-2 text-gray-400 text-sm">No hay productos personalizables</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ url('/')}}#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-xs md:text-sm xl:text-base">Ubicación</a>
                        
                        @auth
                            <!-- Usuario logueado -->
                            <div class="flex items-center space-x-1.5 md:space-x-2 xl:space-x-4">
                                @php $hasProformas = \App\Models\Proforma::where('user_id', auth()->id())->exists(); @endphp
                                @if(auth()->user()->role !== 'client')
                                    <a href="{{ route('admin.dashboard') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-2 py-1.5 md:px-3 md:py-2 xl:px-6 xl:py-3 rounded-lg xl:rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-xs md:text-sm xl:text-base">
                                        Dashboard
                                    </a>
                                @endif
                                <div class="relative group">
                                    <button class="{{ $hasProformas && request()->routeIs('configurador') ? 'animate-wiggle' : '' }} flex items-center space-x-0.5 md:space-x-1 xl:space-x-2 text-white/90 hover:text-white transition-colors duration-300">
                                        <div class="w-6 h-6 md:w-7 md:h-7 xl:w-8 xl:h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center relative">
                                            <span class="text-xs font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium text-xs md:text-sm xl:text-base hidden xl:inline">{{ auth()->user()->name }}</span>
                                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 xl:w-4 xl:h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown menu -->
                                    <div class="absolute right-0 mt-1.5 md:mt-2 w-40 md:w-44 xl:w-48 bg-white dark:bg-gray-700 rounded-lg xl:rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                        <div class="py-1.5 md:py-2">
                                            <a href="javascript:void(0)" onclick="Livewire.dispatch('openProfileModal')" id="openProfileModalDesktop" class="block px-2.5 md:px-3 xl:px-4 py-1.5 md:py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 flex items-center gap-1.5 md:gap-2 text-xs md:text-sm xl:text-base">
                                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 xl:w-4 xl:h-4 inline mr-1.5 md:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Mi Perfil</span>
                                                @livewire('profile-status')
                                            </a>
                                            <a href="javascript:void(0)" onclick="Livewire.dispatch('openProformasModal')" id="openProformasModalDesktop" class="block px-2.5 md:px-3 xl:px-4 py-1.5 md:py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 flex items-center gap-1.5 md:gap-2 text-xs md:text-sm xl:text-base">
                                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5 xl:w-4 xl:h-4 inline mr-1.5 md:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Mis Proformas</span>
                                                @if($hasProformas)
                                                <span class="relative flex h-2 w-2 ml-auto">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                                </span>
                                                @endif
                                            </a>
                                            @auth
                                                @if(empty(auth()->user()->oauth_provider))
                                                    <a href="javascript:void(0)" onclick="Livewire.dispatch('openPasswordModal')" id="openPasswordModalDesktop" class="block px-2.5 md:px-3 xl:px-4 py-1.5 md:py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 flex items-center gap-1.5 md:gap-2 text-xs md:text-sm xl:text-base">
                                                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5 xl:w-4 xl:h-4 inline mr-1.5 md:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6zm2 2h4v2H8V6zm0 4h4v2H8v-2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span>Cambiar Contraseña</span>
                                                    </a>
                                                @endif
                                            @endauth
                                            <div class="border-t border-gray-200 dark:border-gray-600 my-0.5 md:my-1"></div>
                                            <form method="POST" action="{{ route('logout') }}" class="block" onsubmit="if(window.Alpine && Alpine.store('loginModal')) Alpine.store('loginModal').open = false;">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-2.5 md:px-3 xl:px-4 py-1.5 md:py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-300 text-xs md:text-sm xl:text-base">
                                                    <svg class="w-3 h-3 md:w-3.5 md:h-3.5 xl:w-4 xl:h-4 inline mr-1.5 md:mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Cerrar Sesión
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Usuario no logueado -->
                            <div class="flex items-center space-x-1.5 md:space-x-2 xl:space-x-4">
                                <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = true" class="relative group px-3 py-1.5 md:px-4 md:py-2 xl:px-5 xl:py-2.5 rounded-lg xl:rounded-xl border-2 border-white/30 hover:border-white/60 text-white font-semibold transition-all duration-300 text-xs md:text-sm xl:text-base backdrop-blur-sm bg-white/5 hover:bg-white/10 transform hover:scale-105 shadow-lg hover:shadow-white/20">
                                    <span class="relative z-10 flex items-center gap-1">
                                        <span class="hidden xl:inline">Iniciar Sesión</span>
                                        <span class="xl:hidden">Login</span>
                                    </span>
                                    <div class="absolute inset-0 rounded-lg xl:rounded-xl bg-gradient-to-r from-white/0 via-white/10 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                                <a href="javascript:void(0)" @click.prevent="$store.registerModal.open = true" class="relative bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-600 hover:via-teal-600 hover:to-cyan-600 text-white px-2 py-1.5 md:px-3 md:py-2 xl:px-6 xl:py-3 rounded-lg xl:rounded-xl font-bold transition-all duration-300 transform hover:scale-110 shadow-[0_0_20px_rgba(16,185,129,0.5)] hover:shadow-[0_0_30px_rgba(16,185,129,0.8)] text-xs md:text-sm xl:text-base border border-emerald-400/30 hover:border-emerald-300/50 animate-pulse hover:animate-none">
                                    <span class="relative z-10 flex items-center gap-1">
                                        Registrarse
                                    </span>
                                    <div class="absolute inset-0 rounded-lg xl:rounded-xl bg-gradient-to-r from-white/0 via-white/20 to-white/0 opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                            </div>
                        @endauth
                        <!-- Logo derecho eliminado de aquí -->
                <!-- Logo derecho fuera del nav, pero dentro del container, con posición absoluta -->
                <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Secundario" class="w-8 h-8 md:w-9 md:h-9 lg:w-10 lg:h-10 xl:w-14 xl:h-14 rounded-lg xl:rounded-xl drop-shadow-[0_5px_10px_rgba(0,255,255,0.9)] object-cover hidden lg:block absolute right-2 md:right-3 xl:right-6 top-1/2 -translate-y-1/2">
                    </div>
                    
                    <!-- Botón menú móvil -->
                    <button data-mobile-menu class="lg:hidden text-white focus:outline-none p-2">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Menú móvil -->
                <div data-mobile-menu-content class="hidden lg:hidden mt-3 sm:mt-4 py-3 sm:py-4 border-t border-white/20">
                    <div class="flex flex-col space-y-2 sm:space-y-3">
                        <a href="#inicio" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-sm sm:text-base py-1">Inicio</a>
                        <a href="#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-sm sm:text-base py-1">Nosotros</a>
                        <a href="#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-sm sm:text-base py-1">Servicios</a>
                        <a href="#galeria" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-sm sm:text-base py-1">Galería</a>
                        <a href="#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium text-sm sm:text-base py-1">Ubicación</a>
                        
                        @auth
                            <!-- Usuario logueado - Móvil -->
                            <div class="border-t border-white/20 pt-2 sm:pt-3 mt-2 sm:mt-3">
                                <div class="flex items-center space-x-2 sm:space-x-3 mb-2 sm:mb-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm sm:text-base">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium text-sm sm:text-base">{{ auth()->user()->name }}</p>
                                        <p class="text-white/70 text-xs sm:text-sm">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                @if(auth()->user()->role !== 'client')
                                    <a href="{{ route('admin.dashboard') }}" class="block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-semibold transition-all duration-300 text-center mb-2 sm:mb-3 text-sm sm:text-base">
                                        Dashboard
                                    </a>
                                @endif
                                <a href="javascript:void(0)" id="openProfileModalMobile" class="block text-white/90 hover:text-white transition-colors duration-300 font-medium py-1.5 sm:py-2 text-sm sm:text-base">
                                    Mi Perfil
                                </a>
                                <a href="javascript:void(0)" id="openProformasModalMobile" class="flex items-center gap-2 text-white/90 hover:text-white transition-colors duration-300 font-medium py-1.5 sm:py-2 text-sm sm:text-base">
                                    Mis Proformas
                                    @if($hasProformas)
                                    <span class="relative flex h-2 w-2 ml-1">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                                    </span>
                                    @endif
                                </a>
                                @auth
                                    @if(empty(auth()->user()->oauth_provider))
                                        <a href="javascript:void(0)" id="openPasswordModalMobile" class="block text-white/90 hover:text-white transition-colors duration-300 font-medium py-1.5 sm:py-2 text-sm sm:text-base">
                                            Cambiar Contraseña
                                        </a>
                                    @endif
                                @endauth
                                <form method="POST" action="{{ route('logout') }}" class="mt-2 sm:mt-3" onsubmit="if(window.Alpine && Alpine.store('loginModal')) Alpine.store('loginModal').open = false;">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 transition-colors duration-300 font-medium py-1.5 sm:py-2 text-sm sm:text-base">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Usuario no logueado - Móvil -->
                            <div class="border-t border-white/20 pt-2 sm:pt-3 mt-2 sm:mt-3 space-y-2 sm:space-y-3">
                                <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = true" class="relative group block px-4 py-2 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl border-2 border-white/30 hover:border-white/60 text-white font-semibold transition-all duration-300 text-center text-sm sm:text-base backdrop-blur-sm bg-white/5 hover:bg-white/10 transform hover:scale-105 shadow-lg hover:shadow-white/20">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 102 0V4a1 1 0 011-1h10a1 1 0 011 1v12a1 1 0 102 0V4a1 1 0 00-1-1H4a1 1 0 00-1 1z" clip-rule="evenodd"></path>
                                            <path d="M6 7a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1z"></path>
                                        </svg>
                                        Iniciar Sesión
                                    </span>
                                    <div class="absolute inset-0 rounded-lg sm:rounded-xl bg-gradient-to-r from-white/0 via-white/10 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                                <a href="javascript:void(0)" @click.prevent="$store.registerModal.open = true" class="relative block bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 hover:from-emerald-600 hover:via-teal-600 hover:to-cyan-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-bold transition-all duration-300 text-center text-sm sm:text-base shadow-[0_0_20px_rgba(16,185,129,0.5)] hover:shadow-[0_0_30px_rgba(16,185,129,0.8)] border border-emerald-400/30 hover:border-emerald-300/50 transform hover:scale-105">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                        </svg>
                                        Registrarse
                                    </span>
                                    <div class="absolute inset-0 rounded-lg sm:rounded-xl bg-gradient-to-r from-white/0 via-white/20 to-white/0 opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
    <main>
            @isset($slot)
                {{ $slot }} <!-- Para componentes Livewire que usan layouts -->
            @endisset

           @hasSection('content')
                @yield('content') <!-- Para vistas Blade que extienden este layout -->
            @endif
        </main>

        @fluxScripts
        @livewire('profile-modal')
        @livewire('password-modal')
        @auth
            @livewire('user-proformas-modal')
        @endauth
        @livewireScripts
        <!-- Modals globales para usuarios guest -->
        @auth
            <livewire:verification-modal />
        @endauth

        <!-- Notificaciones de autenticación -->
        @if(session('login_message') || session('show_login_modal') || session('show_register_modal'))
        <div x-data="{ 
                show: true,
                init() {
                    // Auto-ocultar después de 4 segundos
                    setTimeout(() => {
                        this.show = false;
                    }, 4000);
                }
            }" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="fixed top-16 sm:top-20 right-2 sm:right-4 z-40 max-w-[calc(100vw-1rem)] sm:max-w-sm">
            <div class="bg-red-600 text-white p-3 sm:p-4 rounded-lg shadow-lg border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium">
                            @if(session('login_message'))
                                {{ session('login_message') }}
                            @elseif(session('show_login_modal'))
                                Debes iniciar sesión para acceder a esta página
                            @elseif(session('show_register_modal'))
                                Crea una cuenta para comenzar
                            @endif
                        </span>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white ml-2 flex-shrink-0" title="Cerrar notificación">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif

        @guest
        <div x-data x-init="
            // Inicializar stores si no existen
            window.Alpine && Alpine.store('loginModal') === undefined ? Alpine.store('loginModal', { open: false }) : null;
            window.Alpine && Alpine.store('registerModal') === undefined ? Alpine.store('registerModal', { open: false }) : null;
            
            // Auto-abrir modales si es necesario
            @if(session('show_login_modal'))
                if (window.Alpine && Alpine.store('loginModal')) {
                    Alpine.store('loginModal').open = true;
                }
            @endif
            
            @if(session('show_register_modal'))
                if (window.Alpine && Alpine.store('registerModal')) {
                    Alpine.store('registerModal').open = true;
                }
            @endif
        ">
            <div x-show="$store.loginModal.open" x-transition x-cloak class="fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px] transition-opacity"></div>
                <div class="flex items-center justify-center min-h-screen">
                    <div class="w-full max-w-md relative overflow-visible">
                        <button @click="$store.loginModal.open = false" class="absolute top-3 right-3 z-10 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <livewire:auth.login />
                    </div>
                </div>
            </div>
        </div>
        <div x-data x-init="window.Alpine && Alpine.store('registerModal') === undefined ? Alpine.store('registerModal', { open: false }) : null">
            <div x-show="$store.registerModal.open" x-transition x-cloak class="fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px] transition-opacity"></div>
                <div class="flex items-center justify-center min-h-screen">
                    <div class="w-full max-w-2xl relative overflow-visible">
                        <button @click="$store.registerModal.open = false" class="absolute top-3 right-3 z-10 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <livewire:auth.register />
                    </div>
                </div>
            </div>
        </div>
        <div x-data x-init="window.Alpine && Alpine.store('forgotPasswordModal') === undefined ? Alpine.store('forgotPasswordModal', { open: false }) : null">
            <div x-show="$store.forgotPasswordModal.open" x-transition x-cloak class="fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px] transition-opacity"></div>
                <div class="flex items-center justify-center min-h-screen">
                    <div class="w-full max-w-md relative overflow-visible">
                        <button @click="$store.forgotPasswordModal.open = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10 cursor-pointer">&times;</button>
                        <livewire:forgot-password-modal />
                    </div>
                </div>
            </div>
        </div>
        @endguest
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            var mobileMenuBtn = document.querySelector('[data-mobile-menu]');
            var mobileMenu = document.querySelector('[data-mobile-menu-content]');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Cerrar menú al hacer clic en un enlace
                var mobileLinks = mobileMenu.querySelectorAll('a');
                mobileLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
            
            // Smooth scroll/redirect for anchor links
            document.querySelectorAll('a.nav-link[href^="'+window.location.origin+'/#"], a.nav-link[href^="'+window.location.origin+'#"], a.nav-link[href^="#"]').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    var href = link.getAttribute('href');
                    var hash = href.includes('#') ? href.substring(href.indexOf('#')) : '';
                    if (!hash || hash === '#') return;
                    // Si ya estamos en la home
                    if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
                        var target = document.querySelector(hash);
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth' });
                            history.replaceState(null, '', hash);
                        }
                    } else {
                        // Si no, redirigir a la home con el hash
                        window.location.href = '/'+hash;
                    }
                });
            });
            
            // Livewire modals - Compatible con v2 y v3
            function emitProfileModal() {
                if(window.Livewire) {
                    if(typeof window.Livewire.dispatch === 'function') {
                        window.Livewire.dispatch('openProfileModal');
                    } else if(typeof window.Livewire.emit === 'function') {
                        window.Livewire.emit('openProfileModal');
                    }
                }
            }
            
            function emitProformasModal() {
                if(window.Livewire) {
                    if(typeof window.Livewire.dispatch === 'function') {
                        window.Livewire.dispatch('openProformasModal');
                    } else if(typeof window.Livewire.emit === 'function') {
                        window.Livewire.emit('openProformasModal');
                    }
                }
            }
            
            function emitPasswordModal() {
                if(window.Livewire) {
                    if(typeof window.Livewire.dispatch === 'function') {
                        window.Livewire.dispatch('openPasswordModal');
                    } else if(typeof window.Livewire.emit === 'function') {
                        window.Livewire.emit('openPasswordModal');
                    }
                }
            }
            
            // Profile Modal buttons
            var desktopBtn = document.getElementById('openProfileModalDesktop');
            var mobileBtn = document.getElementById('openProfileModalMobile');
            if(desktopBtn) desktopBtn.addEventListener('click', function(e){ e.preventDefault(); emitProfileModal(); });
            if(mobileBtn) mobileBtn.addEventListener('click', function(e){ e.preventDefault(); emitProfileModal(); });
            
            // Proformas Modal buttons
            var desktopProformasBtn = document.getElementById('openProformasModalDesktop');
            var mobileProformasBtn = document.getElementById('openProformasModalMobile');
            if(desktopProformasBtn) desktopProformasBtn.addEventListener('click', function(e){ e.preventDefault(); emitProformasModal(); });
            if(mobileProformasBtn) mobileProformasBtn.addEventListener('click', function(e){ e.preventDefault(); emitProformasModal(); });
            
            // Password Modal buttons
            var desktopPasswordBtn = document.getElementById('openPasswordModalDesktop');
            var mobilePasswordBtn = document.getElementById('openPasswordModalMobile');
            if(desktopPasswordBtn) desktopPasswordBtn.addEventListener('click', function(e){ e.preventDefault(); emitPasswordModal(); });
            if(mobilePasswordBtn) mobilePasswordBtn.addEventListener('click', function(e){ e.preventDefault(); emitPasswordModal(); });
        });

                    // Aplicar tema ANTES de que se cargue la página para evitar flash
            (function() {
                const darkMode = localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && true);
                if (darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
    </body>
</html>