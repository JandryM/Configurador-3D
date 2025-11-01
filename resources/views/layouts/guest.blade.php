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
            <div class="container mx-auto px-6 py-4 relative">
                <div class="flex items-center justify-between w-full">
                    <!-- Logo izquierdo -->
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="w-14 h-14 rounded-xl drop-shadow-[0_5px_10px_rgba(0,255,255,0.9)] object-cover">
                        <span class="text-2xl font-bold text-white">Quality</span>
                    </div>
                    <!-- Navegación Desktop -->
                    <div class="hidden md:flex items-center space-x-8 flex-1 justify-center">
                        <a href="{{ url('/')}}#inicio" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Inicio</a>
                        <a href="{{ url('/')}}#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Nosotros</a>
                        <a href="{{ url('/')}}#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Servicios</a>
                        <a href="{{ url('/')}}#galeria" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Galería</a>
                        <a href="#" target="_blank" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium bg-gradient-to-r from-green-500 to-blue-500 px-4 py-2 rounded-lg font-semibold ml-2">Proforma</a>
                        <a href="{{ url('/')}}#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Ubicación</a>
                        
                        @auth
                            <!-- Usuario logueado -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Dashboard
                                </a>
                                <div class="relative group">
                                    <button class="flex items-center space-x-2 text-white/90 hover:text-white transition-colors duration-300">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center relative">
                                            <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                        </div>
                                        <span class="font-medium">{{ auth()->user()->name }}</span>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown menu -->
                                    <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                        <div class="py-2">
                                            <a href="javascript:void(0)" onclick="Livewire.dispatch('openProfileModal')" id="openProfileModalDesktop" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 flex items-center gap-2">
                                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Mi Perfil</span>
                                                @livewire('profile-status')
                                            </a>
                                            @auth
                                                @if(empty(auth()->user()->oauth_provider))
                                                    <a href="javascript:void(0)" onclick="Livewire.dispatch('openPasswordModal')" id="openPasswordModalDesktop" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300 flex items-center gap-2">
                                                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6zm2 2h4v2H8V6zm0 4h4v2H8v-2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span>Cambiar Contraseña</span>
                                                    </a>
                                                @endif
                                            @endauth
                                            <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
                                            <form method="POST" action="{{ route('logout') }}" class="block" onsubmit="if(window.Alpine && Alpine.store('loginModal')) Alpine.store('loginModal').open = false;">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-300">
                                                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
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
                            <div class="flex items-center space-x-4">
                                <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = true" class="text-white/90 hover:text-white font-medium transition-colors duration-300">
                                    Iniciar Sesión
                                </a>
                                <a href="javascript:void(0)" @click.prevent="$store.registerModal.open = true" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Registrarse
                                </a>
                            </div>
                        @endauth
                        <!-- Logo derecho eliminado de aquí -->
                <!-- Logo derecho fuera del nav, pero dentro del container, con posición absoluta -->
                <img src="{{ asset('images/logo2.jpg') }}" alt="Logo Secundario" class="w-14 h-14 rounded-xl drop-shadow-[0_5px_10px_rgba(0,255,255,0.9)] object-cover hidden md:block absolute right-6 top-1/2 -translate-y-1/2">
                    </div>
                    
                    <!-- Botón menú móvil -->
                    <button data-mobile-menu class="md:hidden text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Menú móvil -->
                <div data-mobile-menu-content class="hidden md:hidden mt-4 py-4 border-t border-white/20">
                    <div class="flex flex-col space-y-3">
                        <a href="#inicio" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Inicio</a>
                        <a href="#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Nosotros</a>
                        <a href="#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Servicios</a>
                        <a href="#galeria" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Galería</a>
                        <a href="#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Ubicación</a>
                        
                        @auth
                            <!-- Usuario logueado - Móvil -->
                            <div class="border-t border-white/20 pt-3 mt-3">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                                        <p class="text-white/70 text-sm">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 text-center mb-3">
                                    Dashboard
                                </a>
                                <a href="javascript:void(0)" id="openProfileModalMobile" class="block text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                                    Mi Perfil
                                </a>
                                @auth
                                    @if(empty(auth()->user()->oauth_provider))
                                        <a href="javascript:void(0)" id="openPasswordModalMobile" class="block text-white/90 hover:text-white transition-colors duration-300 font-medium py-2">
                                            Cambiar Contraseña
                                        </a>
                                    @endif
                                @endauth
                                <form method="POST" action="{{ route('logout') }}" class="mt-3" onsubmit="if(window.Alpine && Alpine.store('loginModal')) Alpine.store('loginModal').open = false;">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-red-400 hover:text-red-300 transition-colors duration-300 font-medium py-2">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Usuario no logueado - Móvil -->
                            <div class="border-t border-white/20 pt-3 mt-3 space-y-3">
                                <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = true" class="block text-white/90 hover:text-white font-medium transition-colors duration-300">
                                    Iniciar Sesión
                                </a>
                                <a href="javascript:void(0)" @click.prevent="$store.registerModal.open = true" class="block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 text-center">
                                    Registrarse
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
            class="fixed top-20 right-4 z-40 max-w-sm">
            <div class="bg-red-600 text-white p-4 rounded-lg shadow-lg border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm font-medium">
                            @if(session('login_message'))
                                {{ session('login_message') }}
                            @elseif(session('show_login_modal'))
                                Debes iniciar sesión para acceder a esta página
                            @elseif(session('show_register_modal'))
                                Crea una cuenta para comenzar
                            @endif
                        </span>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white ml-2" title="Cerrar notificación">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
                        <button @click="$store.loginModal.open = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-3xl font-bold z-10">&times;</button>
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
                        <button @click="$store.registerModal.open = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-3xl font-bold z-10">&times;</button>
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
                        <button @click="$store.forgotPasswordModal.open = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-3xl font-bold z-10">&times;</button>
                        <livewire:forgot-password-modal />
                    </div>
                </div>
            </div>
        </div>
        @endguest
        <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            // Livewire modals
            function emitProfileModal() {
                if(window.Livewire && typeof window.Livewire.emit === 'function') {
                    window.Livewire.emit('openProfileModal');
                } else if(window.livewire && typeof window.livewire.emit === 'function') {
                    window.livewire.emit('openProfileModal');
                }
            }
            var desktopBtn = document.getElementById('openProfileModalDesktop');
            var mobileBtn = document.getElementById('openProfileModalMobile');
            if(desktopBtn) desktopBtn.addEventListener('click', function(e){ e.preventDefault(); emitProfileModal(); });
            if(mobileBtn) mobileBtn.addEventListener('click', function(e){ e.preventDefault(); emitProfileModal(); });
        });
        </script>
    </body>
</html>