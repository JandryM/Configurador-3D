<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Panel de Administración') - Quality</title>
    <style>
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .animate-pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Main content positioning */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 280px;
            }
            .main-content-expanded {
                margin-left: 96px; /* Update margin to match 96px */
            }
        }
        
        /* Sidebar collapsed state */
        @media (min-width: 1024px) {
            .sidebar-collapsed {
                width: 96px; /* Widen to 96px for Uleam Logo */
            }
        }

        /* Custom scrollbar for sidebar */
        #sidebar nav {
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
        #sidebar nav::-webkit-scrollbar {
            width: 5px;
            background: transparent;
        }
        #sidebar nav::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }
        #sidebar nav::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
        #sidebar nav::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 antialiased">
    <!-- Overlay para móvil -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 h-full w-[280px] lg:w-[280px] bg-custom-blue border-r border-slate-800 z-50 transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl">
        <!-- Logo y Header del Sidebar -->
        <div id="sidebar-header" class="flex items-center justify-between p-4 sm:p-6 border-b border-slate-800 transition-all duration-300">
            <div class="flex items-center space-x-0 w-full overflow-hidden transition-all duration-300" id="sidebar-header-content">
                
                <!-- Contenedor Logos Expandido: Quality (Izq) + Texto + Uleam (Der) -->
                <div class="logo-expanded flex items-center justify-between w-full min-w-0 transition-opacity duration-300">
                    <!-- Quality Logo -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0 sidebar-logo bg-slate-800 border border-slate-700">
                        <img src="{{ asset('images/logo.png') }}" alt="Quality" class="w-full h-full object-contain">
                    </div>
                    
                    <!-- Texto Central -->
                    <div class="sidebar-text min-w-0 text-center px-1">
                        <h2 class="text-white font-bold text-base sm:text-lg truncate">Quality</h2>
                        <p class="text-slate-400 text-xs">Panel Admin</p>
                    </div>

                    <!-- Uleam Logo -->
                     <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0 sidebar-logo bg-white border border-slate-200 p-0.5">
                        <img src="{{ asset('images/logo2.jpg') }}" alt="Uleam" class="w-full h-full object-contain">
                    </div>
                </div>

                <!-- Contenedor Logos Colapsado: Ambos logos verticalmente o ajustados -->
                <div id="collapsed-logos" class="logo-collapsed hidden flex-col items-center justify-center gap-2 w-full mx-auto group">
                    <!-- Quality Small -->
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-lg bg-slate-800 border border-slate-700 p-0.5">
                         <img src="{{ asset('images/logo.png') }}" alt="Q" class="w-full h-full object-contain">
                    </div>
                    <!-- Uleam Small -->
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-lg bg-white border border-slate-200 p-0.5">
                        <img src="{{ asset('images/logo2.jpg') }}" alt="U" class="w-full h-full object-contain group-hover:scale-110 transition-transform">
                    </div>
                </div>
            </div>
            
            <!-- Botón Hamburguesa (Siempre visible en desktop) -->
            <button id="sidebar-toggle" class="sidebar-toggle-btn text-slate-400 hover:text-white transition-colors lg:block hidden flex-shrink-0 ml-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Navegación -->
        @php
            $userRole = auth()->user()->role;
            // Contar órdenes pendientes de aprobación
            $pendingOrdersCount = DB::table('orders')->where('status', 'pending')->count();
        @endphp

        <nav class="mt-4 sm:mt-6 px-2 sm:px-3 space-y-1 sm:space-y-2 overflow-y-auto max-h-[calc(100vh-220px)] scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
            <!-- Dashboard -->
                <!-- Padding inferior para que el bloque de usuario/botones no se tape -->
                <style>
                    /* Estilos para estado colapsado */
                    .sidebar-collapsed .logo-expanded { display: none !important; }
                    .sidebar-collapsed .logo-collapsed { display: flex !important; }
                    
                    /* Ajustar Header cuando colapsado para que sea Columna */
                    .sidebar-collapsed #sidebar-header {
                        flex-direction: column;
                        gap: 1rem;
                        padding-top: 1.5rem;
                        padding-bottom: 1.5rem;
                    }
                    /* Ajustar contenedor de logo para que ocupe todo ancho */
                    .sidebar-collapsed #sidebar-header-content {
                        width: 100%;
                    }
                    /* Ajustar botón toggle para que esté centrado abajo */
                    .sidebar-collapsed .sidebar-toggle-btn {
                        margin-left: 0;
                        margin-top: 0.5rem;
                    }
                    
                    /* Ajustes de navegación en colapsado */
                    .sidebar-collapsed nav a { justify-content: center; }
                    .sidebar-collapsed nav a span.sidebar-text { display: none; }
                    
                    /* Ajustes del Footer en colapsado para evitar desbordamiento */
                    .sidebar-collapsed .footer-actions { 
                        flex-direction: column; 
                        space-y: 0.5rem; /* space-y-2 */
                        space-x: 0 !important;
                    }
                    .sidebar-collapsed .footer-actions > * {
                        width: 100%;
                        margin-left: 0 !important;
                        margin-top: 0.5rem;
                    }
                    .sidebar-collapsed .footer-actions > *:first-child {
                        margin-top: 0;
                    }
                    .sidebar-collapsed .user-profile {
                        justify-content: center;
                    }

                    #sidebar nav { padding-bottom: 110px; }
                    @media (min-width: 640px) { #sidebar nav { padding-bottom: 130px; } }
                </style>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white border-l-2 border-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 11h1v6a1 1 0 001 1h4a1 1 0 001-1v-4h2v4a1 1 0 001 1h4a1 1 0 001-1v-6h1a1 1 0 00.707-1.707l-7-7z" />
                </svg>
                <span class="sidebar-text text-sm sm:text-base font-medium">Dashboard</span>
            </a>

            @if($userRole === 'admin' || $userRole === 'owner')
                <!-- Usuarios -->
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-sky-500/10 text-sky-400 border-l-2 border-sky-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-sky-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="sidebar-text text-sm sm:text-base font-medium">Usuarios</span>
                </a>

                <!-- Productos -->
                <a href="{{ route('admin.products.index') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.products.*') ? 'bg-amber-500/10 text-amber-400 border-l-2 border-amber-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.products.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text text-sm sm:text-base font-medium">Productos</span>
                </a>

                <!-- Inventario -->
                <a href="{{ route('admin.inventory.index') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.inventory.*') ? 'bg-teal-500/10 text-teal-400 border-l-2 border-teal-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.inventory.*') ? 'text-teal-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="sidebar-text text-sm sm:text-base font-medium">Inventario</span>
                </a>

                <!-- Costos -->
                <a href="{{ route('admin.cost-settings') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.cost-settings') || request()->routeIs('admin.product-cost-settings') ? 'bg-red-500/10 text-red-400 border-l-2 border-red-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.cost-settings') || request()->routeIs('admin.product-cost-settings') ? 'text-red-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text text-sm sm:text-base font-medium">Costos</span>
                </a>
            @endif

            <!-- Proformas -->
            <a href="{{ route('admin.proformas.index') }}" class="flex items-center px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.proformas.*') ? 'bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.proformas.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h6a2 2 0 002-2V3a2 2 0 012 2v6.5a1.5 1.5 0 01-1.5 1.5h-6A1.5 1.5 0 019 11.5V4H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a1 1 0 10-2 0v1H7V4a1 1 0 10-2 0v1z" clip-rule="evenodd"></path>
                </svg>
                <span class="sidebar-text text-sm sm:text-base font-medium">Proformas</span>
            </a>

            <!-- Órdenes -->
            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-2 sm:px-3 py-2.5 sm:py-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.orders.*') ? 'bg-orange-500/10 text-orange-400 border-l-2 border-orange-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} relative">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3 flex-shrink-0 transition-colors {{ request()->routeIs('admin.orders.*') ? 'text-orange-400' : 'text-slate-500 group-hover:text-white' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text text-sm sm:text-base font-medium">Órdenes</span>
                </div>
                @if($pendingOrdersCount > 0)
                    <!-- Badge cuando sidebar está expandido -->
                    <span class="sidebar-text bg-red-500 text-white text-[0.65rem] sm:text-xs font-bold rounded-full w-5 h-5 sm:w-6 sm:h-6 flex items-center justify-center shadow-lg animate-pulse-ring">
                        {{ $pendingOrdersCount > 9 ? '9+' : $pendingOrdersCount }}
                    </span>
                    <!-- Badge cuando sidebar está colapsado -->
                    <span class="sidebar-collapsed-text hidden absolute -top-1 -right-1 bg-red-500 text-white text-[0.6rem] sm:text-xs font-bold rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center shadow-lg animate-pulse-ring">
                        {{ $pendingOrdersCount > 9 ? '9+' : $pendingOrdersCount }}
                    </span>
                @endif
            </a>
        </nav>
        
        <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 pb-5 sm:pb-7 border-t border-slate-800 bg-slate-900/50 backdrop-blur-sm transition-all duration-300">
            <div class="user-profile flex items-center justify-center sm:justify-start space-x-0 sm:space-x-3 mb-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 
                    @if(auth()->user()->role === 'admin')
                        bg-gradient-to-br from-orange-500 to-red-600 border-orange-400
                    @elseif(auth()->user()->role === 'owner')
                        bg-gradient-to-br from-purple-500 to-indigo-600 border-purple-400
                    @else
                        bg-gradient-to-br from-blue-500 to-cyan-600 border-blue-400
                    @endif
                    border rounded-lg flex items-center justify-center shadow-lg flex-shrink-0 group relative cursor-help">
                    <span class="text-xs sm:text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                    <!-- Tooltip nombre usuario cuando colapsado -->
                   <div class="absolute left-full ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap lg:hidden pointer-events-none transition-opacity z-50 shadow-xl border border-slate-700">
                        {{ auth()->user()->name }}
                   </div>
                </div>
                <div class="sidebar-text min-w-0">
                    <p class="text-white text-xs sm:text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-[0.65rem] sm:text-xs truncate">{{ auth()->user()->email }}</p>
                    <div class="mt-1">
                        <span class="
                            @if(auth()->user()->role === 'admin')
                                bg-orange-500/20 text-orange-300 border-orange-500/30
                            @elseif(auth()->user()->role === 'owner')
                                bg-purple-500/20 text-purple-300 border-purple-500/30
                            @else
                                bg-blue-500/20 text-blue-300 border-blue-500/30
                            @endif
                            text-[0.6rem] sm:text-[0.65rem] font-semibold border px-2 py-0.5 rounded inline-block">
                            @if(auth()->user()->role === 'admin')
                                Administrador
                            @elseif(auth()->user()->role === 'owner')
                                Propietario
                            @else
                                Cliente
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="footer-actions flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <a href="{{ route('home') }}" class="flex-1 bg-blue-600/10 hover:bg-blue-600 border border-blue-600/50 hover:border-blue-500 text-blue-400 hover:text-white text-[0.65rem] sm:text-xs py-2 px-2 rounded-lg transition-all text-center flex items-center justify-center group cursor-pointer" title="Ver Sitio">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span class="sidebar-text ml-1.5">Sitio</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1 w-full m-0">
                    @csrf
                    <button type="submit" class="w-full bg-red-600/10 hover:bg-red-600 border border-red-600/50 hover:border-red-500 text-red-400 hover:text-white text-[0.65rem] sm:text-xs py-2 px-2 rounded-lg transition-all flex items-center justify-center group cursor-pointer" title="Cerrar Sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="sidebar-text ml-1.5">Salir</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Contenido Principal -->
    <main id="main-content" class="main-content min-h-screen">
        <!-- Header con botón de menú móvil -->
        <header class="glass-card border-b border-slate-200/50 px-4 sm:px-6 py-3 sm:py-4 lg:hidden">
            <div class="flex items-center justify-between">
                <h1 class="text-base sm:text-lg font-semibold text-slate-800">@yield('page-title', $title ?? 'Dashboard')</h1>
                <button id="mobile-menu-toggle" class="text-slate-600 hover:text-slate-800 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </header>
        
        <!-- Contenido de la página -->
        <div class="p-4 sm:p-6">
            {{-- Si estamos en un componente Blade --}}
            @isset($slot)
                {{ $slot }}
            @endisset

            {{-- Si estamos en una vista normal --}}
            @hasSection('content')
                @yield('content')
            @endif
        </div>
    </main>
    
    <!-- Modales (si son necesarios) -->
    @auth
        <livewire:profile-modal />
        @if(empty(auth()->user()->oauth_provider))
            <livewire:password-modal />
        @endif
        <livewire:verification-modal />
        @if(auth()->user()->role === 'owner')
            <livewire:owner.bank-account-form />
        @endif
    @endauth

    @livewireScripts
    
    <!-- Scripts del Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            // const collapsedLogoToggle = document.getElementById('collapsed-logo-toggle'); // Removed as requested
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const sidebarCollapsedTexts = document.querySelectorAll('.sidebar-collapsed-text');
            
            let isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            
            // Aplicar estado inicial
            if (isCollapsed && window.innerWidth >= 1024) {
                collapseSidebar();
            }
            
            // Toggle sidebar desktop
            sidebarToggle?.addEventListener('click', function() {
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });

            // Toggle desde el logo colapsado (ELIMINADO POR SOLICITUD)
            /*
            collapsedLogoToggle?.addEventListener('click', function() {
                expandSidebar();
            });
            */
            
            // Toggle sidebar móvil
            mobileMenuToggle?.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('hidden');
            });
            
            // Cerrar sidebar móvil al hacer clic en overlay
            sidebarOverlay?.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.add('hidden');
            });
            
            function collapseSidebar() {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.add('main-content-expanded');
                sidebarTexts.forEach(text => text.classList.add('hidden'));
                sidebarCollapsedTexts.forEach(text => text.classList.remove('hidden'));
                localStorage.setItem('sidebar-collapsed', 'true');
            }
            
            function expandSidebar() {
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.classList.remove('main-content-expanded');
                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                sidebarCollapsedTexts.forEach(text => text.classList.add('hidden'));
                localStorage.setItem('sidebar-collapsed', 'false');
            }
            
            // Responsive handling
            window.addEventListener('resize', function() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.remove('sidebar-collapsed');
                    mainContent.classList.remove('main-content-expanded');
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                    sidebarCollapsedTexts.forEach(text => text.classList.add('hidden'));
                } else {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.add('hidden');
                    if (isCollapsed) {
                        collapseSidebar();
                    }
                }
            });
        });
        
        // Funciones para modales (compatibilidad)
        function openProfileModal() {
            Livewire.dispatch('openProfileModal');
        }
        
        function openPasswordModal() {
            Livewire.dispatch('openPasswordModal');
        }
    </script>
    
    @stack('scripts')
</body>
</html>