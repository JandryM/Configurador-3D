<!-- Navegación -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-black/10 backdrop-blur-md transition-all duration-300">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-2">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <a href="{{ route('home') }}" class="text-2xl font-bold text-white">Quality</a>
            </div>
            
            <!-- Navegación Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Inicio</a>
                <a href="{{ route('home') }}#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'nosotros')">Nosotros</a>
                <a href="{{ route('home') }}#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'servicios')">Servicios</a>
                <a href="{{ route('galeria') }}" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToGallery(event, '{{ route('home') }}', '{{ route('galeria') }}')">Galería</a>
                <a href="{{ route('proforma') }}" target="_blank" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium bg-gradient-to-r from-green-500 to-blue-500 px-4 py-2 rounded-lg font-semibold ml-2">Proforma</a>
                <a href="{{ route('home') }}#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'ubicacion')">Ubicación</a>
                
                @auth
                    <!-- Usuario logueado -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Dashboard
                        </a>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-white/90 hover:text-white transition-colors duration-300">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="py-2">
                                    <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Mi Perfil
                                    </a>
                                    <a href="{{ route('settings.appearance') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300">
                                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Configuración
                                    </a>
                                    <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
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
                        <a href="{{ route('login') }}" class="text-white/90 hover:text-white font-medium transition-colors duration-300">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Registrarse
                        </a>
                    </div>
                @endauth
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
                <a href="{{ route('home') }}" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium">Inicio</a>
                <a href="{{ route('home') }}#nosotros" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'nosotros')">Nosotros</a>
                <a href="{{ route('home') }}#servicios" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'servicios')">Servicios</a>
                <a href="{{ route('galeria') }}" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToGallery(event, '{{ route('home') }}', '{{ route('galeria') }}')">Galería</a>
                <a href="{{ route('home') }}#ubicacion" class="nav-link text-white/90 hover:text-white transition-colors duration-300 font-medium" onclick="navigateToSection(event, '{{ route('home') }}', 'ubicacion')">Ubicación</a>
                
                @auth
                    <!-- Usuario logueado - Móvil -->
                    <div class="border-t border-white/20 pt-3 mt-3">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('dashboard') }}" class="text-white/90 hover:text-white transition-colors duration-300 font-medium">Dashboard</a>
                            <a href="{{ route('settings.profile') }}" class="text-white/90 hover:text-white transition-colors duration-300 font-medium">Mi Perfil</a>
                            <a href="{{ route('settings.appearance') }}" class="text-white/90 hover:text-white transition-colors duration-300 font-medium">Configuración</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors duration-300 font-medium">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Usuario no logueado - Móvil -->
                    <div class="border-t border-white/20 pt-3 mt-3 flex flex-col space-y-2">
                        <a href="{{ route('login') }}" class="text-white/90 hover:text-white font-medium transition-colors duration-300">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg font-semibold inline-block text-center">
                            Registrarse
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

