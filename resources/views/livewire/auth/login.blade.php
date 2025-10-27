<div>
<?php
//Esto es un modal
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';
    
    public bool $remember = false;
    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        // Primero verificar si las credenciales son correctas
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], false)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Verificar estado de la cuenta después del login exitoso PERO ANTES de establecer la sesión
        if ($user->isSuspended()) {
            // Cerrar la sesión inmediatamente
            Auth::logout();
            
            // Preparar mensaje detallado de suspensión
            $message = 'Tu cuenta ha sido suspendida y no puedes iniciar sesión.';
            
            if ($user->suspended_until) {
                $message = 'Tu cuenta está suspendida hasta el ' . $user->suspended_until->format('d/m/Y H:i') . '.';
            } else {
                $message = 'Tu cuenta ha sido suspendida indefinidamente.';
            }

            if ($user->suspension_reason) {
                $message .= ' Motivo: ' . $user->suspension_reason;
            }

            $message .= ' Contacta al administrador para más información.';

            // Lanzar error de validación con el mensaje completo
            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        // Solo si pasa todas las verificaciones, establecer la sesión permanente
        Auth::logout(); // Cerrar la sesión temporal
        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }


        // Actualizar último login
        Auth::user()->updateLastLogin();

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Redirigir a la página de inicio después del login
        $this->redirectIntended(default: '/', navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
    
    /**
     * Toggle password visibility.
     */
    public function togglePassword(): void
    {
        $this->showPassword = !$this->showPassword;
    }
}; ?>

<div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl p-0 md:p-8 w-full max-w-md mx-auto">
    <!-- Encabezado del formulario -->
    <div class="text-center mb-4">
        <h1 class="text-lg font-bold text-white mb-1">Bienvenido de vuelta</h1>
        <p class="text-slate-200 text-xs">Accede a tu cuenta para continuar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" wire:submit="login" class="space-y-3">
    <!-- Email Address -->
    <div class="space-y-1">
        <label for="login_email" class="block text-xs font-semibold text-white">
            Correo Electrónico
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
            </div>
            <input
                wire:model="email"
                type="email"
                id="login_email"
                required
                autofocus
                autocomplete="email"
                placeholder="tu@email.com"
                class="w-full pl-10 pr-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
            />
        </div>
        @error('email') 
            <div class="mt-2">
                @if(str_contains($message, 'suspendida'))
                    <!-- Modal de Suspensión -->
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-semibold text-red-800">Cuenta Suspendida</h3>
                                @if(str_contains($message, 'hasta'))
                                    <p class="text-sm text-red-700 mt-1">Duración: {{ Str::match('/hasta el [^\.]+\./', $message) }}</p>
                                @endif
                                @if(str_contains($message, 'Motivo'))
                                    <p class="text-sm text-red-700 mt-1">Motivo: {{ Str::match('/Motivo: ([^\.]+)(?= Contacta)/', $message) }}</p>
                                @endif
                                <p class="text-sm text-red-700 mt-1">Contacta al administrador para más información.</p>
                            </div>
                        </div>
                    </div>
                @elseif(str_contains($message, 'verificar tu correo electrónico'))
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 space-y-2 text-center">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                        <p class="text-yellow-800 text-sm font-semibold">Debes verificar tu correo electrónico antes de acceder.</p>
                        <p class="text-yellow-700 text-xs">Te hemos enviado un código de verificación a tu correo. Ingresa ese código en el siguiente paso para activar tu cuenta.</p>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-verification-modal'))" class="mt-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg text-xs font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all">Verificar mi correo</button>
                    </div>
                @else
                    <p class="text-red-300 text-sm">{{ $message }}</p>
                @endif
            </div>
        @enderror
    </div>

    <!-- Password -->
    <div class="space-y-1">
        <label for="login_password" class="block text-xs font-semibold text-white">
            Contraseña
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <input
                wire:model="password"
                type="{{ $showPassword ? 'text' : 'password' }}"
                id="login_password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full pl-10 pr-12 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
            />
            <button type="button" 
                    wire:click="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-white transition-colors duration-200">
                @if($showPassword)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                @endif
            </button>
        </div>
        @error('password')
            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me and Forgot Password -->
    <div class="flex items-center justify-between py-2">
        <div class="flex items-center">
            <input
                id="remember"
                name="remember"
                type="checkbox"
                wire:model="remember"
                class="h-3 w-3 rounded border-white/20 bg-black/30 text-slate-400 focus:ring-cyan-400 focus:ring-2 transition-colors"
            />
            <label for="remember" class="ml-2 block text-xs text-slate-300">
                Recordarme
            </label>
        </div>
        <a href="javascript:void(0)" onclick="Livewire.dispatch('openForgotPasswordModal')" class="text-xs text-slate-300 hover:text-white transition-colors">
            ¿Olvidaste tu contraseña?
        </a>
    </div>

    <!-- Sign In Button -->
    <div class="space-y-2">
        <!-- Botón de Login -->
                    <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-cyan-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-300 group-hover:text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 1 1 6 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Iniciar Sesión
                </button>
    </div>
</form>

<!-- Separador -->
    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/20"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="px-2 text-slate-300 bg-black/70 rounded">O continúa con</span>
        </div>
    </div>

<!-- Botón de Google rediseñado -->
<div class="space-y-4">
                    <button type="button" onclick="window.location='{{ route('auth.google') }}'"
                    class="group relative w-full flex justify-center py-2 px-4 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-600" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </span>
                    Continuar con Google
                </button>
</div>

<!-- Link de registro -->
@if (Route::has('register'))
    <div class="text-center mt-3">
        <p class="text-slate-300 text-xs">
            ¿No tienes una cuenta? 
            <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = false; $store.registerModal.open = true" class="text-slate-300 hover:text-white font-semibold transition-colors duration-300">
                Regístrate aquí
            </a>
        </p>
    </div>
@endif

</div>

@livewire('forgot-password-modal')
</div>
