<x-layouts.guest :title="__('Quality - Soluciones en Aluminio, Vidrio y Melamina')">
    <!-- Hero Slider -->
    <section id="inicio" class="relative">
        <div class="glide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <li class="glide__slide relative">
                        <div class="h-[600px] bg-cover bg-center bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900">
                            <div class="absolute inset-0 bg-black/60 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-4xl mx-auto">
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-blue-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent leading-tight">
                                            Soluciones en Aluminio y Vidrio
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-gray-200">Calidad y diseño en cada proyecto</p>
                                        <a href="#servicios" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                            Nuestros Servicios
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="glide__slide relative">
                        <div class="h-[600px] bg-cover bg-center bg-gradient-to-br from-purple-900 via-pink-900 to-indigo-900">
                            <div class="absolute inset-0 bg-black/60 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-4xl mx-auto">
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent leading-tight">
                                            Muebles de Melamina a Medida
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-gray-200">Diseños personalizados para tu hogar u oficina</p>
                                        <a href="#galeria" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                            Ver Proyectos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="glide__slide relative">
                        <div class="h-[600px] bg-cover bg-center bg-gradient-to-br from-indigo-900 via-blue-900 to-purple-900">
                            <div class="absolute inset-0 bg-black/60 flex items-center">
                                <div class="container mx-auto px-6 text-center">
                                    <div class="max-w-4xl mx-auto">
                                        <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-indigo-400 via-blue-400 to-purple-400 bg-clip-text text-transparent leading-tight">
                                            Trabajos en Gypsum y Cielo Raso
                                        </h1>
                                        <p class="text-xl md:text-2xl mb-8 text-gray-200">Transformamos tus espacios con elegancia</p>
                                        <a href="#nosotros" class="inline-block bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                            Conócenos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="glide__bullets absolute bottom-6 left-1/2 transform -translate-x-1/2" data-glide-el="controls[nav]">
                <button class="glide__bullet w-3 h-3 bg-white/50 rounded-full mx-2 transition-all duration-300" data-glide-dir="=0"></button>
                <button class="glide__bullet w-3 h-3 bg-white/50 rounded-full mx-2 transition-all duration-300" data-glide-dir="=1"></button>
                <button class="glide__bullet w-3 h-3 bg-white/50 rounded-full mx-2 transition-all duration-300" data-glide-dir="=2"></button>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section id="nosotros" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Sobre <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Nosotros</span>
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="flex flex-col lg:flex-row items-center max-w-7xl mx-auto">
                <div class="lg:w-1/2 mb-12 lg:mb-0">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 rounded-3xl transform rotate-3"></div>
                        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=958&q=80" 
                             alt="Nuestro equipo" 
                             class="relative rounded-3xl shadow-2xl">
                    </div>
                </div>
                <div class="lg:w-1/2 lg:pl-16">
                    <h3 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Calidad y Experiencia en Cada Proyecto</h3>
                    <p class="text-lg mb-6 text-gray-600 dark:text-gray-300">En Quality nos especializamos en la fabricación e instalación de productos de aluminio, vidrio, melamina y gypsum para hogares y empresas.</p>
                    <p class="text-lg mb-6 text-gray-600 dark:text-gray-300">Con más de 10 años de experiencia, nuestro equipo de profesionales se compromete a brindar soluciones de alta calidad, innovadoras y personalizadas según las necesidades de cada cliente.</p>
                    <p class="text-lg mb-8 text-gray-600 dark:text-gray-300">Nuestra misión es transformar espacios con diseños funcionales y estéticos que superen las expectativas de nuestros clientes.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Materiales de calidad</span>
                        </div>
                        <div class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                            <div class="bg-gradient-to-br from-purple-600 to-pink-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Diseño personalizado</span>
                        </div>
                        <div class="flex items-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                            <div class="bg-gradient-to-br from-indigo-600 to-blue-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Instalación profesional</span>
                        </div>
                        <div class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                            <div class="bg-gradient-to-br from-green-600 to-emerald-600 p-3 rounded-xl mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-900 dark:text-white">Garantía en todos nuestros trabajos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Proforma -->
    <section id="proforma" class="py-20 bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Solicita tu <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Proforma</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">Cotiza productos y servicios a medida para tu proyecto</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            <div class="flex justify-center">
                <a href="{{ route('proforma') }}" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-10 py-5 rounded-2xl font-bold text-xl shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    Ir a Proforma
                </a>
            </div>
        </div>
    </section>
    <!-- Servicios -->
    <section id="servicios" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Nuestros <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Servicios</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">Ofrecemos soluciones integrales para transformar tus espacios</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <!-- Servicio 1 -->
                <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-48 bg-gradient-to-br from-blue-600 to-indigo-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Aluminio y Vidrio</h3>
                        <p class="text-gray-600 dark:text-gray-300">Ventanas, puertas, portones y divisiones de oficina con los mejores materiales.</p>
                    </div>
                </div>
                
                <!-- Servicio 2 -->
                <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-48 bg-gradient-to-br from-purple-600 to-pink-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM15 7a1 1 0 112 0v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Melamina</h3>
                        <p class="text-gray-600 dark:text-gray-300">Cocinas, closets, escritorios y muebles a medida con diseños modernos y funcionales.</p>
                    </div>
                </div>
                
                <!-- Servicio 3 -->
                <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-48 bg-gradient-to-br from-indigo-600 to-blue-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Gypsum</h3>
                        <p class="text-gray-600 dark:text-gray-300">Techos, paredes y diseños decorativos en gypsum para interiores residenciales y comerciales.</p>
                    </div>
                </div>
                
                <!-- Servicio 4 -->
                <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                    <div class="relative overflow-hidden rounded-t-3xl">
                        <div class="h-48 bg-gradient-to-br from-green-600 to-emerald-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-6 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Cielo Raso</h3>
                        <p class="text-gray-600 dark:text-gray-300">Instalación y diseño de cielos rasos modernos que mejoran la estética de cualquier espacio.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería -->
    <section id="galeria" class="py-20 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Nuestra <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Galería</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">Algunos de nuestros proyectos realizados</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            
            <!-- Productos destacados dinámicos -->
            <livewire:featured-products />
            <!-- Botón Ver más -->
            <div class="flex justify-center mt-8">
                <a href="{{ route('galeria') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold text-lg shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    Ver más proyectos
                </a>
            </div>
            </div>
        </div>
    </section>

    <!-- Ubicación -->
    <section id="ubicacion" class="py-20 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Nuestra <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Ubicación</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">Visítanos en nuestra oficina principal</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 max-w-7xl mx-auto items-center">
                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Información de Contacto</h3>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Dirección</p>
                                <p class="text-gray-600 dark:text-gray-300">Sangolquí, Sector de Rosalinda, El Triángulo</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Teléfono</p>
                                <p class="text-gray-600 dark:text-gray-300">0983815678</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Email</p>
                                <p class="text-gray-600 dark:text-gray-300">qualityservices@gmail.com</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Horario</p>
                                <p class="text-gray-600 dark:text-gray-300">Lunes - Viernes: 8:00 AM - 6:00 PM<br>Sábados: 8:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-1">
                        <div class="bg-white dark:bg-gray-900 rounded-3xl overflow-hidden">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.691686734843!2d-78.4471637!3d-0.3168815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91d59c8fefe6a4a3%3A0x4f6e5fef5d6e4c4c!2sSangolqu%C3%AD%2C%20Ecuador!5e0!3m2!1ses!2sec!4v1654321098765!5m2!1ses!2sec"
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
    <footer class="bg-gray-900 dark:bg-gray-950 text-white">
        <div class="container mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Información de la empresa -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl p-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold">Quality Services</h3>
                    </div>
                    <p class="text-gray-300 text-lg mb-6 leading-relaxed">
                        Transformamos espacios con soluciones integrales en aluminio, vidrio, melamina, gypsum y cielo raso. 
                        Calidad y profesionalismo en cada proyecto.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                        <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white p-3 rounded-full transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Enlaces rápidos -->
                <div>
                    <h4 class="text-xl font-semibold mb-6">Enlaces Rápidos</h4>
                    <ul class="space-y-3">
                        <li><a href="#inicio" class="text-gray-300 hover:text-white transition-colors duration-300">Inicio</a></li>
                        <li><a href="#about" class="text-gray-300 hover:text-white transition-colors duration-300">Nosotros</a></li>
                        <li><a href="#servicios" class="text-gray-300 hover:text-white transition-colors duration-300">Servicios</a></li>
                        <li><a href="#galeria" class="text-gray-300 hover:text-white transition-colors duration-300">Galería</a></li>
                        <li><a href="#ubicacion" class="text-gray-300 hover:text-white transition-colors duration-300">Ubicación</a></li>
                    </ul>
                </div>
                
                <!-- Contacto -->
                <div>
                    <h4 class="text-xl font-semibold mb-6">Contacto</h4>
                    <div class="space-y-3">
                        <p class="text-gray-300 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Sangolquí, El Triángulo
                        </p>
                        <p class="text-gray-300 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            0983815678
                        </p>
                        <p class="text-gray-300 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            qualityservices@gmail.com
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Quality Services. Todos los derechos reservados.</p>
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

        // Navbar transparente en scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.remove('bg-black/10');
                navbar.classList.add('bg-gray-900/95', 'shadow-2xl');
            } else {
                navbar.classList.add('bg-black/10');
                navbar.classList.remove('bg-gray-900/95', 'shadow-2xl');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('[data-mobile-menu]');
        const mobileMenu = document.querySelector('[data-mobile-menu-content]');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</x-layouts.guest>