@extends('layouts.guest')
@section('title', __('Quality - Soluciones en Aluminio, Vidrio y Melamina'))
@section('content')
    <!-- Modal de verificación de correo (global, escucha evento Livewire) -->
    @auth
        <livewire:verification-modal />
    @endauth
    <style>[x-cloak] { display: none !important; }</style>

    <!-- Modal de Login (solo el modal, sin botón) -->
    @guest
    <div x-data x-init="window.Alpine && Alpine.store('loginModal') === undefined ? Alpine.store('loginModal', { open: false }) : null">
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
    
    @endguest

        <!-- Modal de Registro (solo el modal, sin botón) -->
        @guest
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
        @endguest
    <!-- Hero Slider -->
    <section id="inicio" class="relative">
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <!-- Slide 1: Ventanas Normales -->
                    <li class="glide__slide relative">
                        <div class="h-[700px] bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(30,58,138,0.9), rgba(59,130,246,0.8)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80');">
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-5xl mx-auto">
                                        <div class="mb-6">
                                            <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-200/20 backdrop-blur-md rounded-2xl mb-4">
                                                <svg class="w-10 h-10 text-slate-100" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M21 2H3c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H3V4h18v16zm-10-1h2V5h-2v14zm4 0h2V5h-2v14z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 text-slate-100 leading-tight">
                                            Ventanas de Aluminio
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-slate-200">Ventanas resistentes y elegantes para tu hogar</p>
                                        <a href="#servicios" class="inline-block bg-slate-600/80 backdrop-blur-md hover:bg-slate-700/90 text-slate-100 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-slate-400/30">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Slide 2: Ventanas de Baño -->
                    <li class="glide__slide relative">
                        <div class="h-[700px] bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(71,85,105,0.9), rgba(148,163,184,0.8)), url('https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-5xl mx-auto">
                                        <div class="mb-6">
                                            <div class="inline-flex items-center justify-center w-20 h-20 bg-cyan-200/20 backdrop-blur-md rounded-2xl mb-4">
                                                <svg class="w-10 h-10 text-cyan-100" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9,2V8H7V2H9M13,2V8H11V2H13M17,2V8H15V2H17M19,9A2,2 0 0,1 21,11V20A2,2 0 0,1 19,22H5A2,2 0 0,1 3,20V11A2,2 0 0,1 5,9H19Z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 text-cyan-50 leading-tight">
                                            Ventanas de Baño
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-cyan-100">Privacidad y ventilación perfecta para espacios húmedos</p>
                                        <a href="#servicios" class="inline-block bg-cyan-600/80 backdrop-blur-md hover:bg-cyan-700/90 text-cyan-50 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-cyan-400/30">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Slide 3: Portones -->
                    <li class="glide__slide relative">
                        <div class="h-[700px] bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(55,65,81,0.9), rgba(107,114,128,0.8)), url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=2069&q=80');">
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-5xl mx-auto">
                                        <div class="mb-6">
                                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-200/20 backdrop-blur-md rounded-2xl mb-4">
                                                <svg class="w-10 h-10 text-gray-100" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12,3C13.75,3 16.95,3.8 19.28,6.11C21.75,8.61 22.58,11.82 20.97,16.63C20.71,17.38 20.16,18 19.39,18.24C18.92,18.39 18.07,18.58 16.5,18.58C15.23,18.58 13.75,18.47 12,18.47C10.25,18.47 8.77,18.58 7.5,18.58C5.93,18.58 5.08,18.39 4.61,18.24C3.84,18 3.29,17.38 3.03,16.63C1.42,11.82 2.25,8.61 4.72,6.11C7.05,3.8 10.25,3 12,3M12,7.5A4.5,4.5 0 0,0 7.5,12A4.5,4.5 0 0,0 12,16.5A4.5,4.5 0 0,0 16.5,12A4.5,4.5 0 0,0 12,7.5M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 text-gray-100 leading-tight">
                                            Portones de Aluminio
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-gray-200">Seguridad y estilo para la entrada de tu hogar</p>
                                        <a href="#servicios" class="inline-block bg-gray-600/80 backdrop-blur-md hover:bg-gray-700/90 text-gray-100 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-gray-400/30">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Slide 4: Mallas Antimosquiteras -->
                    <li class="glide__slide relative">
                        <div class="h-[700px] bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(14,116,144,0.9), rgba(34,197,94,0.8)), url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                            <div class="absolute inset-0 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-5xl mx-auto">
                                        <div class="mb-6">
                                            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-200/20 backdrop-blur-md rounded-2xl mb-4">
                                                <svg class="w-10 h-10 text-emerald-100" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M21,16V4H3V16H21M21,2A2,2 0 0,1 23,4V16A2,2 0 0,1 21,18H3A2,2 0 0,1 1,16V4A2,2 0 0,1 3,2H21M5,6H19V14H5V6Z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 text-emerald-50 leading-tight">
                                            Mallas Antimosquiteras
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-emerald-100">Protección natural contra insectos sin sacrificar la ventilación</p>
                                        <a href="#servicios" class="inline-block bg-emerald-600/80 backdrop-blur-md hover:bg-emerald-700/90 text-emerald-50 px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-emerald-400/30">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="glide__bullets absolute bottom-6 left-1/2 transform -translate-x-1/2" data-glide-el="controls[nav]">
                <button class="glide__bullet w-4 h-4 bg-slate-300/60 hover:bg-slate-100 rounded-full mx-2 transition-all duration-300 backdrop-blur-sm" data-glide-dir="=0"></button>
                <button class="glide__bullet w-4 h-4 bg-slate-300/60 hover:bg-slate-100 rounded-full mx-2 transition-all duration-300 backdrop-blur-sm" data-glide-dir="=1"></button>
                <button class="glide__bullet w-4 h-4 bg-slate-300/60 hover:bg-slate-100 rounded-full mx-2 transition-all duration-300 backdrop-blur-sm" data-glide-dir="=2"></button>
                <button class="glide__bullet w-4 h-4 bg-slate-300/60 hover:bg-slate-100 rounded-full mx-2 transition-all duration-300 backdrop-blur-sm" data-glide-dir="=3"></button>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section id="nosotros" class="py-20 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                    Sobre <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Quality</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-cyan-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="flex flex-col lg:flex-row items-center max-w-7xl mx-auto">
                <div class="lg:w-1/2 mb-12 lg:mb-0">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/15 to-cyan-600/15 rounded-3xl transform rotate-2"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-600/10 to-blue-600/10 rounded-3xl transform -rotate-2"></div>
                        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=958&q=80" 
                             alt="Trabajos de aluminio y vidrio Quality" 
                             class="relative rounded-3xl shadow-2xl">
                    </div>
                </div>
                <div class="lg:w-1/2 lg:pl-16">
                    <h3 class="text-3xl font-bold mb-6 text-slate-800">Especialistas en Aluminio y Vidrio</h3>
                    <p class="text-lg mb-6 text-slate-600 leading-relaxed">En <strong>Quality</strong> somos una empresa especializada en la fabricación e instalación de productos de aluminio y vidrio de alta calidad. Nos enfocamos en cuatro líneas principales: ventanas residenciales, ventanas de baño, portones de seguridad y mallas antimosquiteras.</p>
                    <p class="text-lg mb-6 text-slate-600 leading-relaxed">Con más de 8 años de experiencia en el mercado, nuestro equipo de técnicos especializados garantiza acabados perfectos y durabilidad excepcional en cada proyecto.</p>
                    <p class="text-lg mb-8 text-slate-600 leading-relaxed">Trabajamos con materiales de primera calidad y ofrecemos diseños personalizados que se adaptan a las necesidades específicas de cada cliente y espacio.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-4 bg-blue-50/70 backdrop-blur-sm rounded-2xl border border-blue-100">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-blue-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">Aluminio premium</span>
                        </div>
                        <div class="flex items-center p-4 bg-cyan-50/70 backdrop-blur-sm rounded-2xl border border-cyan-100">
                            <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-cyan-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">Vidrio templado</span>
                        </div>
                        <div class="flex items-center p-4 bg-slate-50/70 backdrop-blur-sm rounded-2xl border border-slate-200">
                            <div class="bg-gradient-to-br from-slate-500 to-slate-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-slate-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">Instalación técnica</span>
                        </div>
                        <div class="flex items-center p-4 bg-emerald-50/70 backdrop-blur-sm rounded-2xl border border-emerald-100">
                            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-emerald-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-slate-700">Garantía 2 años</span>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="mt-8 grid grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">500+</div>
                            <div class="text-sm text-slate-600">Proyectos realizados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-cyan-600">8+</div>
                            <div class="text-sm text-slate-600">Años de experiencia</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-emerald-600">100%</div>
                            <div class="text-sm text-slate-600">Clientes satisfechos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Proforma -->
    <section id="proforma" class="py-20 bg-gradient-to-br from-cyan-50 via-blue-50 to-slate-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                    Solicita tu <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Cotización</span>
                </h2>
                <p class="text-xl text-slate-600 mb-6">Obtén presupuestos personalizados para tus proyectos de aluminio y vidrio</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-cyan-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-slate-50/80 backdrop-blur-sm rounded-3xl shadow-xl border border-slate-200/50 p-8 md:p-12">
                    <div class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">¿Listo para tu proyecto?</h3>
                            <p class="text-slate-600 mb-6 leading-relaxed">Nuestro equipo te ayudará a calcular los materiales necesarios y costos exactos para tu proyecto. Recibe una cotización detallada sin compromiso.</p>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-center text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Medición gratuita a domicilio
                                </li>
                                <li class="flex items-center text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Presupuesto detallado en 24h
                                </li>
                                <li class="flex items-center text-slate-600">
                                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Asesoría técnica especializada
                                </li>
                            </ul>
                        </div>
                        <div class="flex justify-center">
                            <a href="{{ route('proforma') }}" class="inline-block bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-slate-50 px-10 py-5 rounded-2xl font-bold text-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-blue-500/20">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Crear Cotización</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Productos -->
    <section id="servicios" class="py-20 bg-gradient-to-br from-slate-100 via-blue-50 to-cyan-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                    Nuestros <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Productos</span>
                </h2>
                <p class="text-xl text-slate-600 mb-6">Especialistas en aluminio y vidrio de alta calidad</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-cyan-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <!-- Producto 1: Ventanas Normales -->
                <div class="group bg-slate-50/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-slate-200/50">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-56 bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(30,58,138,0.8), rgba(59,130,246,0.6)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80');"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-slate-200/25 backdrop-blur-md rounded-full p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-slate-100" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 2H3c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H3V4h18v16zm-10-1h2V5h-2v14zm4 0h2V5h-2v14z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-slate-800">Ventanas de Aluminio</h3>
                        <p class="text-slate-600 leading-relaxed">Ventanas resistentes y duraderas, perfectas para hogares y oficinas. Diseño moderno con máxima eficiencia térmica.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-blue-600 font-semibold">Desde $150</span>
                            <button class="text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Producto 2: Ventanas de Baño -->
                <div class="group bg-cyan-50/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-cyan-200/50">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-56 bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(71,85,105,0.8), rgba(148,163,184,0.6)), url('https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80');"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-cyan-200/25 backdrop-blur-md rounded-full p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-cyan-100" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9,2V8H7V2H9M13,2V8H11V2H13M17,2V8H15V2H17M19,9A2,2 0 0,1 21,11V20A2,2 0 0,1 19,22H5A2,2 0 0,1 3,20V11A2,2 0 0,1 5,9H19Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-slate-800">Ventanas de Baño</h3>
                        <p class="text-slate-600 leading-relaxed">Diseñadas especialmente para espacios húmedos. Vidrio esmerilado para privacidad y ventilación óptima.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-cyan-600 font-semibold">Desde $120</span>
                            <button class="text-cyan-600 hover:text-cyan-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Producto 3: Portones -->
                <div class="group bg-gray-50/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-gray-200/50">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-56 bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(55,65,81,0.8), rgba(107,114,128,0.6)), url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80');"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-gray-200/25 backdrop-blur-md rounded-full p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-gray-100" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,3C13.75,3 16.95,3.8 19.28,6.11C21.75,8.61 22.58,11.82 20.97,16.63C20.71,17.38 20.16,18 19.39,18.24C18.92,18.39 18.07,18.58 16.5,18.58C15.23,18.58 13.75,18.47 12,18.47C10.25,18.47 8.77,18.58 7.5,18.58C5.93,18.58 5.08,18.39 4.61,18.24C3.84,18 3.29,17.38 3.03,16.63C1.42,11.82 2.25,8.61 4.72,6.11C7.05,3.8 10.25,3 12,3M12,7.5A4.5,4.5 0 0,0 7.5,12A4.5,4.5 0 0,0 12,16.5A4.5,4.5 0 0,0 16.5,12A4.5,4.5 0 0,0 12,7.5M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-slate-800">Portones de Aluminio</h3>
                        <p class="text-slate-600 leading-relaxed">Seguridad y elegancia para la entrada principal. Diseños personalizados con acabados de alta calidad.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-gray-600 font-semibold">Desde $350</span>
                            <button class="text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Producto 4: Mallas Antimosquiteras -->
                <div class="group bg-emerald-50/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-emerald-200/50">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-56 bg-cover bg-center" style="background-image: linear-gradient(135deg, rgba(14,116,144,0.8), rgba(34,197,94,0.6)), url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80');"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-emerald-200/25 backdrop-blur-md rounded-full p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-emerald-100" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21,16V4H3V16H21M21,2A2,2 0 0,1 23,4V16A2,2 0 0,1 21,18H3A2,2 0 0,1 1,16V4A2,2 0 0,1 3,2H21M5,6H19V14H5V6Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-slate-800">Mallas Antimosquiteras</h3>
                        <p class="text-slate-600 leading-relaxed">Protección efectiva contra insectos. Mallas de alta calidad que permiten la ventilación natural.</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-emerald-600 font-semibold">Desde $45</span>
                            <button class="text-emerald-600 hover:text-emerald-800 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería -->
    <section id="galeria" class="py-20 bg-gradient-to-br from-slate-50 via-cyan-50 to-blue-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                    Nuestros <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Proyectos</span>
                </h2>
                <p class="text-xl text-slate-600 mb-6">Trabajos realizados en aluminio y vidrio que demuestran nuestra calidad</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-cyan-600 mx-auto rounded-full"></div>
            </div>
            
            <!-- Productos destacados dinámicos -->
            <div class="relative">
                <livewire:featured-products />
            </div>
            
            <!-- Botón Ver más -->
            <div class="flex justify-center mt-12">
                <a href="{{ route('galeria') }}" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-slate-50 px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl border border-blue-500/20 space-x-2">
                    <span>Ver más proyectos</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Ubicación -->
    <section id="ubicacion" class="py-20 bg-gradient-to-br from-blue-50 via-slate-100 to-cyan-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 text-slate-800">
                    Nuestra <span class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">Ubicación</span>
                </h2>
                <p class="text-xl text-slate-600 mb-6">Visítanos en nuestro taller y showroom</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-cyan-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 max-w-7xl mx-auto items-center">
                <div class="bg-slate-50/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-slate-200/50">
                    <h3 class="text-2xl font-bold mb-6 text-slate-800">Información de Contacto</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-slate-50 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Dirección</p>
                                <p class="text-slate-600">Calle Martha de Roldos, Av. Jimmy Garcia</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-slate-50 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Teléfono / WhatsApp</p>
                                <p class="text-slate-600">0999917650</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-slate-500 to-slate-600 text-slate-50 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Email</p>
                                <p class="text-slate-600">info@quality.ec</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-slate-50 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Horarios de Atención</p>
                                <p class="text-slate-600">
                                    Lunes - Viernes: 8:00 AM - 6:00 PM<br>
                                    Sábados: 8:00 AM - 4:00 PM<br>
                                    <span class="text-sm text-slate-500">Domingos: Solo emergencias</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de contacto -->
                    <div class="mt-8 grid grid-cols-2 gap-4">
                    
                        <a href="https://wa.me/593999917650" target="_blank" class="flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-gradient-to-r from-blue-600/20 to-cyan-600/20 rounded-3xl p-1">
                        <div class="bg-slate-50/90 backdrop-blur-sm rounded-3xl overflow-hidden border border-slate-200/50">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.254911100912!2d-80.70089242541393!3d-0.9628932990278053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x902be7c5001c526d%3A0x561441bda82f4405!2sQUALITY!5e0!3m2!1ses-419!2sec!4v1760240815315!5m2!1ses-419!2sec"
                                width="100%"
                                height="400"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full h-96">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-slate-800 via-slate-900 to-blue-900 text-slate-100">
        <div class="container mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Información de la empresa -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="h-12 w-auto object-contain">
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">Quality</h3>
                    </div>
                    <p class="text-slate-300 text-lg mb-6 leading-relaxed">
                        Especialistas en aluminio y vidrio con más de 8 años transformando espacios. Ofrecemos ventanas, portones, mallas antimosquiteras y soluciones personalizadas con la más alta calidad.
                    </p>
                    
                    <!-- Certificaciones -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-3">
                            <div class="bg-slate-700/50 backdrop-blur-sm px-3 py-1 rounded-full text-sm text-slate-300 border border-slate-600/50">
                                ✓ Garantía 2 años
                            </div>
                            <div class="bg-slate-700/50 backdrop-blur-sm px-3 py-1 rounded-full text-sm text-slate-300 border border-slate-600/50">
                                ✓ Instalación profesional
                            </div>
                            <div class="bg-slate-700/50 backdrop-blur-sm px-3 py-1 rounded-full text-sm text-slate-300 border border-slate-600/50">
                                ✓ Materiales premium
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        
                        <a href="https://wa.me/593999917650" target="_blank" class="bg-green-600/80 hover:bg-green-500 text-slate-50 p-3 rounded-full transition-all duration-300 transform hover:scale-110 border border-green-500/30">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488z"/>
                            </svg>
                        </a>
                        
                    </div>
                </div>
                
                <!-- Productos -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-slate-200">Nuestros Productos</h4>
                    <ul class="space-y-3">
                        <li><a href="#servicios" class="text-slate-300 hover:text-cyan-300 transition-colors duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ventanas de Aluminio
                        </a></li>
                        <li><a href="#servicios" class="text-slate-300 hover:text-cyan-300 transition-colors duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ventanas de Baño
                        </a></li>
                        <li><a href="#servicios" class="text-slate-300 hover:text-cyan-300 transition-colors duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Portones de Seguridad
                        </a></li>
                        <li><a href="#servicios" class="text-slate-300 hover:text-cyan-300 transition-colors duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mallas Antimosquiteras
                        </a></li>
                        <li><a href="#galeria" class="text-slate-300 hover:text-cyan-300 transition-colors duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                            Ver Proyectos
                        </a></li>
                    </ul>
                </div>
                
                <!-- Contacto -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-slate-200">Contacto Directo</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="bg-blue-600/30 p-2 rounded-lg">
                                <svg class="w-4 h-4 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-200 font-medium">Ubicación</p>
                                <p class="text-slate-400 text-sm">Calle Martha de Roldos, Av. Jimmy Garcia</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-cyan-600/30 p-2 rounded-lg">
                                <svg class="w-4 h-4 text-cyan-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-200 font-medium">Teléfono</p>
                                <p class="text-slate-400 text-sm">0999917650</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-slate-600/30 p-2 rounded-lg">
                                <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-slate-200 font-medium">Horarios</p>
                                <p class="text-slate-400 text-sm">Lun-Vie: 8AM-6PM<br>Sáb: 8AM-4PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-700/50 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-slate-400 text-center md:text-left">&copy; 2024 Quality. Todos los derechos reservados.</p>
                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <span class="text-slate-500 text-sm">Hecho con</span>
                        <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-slate-500 text-sm">en Ecuador</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js"></script>
    <script>
        // Inicializar Glide
        new Glide('.glide', {
            type: 'carousel',
            autoplay: 4000,
            hoverpause: true,
            animationDuration: 800,
            animationTimingFunc: 'ease-in-out'
        }).mount();

        // Smooth scrolling para navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle
        var mobileMenuBtn = document.querySelector('[data-mobile-menu]');
        var mobileMenu = document.querySelector('[data-mobile-menu-content]');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
@endsection