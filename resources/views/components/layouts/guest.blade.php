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
            
            /* Estado activo permanente */
            .nav-link.nav-active::after {
                width: 100%;
                background: linear-gradient(to right, #10b981, #3b82f6);
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
    <body class="min-h-screen bg-white antialiased dark:bg-gray-900">
        <!-- Navegación utilizando el componente header -->
        <x-header />
        
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

        <!-- Contenido Principal -->
        <main>
            {{ $slot }}
        </main>

        @fluxScripts
        @livewireScripts
        
        <!-- Scripts para navegación -->
        <script>
            // Funciones de navegación
            function navigateToSection(event, homeUrl, sectionId) {
                event.preventDefault();
                
                // Si ya estamos en la página home, solo hacer scroll
                if (window.location.pathname === '/' || window.location.pathname.includes('/home')) {
                    const element = document.getElementById(sectionId);
                    if (element) {
                        // Actualizar la URL con el hash sin recargar la página
                        window.history.pushState(null, null, '#' + sectionId);
                        element.scrollIntoView({ behavior: 'smooth' });
                        // Actualizar navegación activa inmediatamente
                        setTimeout(() => {
                            setActiveNavigation();
                        }, 100);
                    }
                } else {
                    // Si estamos en otra página, navegar a home con el anchor
                    window.location.href = homeUrl + '#' + sectionId;
                }
            }

            function navigateToGallery(event, homeUrl, galleryUrl) {
                event.preventDefault();
                
                // Si estamos en la página home, ir a la sección galería
                if (window.location.pathname === '/' || window.location.pathname.includes('/home')) {
                    const element = document.getElementById('galeria');
                    if (element) {
                        // Actualizar la URL con el hash sin recargar la página
                        window.history.pushState(null, null, '#galeria');
                        element.scrollIntoView({ behavior: 'smooth' });
                        // Actualizar navegación activa inmediatamente
                        setTimeout(() => {
                            setActiveNavigation();
                        }, 100);
                    }
                } else if (window.location.pathname.includes('/galeria')) {
                    // Si ya estamos en la página de galería, no hacer nada
                    return;
                } else {
                    // Si estamos en otra página, ir a la página de galería
                    window.location.href = galleryUrl;
                }
            }

            function setActiveNavigation() {
                const currentPath = window.location.pathname;
                const currentHash = window.location.hash;
                const navLinks = document.querySelectorAll('.nav-link');
                
                // Remover clase activa de todos los enlaces
                navLinks.forEach(link => {
                    link.classList.remove('nav-active');
                });
                
                // Determinar qué enlace debe estar activo
                if (currentPath === '/' || currentPath.includes('/home')) {
                    if (currentHash) {
                        // Si hay hash, activar la sección correspondiente
                        const sectionId = currentHash.substring(1);
                        
                        if (sectionId === 'galeria') {
                            // Para la sección galería, activar el enlace de galería
                            const galleryLink = document.querySelector('[href*="galeria"]');
                            if (galleryLink) {
                                galleryLink.classList.add('nav-active');
                            }
                        } else {
                            // Para otras secciones, buscar por el hash
                            const activeLink = document.querySelector(`[href*="#${sectionId}"]`);
                            if (activeLink) {
                                activeLink.classList.add('nav-active');
                            }
                        }
                    } else {
                        // Si no hay hash, activar "Inicio"
                        const homeLink = document.querySelector('[href="{{ route("home") }}"].nav-link');
                        if (homeLink) {
                            homeLink.classList.add('nav-active');
                        }
                    }
                } else if (currentPath.includes('/galeria')) {
                    // Activar enlace de galería cuando estamos en la ruta /galeria
                    const galleryLink = document.querySelector('[href*="galeria"]');
                    if (galleryLink) {
                        galleryLink.classList.add('nav-active');
                    }
                }
            }

            // Función para manejar el clic en "Inicio"
            function navigateToHome(event) {
                event.preventDefault();
                
                if (window.location.pathname === '/' || window.location.pathname.includes('/home')) {
                    // Si ya estamos en home, quitar el hash y ir al top
                    window.history.pushState(null, null, window.location.pathname);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    setTimeout(() => {
                        setActiveNavigation();
                    }, 100);
                } else {
                    // Si estamos en otra página, ir a home
                    window.location.href = '{{ route("home") }}';
                }
            }

            // Inicialización cuando se carga la página
            document.addEventListener('DOMContentLoaded', function() {
                // Configurar navegación activa
                setActiveNavigation();
                
                // Agregar evento al enlace de Inicio
                const homeLinks = document.querySelectorAll('[href="{{ route("home") }}"].nav-link');
                homeLinks.forEach(link => {
                    link.addEventListener('click', navigateToHome);
                });
                
                // Manejar hash inicial
                if (window.location.hash) {
                    const sectionId = window.location.hash.substring(1);
                    const element = document.getElementById(sectionId);
                    if (element) {
                        setTimeout(() => {
                            element.scrollIntoView({ behavior: 'smooth' });
                            setActiveNavigation();
                        }, 100);
                    }
                }

                // Configurar menú móvil
                const mobileMenuButton = document.querySelector('[data-mobile-menu]');
                const mobileMenuContent = document.querySelector('[data-mobile-menu-content]');
                
                if (mobileMenuButton && mobileMenuContent) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenuContent.classList.toggle('hidden');
                    });
                }
            });

            // Event listeners
            window.addEventListener('hashchange', function() {
                setActiveNavigation();
            });

            // Scroll listener para navegación activa
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    if (window.location.pathname === '/' || window.location.pathname.includes('/home')) {
                        const sections = ['inicio', 'nosotros', 'servicios', 'galeria', 'ubicacion'];
                        let currentSection = '';
                        
                        for (let sectionId of sections) {
                            const element = document.getElementById(sectionId);
                            if (element) {
                                const rect = element.getBoundingClientRect();
                                if (rect.top <= 100 && rect.bottom >= 100) {
                                    currentSection = sectionId;
                                    break;
                                }
                            }
                        }
                        
                        if (currentSection) {
                            const newHash = currentSection === 'inicio' ? '' : '#' + currentSection;
                            const currentHash = window.location.hash;
                            
                            if ((newHash === '' && currentHash !== '') || (newHash !== '' && currentHash !== newHash)) {
                                if (newHash === '') {
                                    window.history.replaceState(null, null, window.location.pathname);
                                } else {
                                    window.history.replaceState(null, null, newHash);
                                }
                                setActiveNavigation();
                            }
                        }
                    } else if (window.location.pathname.includes('/galeria')) {
                        setActiveNavigation();
                    }
                }, 100);
            });
        </script>
    </body>
</html>