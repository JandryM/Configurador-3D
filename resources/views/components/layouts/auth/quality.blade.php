<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <style>
            .auth-bg {
                background: linear-gradient(135deg, #334155 0%, #475569 25%, #64748b 50%, #94a3b8 75%, #475569 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
                position: relative;
            }
            
            .auth-bg::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 30% 20%, rgba(147, 197, 253, 0.1) 0%, transparent 50%),
                           radial-gradient(circle at 70% 80%, rgba(156, 163, 175, 0.15) 0%, transparent 50%),
                           linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.02) 50%, transparent 70%);
            }
            
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .glassmorphism {
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }
            
            .floating {
                animation: float 8s ease-in-out infinite;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-8px) rotate(1deg); }
                66% { transform: translateY(4px) rotate(-1deg); }
            }
            
            .aluminum-pattern {
                background: repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 2px,
                    rgba(255, 255, 255, 0.03) 2px,
                    rgba(255, 255, 255, 0.03) 4px
                );
            }
        </style>
    </head>
    <body class="min-h-screen auth-bg antialiased overflow-x-hidden">
        <!-- Formas decorativas industriales -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Elementos que representan aluminio -->
            <div class="absolute top-16 left-8 w-28 h-28 bg-gradient-to-br from-slate-300/15 to-slate-400/10 rounded-lg blur-sm floating aluminum-pattern"></div>
            <div class="absolute top-32 right-16 w-36 h-24 bg-gradient-to-r from-gray-300/12 to-slate-300/8 blur-md floating" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-28 left-16 w-32 h-20 bg-gradient-to-br from-slate-400/10 to-gray-300/15 rounded-sm blur-sm floating" style="animation-delay: -4s;"></div>
            <div class="absolute bottom-16 right-12 w-24 h-32 bg-gradient-to-t from-slate-300/8 to-gray-400/12 blur-sm floating" style="animation-delay: -1s;"></div>
            
            <!-- Formas geométricas que representan ventanas y marcos -->
            <div class="absolute top-1/3 left-1/5 w-16 h-24 bg-gradient-to-b from-slate-300/12 to-transparent border border-white/8 floating" style="animation-delay: -3s;"></div>
            <div class="absolute bottom-1/4 right-1/4 w-20 h-20 bg-gradient-to-br from-gray-300/10 to-slate-400/8 transform rotate-12 border border-white/6 floating" style="animation-delay: -5s;"></div>
            
            <!-- Efectos de vidrio -->
            <div class="absolute top-1/2 left-1/3 w-3 h-40 bg-gradient-to-b from-transparent via-white/5 to-transparent blur-[1px] floating" style="animation-delay: -6s;"></div>
            <div class="absolute top-1/4 right-1/3 w-40 h-3 bg-gradient-to-r from-transparent via-white/4 to-transparent blur-[1px] floating" style="animation-delay: -7s;"></div>
        </div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-2 py-4 overflow-y-auto">
            <div class="w-full max-w-5xl">
                <!-- Logo y título -->
                <div class="text-center mb-2 floating">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 mb-1" wire:navigate>
                        <img src="{{ asset('images/logo.png') }}" alt="Quality Logo" class="h-8 w-auto object-contain filter brightness-0 invert">
                        <span class="text-lg font-bold text-white">Quality</span>
                    </a>
                    <p class="text-slate-200 text-xs">Soluciones en Aluminio y Vidrio</p>
                </div>

                <!-- Contenedor principal del formulario -->
                <div class="glassmorphism rounded-xl p-3 shadow-2xl min-h-fit mb-1">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center mt-2">
                    <p class="text-slate-300 text-xs">
                        Quality © {{ date('Y') }} - Todos los derechos reservados
                    </p>
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>