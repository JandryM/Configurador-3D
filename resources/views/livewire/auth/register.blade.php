<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth.quality')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    // Nuevos campos
    public string $phone = '';
    public string $address = '';
    public string $province = '';
    public string $city = '';
    public array $cities = [];
    // Propiedades para mostrar contraseñas
    public bool $showPassword = false;
    public bool $showPasswordConfirmation = false;

    public function mount(): void
    {
        // Inicializar array de ciudades vacío
        $this->cities = [];
    }

    public function updatedProvince($value): void
    {
        if (!empty($value)) {
            $this->cities = config('ecuador.provinces')[$value] ?? [];
            $this->city = ''; // Resetear ciudad cuando cambia la provincia
        } else {
            $this->cities = [];
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            // Validación para los nuevos campos
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'in:' . implode(',', array_keys(config('ecuador.provinces')))],
            'city' => ['required', 'string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // Redirigir al login después del registro
        $this->redirect(route('login'), navigate: true);
    }
    
    /**
     * Toggle password visibility.
     */
    public function togglePassword(): void
    {
        $this->showPassword = !$this->showPassword;
    }
    
    /**
     * Toggle password confirmation visibility.
     */
    public function togglePasswordConfirmation(): void
    {
        $this->showPasswordConfirmation = !$this->showPasswordConfirmation;
    }
}; ?>

<div>
    <!-- Encabezado del formulario -->
    <div class="text-center mb-3">
        <h1 class="text-base font-bold text-white mb-1">Crear una cuenta</h1>
        <p class="text-slate-200 text-xs">Ingresa tus datos para crear tu cuenta</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" wire:submit="register" class="space-y-4">
        <!-- Layout de dos columnas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            
            <!-- Columna Izquierda: Información Personal -->
            <div class="space-y-2">
                <h3 class="text-xs font-semibold text-slate-200 border-b border-white/20 pb-1">
                    Información Personal
                </h3>
                
                <!-- Name -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-semibold text-white">
                        Nombre Completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="name"
                            type="text"
                            id="name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Tu nombre completo"
                            class="w-full pl-10 pr-4 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                    </div>
                    @error('name')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-semibold text-white">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="email"
                            type="email"
                            id="email"
                            required
                            autocomplete="email"
                            placeholder="tu@email.com"
                            class="w-full pl-10 pr-4 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                    </div>
                    @error('email')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label for="phone" class="block text-xs font-semibold text-white">
                        Teléfono
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="phone"
                            type="tel"
                            id="phone"
                            required
                            autocomplete="tel"
                            placeholder="Tu número de teléfono"
                            class="w-full pl-10 pr-4 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                    </div>
                    @error('phone')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="space-y-1">
                    <label for="address" class="block text-xs font-semibold text-white">
                        Dirección
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="address"
                            type="text"
                            id="address"
                            required
                            autocomplete="street-address"
                            placeholder="Tu dirección"
                            class="w-full pl-10 pr-4 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                    </div>
                    @error('address')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Columna Derecha: Ubicación y Seguridad -->
            <div class="space-y-2">
                <h3 class="text-xs font-semibold text-slate-200 border-b border-white/20 pb-1">
                    Ubicación y Seguridad
                </h3>
                
                <!-- Province -->
                <div class="space-y-1">
                    <label for="province" class="block text-xs font-semibold text-white">Provincia</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <select
                            id="province"
                            wire:model.live="province"
                            class="w-full pl-10 pr-8 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300 appearance-none"
                            required
                        >
                            <option value="" class="text-slate-400 bg-slate-800">Selecciona una provincia</option>
                            @foreach (array_keys(config('ecuador.provinces')) as $provinceOption)
                                <option value="{{ $provinceOption }}" class="text-white bg-slate-800">{{ $provinceOption }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-300">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('province')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div class="space-y-1">
                    <label for="city" class="block text-xs font-semibold text-white">Ciudad</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <select
                            id="city"
                            wire:model="city"
                            class="w-full pl-10 pr-8 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300 appearance-none {{ empty($cities) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            required
                            {{ empty($cities) ? 'disabled' : '' }}
                        >
                            <option value="" class="text-slate-400 bg-slate-800">{{ empty($cities) ? 'Primero selecciona una provincia' : 'Selecciona una ciudad' }}</option>
                            @foreach ($cities as $cityOption)
                                <option value="{{ $cityOption }}" class="text-white bg-slate-800">{{ $cityOption }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-300">
                            <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('city')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="block text-xs font-semibold text-white">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="password"
                            type="{{ $showPassword ? 'text' : 'password' }}"
                            id="password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                        <button type="button" 
                                wire:click="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-white transition-colors duration-200">
                            @if($showPassword)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            @endif
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-xs font-semibold text-white">
                        Confirmar Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 616 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="password_confirmation"
                            type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                            id="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-1.5 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg text-white placeholder-slate-300 focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 focus:outline-none transition-all duration-300"
                        />
                        <button type="button" 
                                wire:click="togglePasswordConfirmation"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-white transition-colors duration-200">
                            @if($showPasswordConfirmation)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            @endif
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Botón de registro -->
        <div class="flex justify-center pt-1">
            <button type="submit" 
                class="w-full max-w-md py-1.5 px-4 bg-gradient-to-r from-slate-600 to-gray-600 hover:from-slate-700 hover:to-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                Crear cuenta
            </button>
        </div>
    </form>

    <!-- Separador -->
    <div class="relative my-2">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/20"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="px-2 text-slate-300 bg-transparent">O continúa con</span>
        </div>
    </div>

    <!-- Botón de Google -->
    <div class="flex justify-center">
        <button type="button" onclick="window.location='{{ route('auth.google') }}'"
            class="w-full max-w-md flex items-center justify-center py-1.5 px-4 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-lg">
            <svg class="h-4 w-4 mr-2 text-slate-600" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuar con Google
        </button>
    </div>

    <!-- Link de login -->
    <div class="text-center mt-2">
        <p class="text-slate-300 text-xs">
            ¿Ya tienes una cuenta? 
            <a href="{{ route('login') }}" wire:navigate class="text-slate-300 hover:text-white font-semibold transition-colors duration-300">
                Inicia sesión aquí
            </a>
        </p>
    </div>

    <!-- Volver al inicio -->
    <div class="text-center mt-1">
        <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center text-slate-300 hover:text-white transition-colors duration-300 text-xs">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Volver al inicio
        </a>
    </div>
</div>
