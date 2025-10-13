<!-- Navegación -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md transition-all duration-300 border-b border-slate-200/50 shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="h-12 w-auto object-contain">
                    <span class="text-2xl font-bold text-black">Quality</span>
                </a>
            </div>
            
            <!-- Navegación Desktop -->
            <div class="hidden md:flex items-center space-x-2">
                <!-- Links principales -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('home') }}" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md">Inicio</a>
                    <a href="{{ route('home') }}#nosotros" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md" onclick="navigateToSection(event, '{{ route('home') }}', 'nosotros')">Nosotros</a>
                    <a href="{{ route('home') }}#servicios" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md" onclick="navigateToSection(event, '{{ route('home') }}', 'servicios')">Productos</a>
                    <a href="{{ route('galeria') }}" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md" onclick="navigateToGallery(event, '{{ route('home') }}', '{{ route('galeria') }}')">Proyectos</a>
                    <a href="{{ route('home') }}#ubicacion" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md" onclick="navigateToSection(event, '{{ route('home') }}', 'ubicacion')">Contacto</a>
                    <a href="{{ route('proforma') }}" target="_blank" class="nav-link text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md">Cotizar</a>
                </div>
                
                <!-- Autenticación -->
                <div class="flex items-center space-x-2 ml-4">
                    
                    @auth
                        <!-- Usuario logueado -->
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md">
                                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center shadow-sm">
                                    <span class="text-xs font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="font-medium text-sm max-w-20 truncate">{{ explode(' ', auth()->user()->name)[0] }}</span>
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div class="absolute right-0 mt-2 w-52 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 border border-slate-200/50">
                                <div class="py-2">
                                    <div class="px-4 py-3 border-b border-slate-200/50">
                                        <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-600 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-blue-50 transition-colors duration-300">
                                        <svg class="w-4 h-4 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        Panel de Control
                                    </a>
                                    <button onclick="openProfileModal()" 
                                            class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-50 transition-colors duration-300 w-full text-left">
                                        <svg class="w-4 h-4 mr-3 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        Mi Perfil
                                        <livewire:profile-status />
                                    </button>
                                    @if(empty(auth()->user()->oauth_provider))
                                        <button onclick="openPasswordModal()" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-50 transition-colors duration-300 w-full text-left">
                                            <svg class="w-4 h-4 mr-3 text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cambiar Contraseña
                                        </button>
                                    @endif
                                    <div class="border-t border-slate-200/50 mt-1"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-300">
                                            <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Usuario no logueado -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md">
                                Ingresar
                            </a>
                            <a href="{{ route('register') }}" class="text-slate-800 hover:text-blue-600 hover:bg-slate-100 backdrop-blur-sm transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-md">
                                Crear Cuenta
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
            
            <!-- Botón menú móvil -->
            <button data-mobile-menu class="md:hidden text-black hover:text-blue-600 focus:outline-none bg-white/80 hover:bg-white/90 backdrop-blur-sm p-2 rounded-xl border border-slate-300/50 shadow-sm transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Menú móvil -->
        <div data-mobile-menu-content class="hidden md:hidden mt-6 pb-4">
            <div class="bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/50 shadow-xl p-4">
                <div class="flex flex-col space-y-1">
                    <!-- Links principales -->
                    <a href="{{ route('home') }}" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50">Inicio</a>
                    <a href="{{ route('home') }}#nosotros" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50" onclick="navigateToSection(event, '{{ route('home') }}', 'nosotros')">Nosotros</a>
                    <a href="{{ route('home') }}#servicios" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50" onclick="navigateToSection(event, '{{ route('home') }}', 'servicios')">Productos</a>
                    <a href="{{ route('galeria') }}" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50" onclick="navigateToGallery(event, '{{ route('home') }}', '{{ route('galeria') }}')">Proyectos</a>
                    <a href="{{ route('home') }}#ubicacion" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50" onclick="navigateToSection(event, '{{ route('home') }}', 'ubicacion')">Contacto</a>
                    <a href="{{ route('proforma') }}" target="_blank" class="nav-link text-black hover:text-blue-600 hover:bg-slate-50 transition-all duration-300 font-bold px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50">Cotizar</a>
                    
                    
                    <!-- Separador -->
                    <div class="border-t border-slate-200/50 my-3"></div>
                    
                    @auth
                        <!-- Usuario logueado - Móvil -->
                        <div class="pt-1">
                            <div class="flex items-center space-x-3 mb-3 px-3 py-2 bg-slate-50 rounded-xl">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-semibold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 text-sm">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col space-y-1">
                                <a href="{{ route('dashboard') }}" class="text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-all duration-300 font-medium px-3 py-2 rounded-xl flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    <span>Panel de Control</span>
                                </a>
                                <button onclick="openProfileModal()" 
                                        class="w-full text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-all duration-300 font-medium px-3 py-2 rounded-xl flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Mi Perfil</span>
                                    <livewire:profile-status />
                                </button>
                                @if(empty(auth()->user()->oauth_provider))
                                    <button onclick="openPasswordModal()" class="w-full text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-all duration-300 font-medium px-3 py-2 rounded-xl flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Cambiar Contraseña</span>
                                    </button>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50 transition-all duration-300 font-medium px-3 py-2 rounded-xl flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Usuario no logueado - Móvil -->
                        <div class="pt-1 flex flex-col space-y-1">
                            <a href="{{ route('login') }}" class="text-black hover:text-blue-600 hover:bg-slate-50 font-bold transition-all duration-300 px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50 text-center">
                                Ingresar
                            </a>
                            <a href="{{ route('register') }}" class="text-black hover:text-blue-600 hover:bg-slate-50 font-bold transition-all duration-300 px-3 py-2 rounded-lg border border-transparent hover:border-slate-200/50 text-center">
                                Crear Cuenta
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menú móvil
    const mobileMenuButton = document.querySelector('[data-mobile-menu]');
    const mobileMenuContent = document.querySelector('[data-mobile-menu-content]');
    
    if (mobileMenuButton && mobileMenuContent) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenuContent.classList.toggle('hidden');
        });
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!mobileMenuButton.contains(event.target) && !mobileMenuContent.contains(event.target)) {
                mobileMenuContent.classList.add('hidden');
            }
        });
        
        // Cerrar menú al hacer clic en un enlace
        const mobileLinks = mobileMenuContent.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuContent.classList.add('hidden');
            });
        });
    }
    
    // Navbar scroll effect
    let lastScrollTop = 0;
    const navbar = document.getElementById('navbar');
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Cambiar opacidad y sombra basado en scroll
        if (scrollTop > 50) {
            navbar.classList.add('bg-white/95', 'shadow-lg');
            navbar.classList.remove('bg-white/90', 'shadow-sm');
        } else {
            navbar.classList.add('bg-white/90', 'shadow-sm');
            navbar.classList.remove('bg-white/95', 'shadow-lg');
        }
        
        lastScrollTop = scrollTop;
    });
});

// Funciones de navegación existentes
function navigateToSection(event, homeRoute, sectionId) {
    event.preventDefault();
    
    if (window.location.pathname === '/') {
        // Estamos en home, hacer scroll suave
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    } else {
        // Redirigir a home con hash
        window.location.href = homeRoute + '#' + sectionId;
    }
}

function navigateToGallery(event, homeRoute, galleryRoute) {
    event.preventDefault();
    window.location.href = galleryRoute;
}

function openProfileModal() {
    // Siempre abrir modal para editar perfil
    Livewire.dispatch('openProfileModal');
}

function openPasswordModal() {
    // Abrir modal para cambiar contraseña
    Livewire.dispatch('openPasswordModal');
}
</script>

