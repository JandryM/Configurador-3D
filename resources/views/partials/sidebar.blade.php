<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title ?? 'Panel de Administración') - Quality</title>
    <style>
        /* Gradientes personalizados Quality */
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            transition: all 0.3s ease-in-out;
        }
        
        .sidebar-collapsed {
            width: 100px; /* Cambiado de 70px a 100px */
        }
        
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease-in-out;
        }
        
        .main-content-expanded {
            margin-left: 100px; /* Cambiado de 70px a 100px */
        }
        
        /* Ajustar el contenido del sidebar contraído */
        .sidebar-collapsed .nav-item {
            justify-content: center; /* Centrar los iconos */
        }
        
        .sidebar-collapsed .menu-item-icon {
            font-size: 24px;
            margin: auto;
        }
        
        /* Ocultar texto en el estado contraído */
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        
        /* Mostrar solo iconos en el estado contraído */
        .sidebar-collapsed .sidebar-collapsed-text {
            display: block;
        }
        
        /* Efectos glassmorphism premium */
        .glass-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        .glass-sidebar {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Sidebar Navigation */
        .nav-item {
            transition: all 0.2s ease-in-out;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        
        .nav-item.active {
            background: rgba(59, 130, 246, 0.2);
            border-right: 3px solid #3b82f6;
        }
        
        /* Badge de notificación */
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        .animate-pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen gradient-bg antialiased">
    <!-- Overlay para móvil -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
    
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed left-0 top-0 h-full glass-sidebar z-50">
        <!-- Logo y Header del Sidebar -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="w-full h-full object-contain">
                </div>
                <div id="sidebar-text" class="sidebar-text">
                    <h2 class="text-white font-bold text-lg">Quality</h2>
                    <p class="text-slate-300 text-xs">Panel Admin</p>
                </div>
            </div>
            <button id="sidebar-toggle" class="text-white hover:text-blue-400 transition-colors lg:block hidden">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
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

        <nav class="mt-6 px-3 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                </svg>
                <span class="sidebar-text">Dashboard</span>
            </a>

            @if($userRole === 'admin' || $userRole === 'owner' || $userRole === 'seller')
                <!-- Usuarios -->
                <a href="{{ route('admin.users.index') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="sidebar-text">Usuarios</span>
                </a>

                <!-- Productos -->
                <a href="{{ route('admin.products.index') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text">Productos</span>
                </a>

                <!-- Materiales -->
                <a href="{{ route('admin.materials.index') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text">Materiales</span>
                </a>

                <!-- Costos -->
                <a href="{{ route('admin.cost-settings') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.cost-settings') || request()->routeIs('admin.product-cost-settings') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text">Costos</span>
                </a>
            @endif

            <!-- Proformas -->
            <a href="{{ route('admin.proformas.index') }}" class="nav-item flex items-center px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.proformas.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h6a2 2 0 002-2V3a2 2 0 012 2v6.5a1.5 1.5 0 01-1.5 1.5h-6A1.5 1.5 0 019 11.5V4H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a1 1 0 10-2 0v1H7V4a1 1 0 10-2 0v1z" clip-rule="evenodd"></path>
                </svg>
                <span class="sidebar-text">Proformas</span>
            </a>

            <!-- Órdenes -->
            <a href="{{ route('admin.orders.index') }}" class="nav-item flex items-center justify-between px-3 py-3 rounded-lg text-white {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} relative">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sidebar-text">Órdenes</span>
                </div>
                @if($pendingOrdersCount > 0)
                    <!-- Badge cuando sidebar está expandido -->
                    <span class="sidebar-text bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg animate-pulse-ring">
                        {{ $pendingOrdersCount > 9 ? '9+' : $pendingOrdersCount }}
                    </span>
                    <!-- Badge cuando sidebar está colapsado -->
                    <span class="sidebar-collapsed-text hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg animate-pulse-ring">
                        {{ $pendingOrdersCount > 9 ? '9+' : $pendingOrdersCount }}
                    </span>
                @endif
            </a>
        </nav>
        
        <!-- Información del usuario y logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-8 h-8 bg-gradient-to-r from-slate-600 to-slate-700 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                    <span class="text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="sidebar-text min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-300 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <!-- Botones de acción -->
            <div class="flex space-x-2">
                <a href="{{ route('home') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs py-2 px-3 rounded-lg transition-colors text-center">
                    <span class="sidebar-text">Ver Sitio</span>
                    <span class="sidebar-collapsed-text hidden">🏠</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs py-2 px-3 rounded-lg transition-colors">
                        <span class="sidebar-text">Logout</span>
                        <span class="sidebar-collapsed-text hidden">🚪</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
    
    <!-- Contenido Principal -->
    <main id="main-content" class="main-content min-h-screen">
        <!-- Header con botón de menú móvil -->
        <header class="glass-card border-b border-slate-200/50 px-6 py-4 lg:hidden">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', $title ?? 'Dashboard')</h1>
                <button id="mobile-menu-toggle" class="text-slate-600 hover:text-slate-800 transition-colors">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </header>
        
        <!-- Contenido de la página -->
        <div class="p-6">
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
    @endauth

    @livewireScripts
    
    <!-- Scripts del Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');
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