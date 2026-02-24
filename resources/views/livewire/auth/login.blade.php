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
    public string $intended = '';
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

        // Guardar la intended URL si no está ya en sesión
        if (!session()->has('url.intended')) {
            $intended = $this->intended ?: url()->previous();
            // Evitar que la intended sea la ruta de login o register
            if (!str_contains($intended, 'login') && !str_contains($intended, 'register')) {
                session(['url.intended' => $intended]);
            }
        }

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
            Auth::logout();
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

        Auth::user()->updateLastLogin();
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Redirigir a la intended URL o home
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
        <input type="hidden" wire:model.defer="intended" value="{{ url()->full() }}" />
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
        <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = false; $store.forgotPasswordModal.open = true" class="text-xs text-slate-300 hover:text-white transition-colors">
            ¿Olvidaste tu contraseña?
        </a>
    </div>

    <!-- Sign In Button -->
    <div class="space-y-2">
        <!-- Botón de Login -->
                    <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-cyan-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl cursor-pointer">
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
                    class="group relative w-full flex justify-center py-2 px-4 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-lg cursor-pointer">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="#F2F2F2"/>
                            <path d="M19.6 12.2c0-.82-.1-1.42-.25-2.05H12v3.72h4.3c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z" fill="#4285F4"/>
                            <path d="M13.46 15.13c-.83.8-2.18 1.9-4.46 1.9-3.37 0-6.18-2.74-6.18-6.12 0-3.38 2.81-6.12 6.18-6.12 1.88 0 3.14.61 4.33 1.75l2.45-2.41C16.27 2.5 14.21 1.5 12 1.5 6.48 1.5 2 6.01 2 11.5s4.48 10 10 10c2.7 0 4.76-.88 6.3-2.64l-2.13-1.73z" fill="#34A853"/>
                            <path d="M12 22c2.1 0 3.92-.64 5.23-1.82l-3.16-2.45c-.91.64-2.05 1.08-3.39 1.08-2.27 0-4.26-1.48-4.99-3.64H3.42v2.5C4.74 20.33 8.15 22 12 22z" fill="#EA4335"/>
                            <path d="M7.07 14.17c-.18-.64-.27-1.31-.27-2s.1-1.36.26-2v-2.5H3.42C2.85 7.87 2.5 9.61 2.5 11.5s.35 3.63.92 5.17l3.16-2.5z" fill="#FBBC04"/>
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


</div>
