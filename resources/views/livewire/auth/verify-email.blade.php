<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth.quality')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $user = Auth::user();
            $defaultRoute = $user->isAdmin() ? route('admin.dashboard', absolute: false) : route('dashboard', absolute: false);
            $this->redirectIntended(default: $defaultRoute, navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Encabezado del formulario -->
    <div class="text-center mb-4">
        <h1 class="text-lg font-bold text-white mb-1">Verificar tu correo electrónico</h1>
        <p class="text-slate-200 text-xs">Te hemos enviado un enlace de verificación</p>
    </div>

    <!-- Contenido principal -->
    <div class="space-y-4">
        <!-- Mensaje de instrucciones -->
        <div class="text-center p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
            <div class="flex items-center justify-center mb-3">
                <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
            </div>
            <p class="text-slate-200 text-sm">
                Por favor, verifica tu dirección de correo electrónico haciendo clic en el enlace que acabamos de enviarte.
            </p>
        </div>

        <!-- Mensaje de éxito (cuando se reenvía el email) -->
        @if (session('status') == 'verification-link-sent')
            <div class="text-center p-3 bg-green-500/20 backdrop-blur-sm rounded-lg border border-green-400/30">
                <div class="flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-green-200 text-xs">
                    Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                </p>
            </div>
        @endif

        <!-- Botones de acción -->
        <div class="space-y-3">
            <!-- Botón reenviar email -->
            <button wire:click="sendVerification" 
                class="w-full py-2 px-4 bg-gradient-to-r from-slate-600 to-gray-600 hover:from-slate-700 hover:to-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                </svg>
                Reenviar correo de verificación
            </button>

            <!-- Separador -->
            <div class="relative my-3">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/20"></div>
                </div>
            </div>

            <!-- Botón cerrar sesión -->
            <button wire:click="logout" 
                class="w-full py-2 px-4 border border-slate-300 text-sm font-medium rounded-lg text-slate-300 bg-transparent hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                </svg>
                Cerrar sesión
            </button>
        </div>
    </div>

    <!-- Volver al inicio -->
    <div class="text-center mt-4">
        <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center text-slate-300 hover:text-white transition-colors duration-300 text-xs">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Volver al inicio
        </a>
    </div>
</div>
