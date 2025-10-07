<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Configurador 3D' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Meta tags para SEO -->
    <meta name="description" content="{{ $description ?? 'Configurador 3D interactivo para personalizar productos de Quality Services' }}">
    <meta name="keywords" content="configurador 3D, personalización, muebles, ventanas, puertas">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $title ?? 'Configurador 3D' }} - {{ config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'Personaliza productos en 3D en tiempo real' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navegación Superior -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">Quality Services</span>
                    </a>
                </div>

                <!-- Navegación Central -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        Inicio
                    </a>
                    <a href="{{ route('galeria') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        Galería
                    </a>
                    <span class="text-blue-600 font-medium">
                        Configurador 3D
                    </span>
                </div>

                <!-- Acciones -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">Hola, {{ auth()->user()->name }}</span>
                            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Mi Cuenta
                            </a>
                        </div>
                    @else
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
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
        {{ $slot }}
    </main>

    <!-- Footer Simple -->
    <footer class="bg-gray-800 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quality Services</h3>
                    <p class="text-gray-300 text-sm">
                        Especialistas en muebles y estructuras de aluminio y vidrio.
                        Configurador 3D para personalizar productos según tus necesidades.
                    </p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="{{ route('galeria') }}" class="text-gray-300 hover:text-white">Galería</a></li>
                        <li><a href="{{ route('proforma') }}" class="text-gray-300 hover:text-white">Proforma</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li>📞 0983815678</li>
                        <li>📧 qualityservices@gmail.com</li>
                        <li>📍 Ecuador</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Quality Services. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Los toasts se agregarán aquí dinámicamente -->
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Scripts adicionales -->
    @stack('scripts')

    <!-- Script para notificaciones -->
    <script>
        // Sistema simple de notificaciones
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };
            
            toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
            toast.textContent = message;
            
            container.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => container.removeChild(toast), 300);
            }, 5000);
        };

        // Escuchar eventos de Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                showToast(data[0].message, data[0].type || 'info');
            });
        });
    </script>
</body>
</html>