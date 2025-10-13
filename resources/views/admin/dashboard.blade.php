<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    <head>
        @include('partials.head')
        <title>Panel de Administración - Quality</title>
        <style>
            /* Gradientes personalizados */
            .gradient-bg {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            }
            
            .admin-gradient {
                background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            }
            
            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.15), 0 15px 15px -5px rgba(0, 0, 0, 0.08);
            }
            
            /* Animaciones sutiles */
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
            
            /* Efectos de glassmorphism premium */
            .glass-card {
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(226, 232, 240, 0.5);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            }
            
            /* Forzar colores para legibilidad */
            .glass-card .text-slate-800 {
                color: rgb(30 41 59) !important;
            }
            
            .glass-card .text-slate-600 {
                color: rgb(71 85 105) !important;
            }
            
            .glass-dark {
                background: rgba(30, 41, 59, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        </style>
        @livewireStyles
    </head>
    <body class="min-h-screen gradient-bg antialiased">
        <!-- Header -->
        <x-header />
        
        <!-- Contenido Principal -->
        <main class="pt-24 pb-12">
            <div class="container mx-auto px-6 max-w-7xl">
                @php
                    $totalUsers = \App\Models\User::count();
                    $totalProducts = \App\Models\Product::count();
                    $totalMaterials = \App\Models\Material::count();
                    $recentUsers = \App\Models\User::latest()->take(5)->get();
                @endphp
                
                <!-- Encabezado de bienvenida Admin -->
                <div class="fade-in mb-8">
                    <div class="glass-card rounded-2xl shadow-2xl p-8">
                        <div class="flex items-center justify-between flex-wrap gap-6">
                            <div>
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg mr-4">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-3xl font-bold mb-2" style="color: #1e293b !important;">
                                            Panel de Administración
                                        </h1>
                                        <p class="text-lg" style="color: #475569 !important;">
                                            Gestión completa del sistema Quality
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center" style="color: #475569 !important;">
                                    <svg class="w-4 h-4 mr-2" style="color: #475569 !important;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Bienvenido, {{ auth()->user()->name }}
                                </div>
                            </div>
                            
                            <!-- Estado del sistema -->
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold" style="color: #1e293b !important;">{{ $totalUsers + $totalProducts + $totalMaterials }}</div>
                                    <div class="text-xs" style="color: #64748b !important;">Elementos Totales</div>
                                </div>
                                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse shadow-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas principales -->
                <div class="fade-in grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" style="animation-delay: 0.1s;">
                    <!-- Usuarios -->
                    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg mb-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold" style="color: #1e293b !important;">{{ $totalUsers }}</p>
                                <p class="text-sm" style="color: #475569 !important;">Usuarios Registrados</p>
                            </div>
                            <div class="opacity-0 hover:opacity-100 transition-opacity duration-200">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM9 16a7 7 0 100-14 7 7 0 000 14z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg mb-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4zm12 0a2 2 0 012 2v1.101a7.002 7.002 0 00-1.586 2.433A4.993 4.993 0 0014 8a4.993 4.993 0 00-2.414.564A7.002 7.002 0 009.586 6.1V5a2 2 0 012-2h4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold" style="color: #1e293b !important;">{{ $totalProducts }}</p>
                                <p class="text-sm" style="color: #475569 !important;">Productos Disponibles</p>
                            </div>
                            <div class="opacity-0 hover:opacity-100 transition-opacity duration-200">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Materiales -->
                    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg mb-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15.586 13H17a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold" style="color: #1e293b !important;">{{ $totalMaterials }}</p>
                                <p class="text-sm" style="color: #475569 !important;">Materiales en Stock</p>
                            </div>
                            <div class="opacity-0 hover:opacity-100 transition-opacity duration-200">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Proformas (Futuro) -->
                    <div class="glass-card rounded-xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg mb-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h6a2 2 0 002-2V3a2 2 0 012 2v6.5a1.5 1.5 0 01-1.5 1.5h-6A1.5 1.5 0 019 11.5V4H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a1 1 0 10-2 0v1H7V4a1 1 0 10-2 0v1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold" style="color: #1e293b !important;">0</p>
                                <p class="text-sm" style="color: #475569 !important;">Proformas Generadas</p>
                            </div>
                            <div class="opacity-50">
                                <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accesos rápidos de gestión -->
                <div class="fade-in grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8" style="animation-delay: 0.2s;">
                    <!-- Gestión de Contenido -->
                    <div class="glass-card rounded-2xl shadow-xl">
                        <div class="p-6 border-b border-slate-200/50">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold" style="color: #1e293b !important;">Gestión de Contenido</h3>
                                    <p class="text-sm" style="color: #475569 !important;">Productos y materiales del catálogo</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50/50 border border-slate-200/50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v1.101a7.002 7.002 0 011.586 2.433A4.993 4.993 0 016 8a4.993 4.993 0 012.414.564A7.002 7.002 0 0110.414 6.1V5a2 2 0 00-2-2H4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium" style="color: #1e293b !important;">Gestión de Productos</p>
                                        <p class="text-xs" style="color: #475569 !important;">{{ $totalProducts }} productos disponibles</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-md">
                                    Próximamente
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50/50 border border-slate-200/50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium" style="color: #1e293b !important;">Gestión de Materiales</p>
                                        <p class="text-xs" style="color: #475569 !important;">{{ $totalMaterials }} materiales en stock</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-md">
                                    Próximamente
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios Recientes -->
                    <div class="glass-card rounded-2xl shadow-xl">
                        <div class="p-6 border-b border-slate-200/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold" style="color: #1e293b !important;">Usuarios Recientes</h3>
                                        <p class="text-sm" style="color: #475569 !important;">Últimos registros en el sistema</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-md">
                                    {{ $totalUsers }} total
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($recentUsers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentUsers as $user)
                                        <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                                            <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-lg flex items-center justify-center shadow-md">
                                                <span class="text-sm font-bold text-white">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <p class="font-medium truncate" style="color: #1e293b !important;">{{ $user->name }}</p>
                                                    @if($user->isAdmin())
                                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-md">
                                                            Admin
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm truncate" style="color: #475569 !important;">{{ $user->email }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs" style="color: #64748b !important;">
                                                    {{ $user->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-medium" style="color: #475569 !important;">No hay usuarios recientes</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal de Perfil -->
        @auth
            <livewire:profile-modal />
        @endauth
        
        <!-- Modal de Contraseña -->
        @auth
            @if(empty(auth()->user()->oauth_provider))
                <livewire:password-modal />
            @endif
        @endauth
        
        <!-- Modal de Verificación de Email -->
        @auth
            <livewire:verification-modal />
        @endauth

        @livewireScripts
        
        <!-- Scripts -->
        <script>
            // Funciones para modales
            function openProfileModal() {
                Livewire.dispatch('openProfileModal');
            }

            function openPasswordModal() {
                Livewire.dispatch('openPasswordModal');
            }
        </script>
    </body>
</html>