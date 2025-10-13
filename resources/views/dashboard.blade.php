<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>Panel de Control - Quality</title>
        <style>
            /* Gradientes personalizados */
            .gradient-bg {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            }
            
            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .card-hover:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
            
            /* Estados de carga */
            .pulse-bg {
                background: linear-gradient(90deg, #f1f5f9, #e2e8f0, #f1f5f9);
                background-size: 200% 100%;
                animation: pulse-bg 2s ease-in-out infinite;
            }
            
            @keyframes pulse-bg {
                0% { background-position: 200% 0; }
                100% { background-position: -200% 0; }
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
                <!-- Encabezado de bienvenida -->
                <div class="fade-in mb-8">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-slate-200/50 shadow-xl p-8">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800 mb-2">
                                    ¡Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                                </h1>
                                <p class="text-slate-600 text-lg">
                                    Gestiona tus proyectos y cotizaciones desde tu panel personal
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <!-- Estado del perfil -->
                                @php
                                    $user = auth()->user();
                                    $profileComplete = !empty($user->phone) && !empty($user->address) && !empty($user->city) && !empty($user->province);
                                @endphp
                                @if($profileComplete)
                                    <div class="flex items-center px-3 py-2 bg-green-100 text-green-800 rounded-lg">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Perfil Completo</span>
                                    </div>
                                @else
                                    <button onclick="openProfileModal()" 
                                            class="flex items-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Completar Perfil</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="fade-in grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" style="animation-delay: 0.1s;">
                    <div class="bg-white/80 backdrop-blur-md rounded-xl border border-slate-200/50 shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-slate-800">0</p>
                                <p class="text-slate-600 text-sm">Proformas Completadas</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md rounded-xl border border-slate-200/50 shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-slate-800">0</p>
                                <p class="text-slate-600 text-sm">Proyectos Activos</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-md rounded-xl border border-slate-200/50 shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-slate-600 text-sm">Miembro desde</p>
                                <p class="text-lg font-semibold text-slate-800">{{ auth()->user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección principal: Mis Proformas -->
                <div class="fade-in" style="animation-delay: 0.2s;">
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-slate-200/50 shadow-xl">
                        <!-- Header de la sección -->
                        <div class="px-8 py-6 border-b border-slate-200/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h6a2 2 0 002-2V3a2 2 0 012 2v6.5a1.5 1.5 0 01-1.5 1.5h-6A1.5 1.5 0 019 11.5V4H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a1 1 0 10-2 0v1H7V4a1 1 0 10-2 0v1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-800">Mis Proformas</h2>
                                        <p class="text-slate-600">Gestiona y revisa tus cotizaciones</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Contenido de proformas -->
                        <div class="px-8 py-12">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-12 h-12 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a2 2 0 002 2h6a2 2 0 002-2V3a2 2 0 012 2v6.5a1.5 1.5 0 01-1.5 1.5h-6A1.5 1.5 0 019 11.5V4H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1V4a1 1 0 10-2 0v1H7V4a1 1 0 10-2 0v1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-slate-800 mb-2">Aún no tienes proformas</h3>
                                <p class="text-slate-600 max-w-sm mx-auto">
                                    Tus proformas aparecerán aquí una vez que las crees desde nuestros productos
                                </p>
                            </div>
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