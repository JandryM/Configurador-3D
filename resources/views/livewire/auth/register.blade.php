<?php
//Esto tambien es un modal jeje
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Volt\Component;

new class extends Component {
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
        $validated['oauth_provider'] = 'local';

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

<div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl p-0 md:p-8 w-full max-w-2xl mx-auto">
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
                    <label for="register_name" class="block text-xs font-semibold text-white">
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
                            id="register_name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Tu nombre completo"
                            class="w-full pl-10 pr-4 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
                        />
                    </div>
                    @error('name')
                        <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="register_email" class="block text-xs font-semibold text-white">
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
                            id="register_email"
                            required
                            autocomplete="email"
                            placeholder="tu@email.com"
                            class="w-full pl-10 pr-4 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
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
                            class="w-full pl-10 pr-4 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
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
                            class="w-full pl-10 pr-4 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
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
                            class="w-full pl-10 pr-8 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300 appearance-none"
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
                            class="w-full pl-10 pr-8 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300 appearance-none {{ empty($cities) ? 'opacity-50 cursor-not-allowed' : '' }}"
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
                    <label for="register_password" class="block text-xs font-semibold text-white">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 1 1 6 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="password"
                            type="{{ $showPassword ? 'text' : 'password' }}"
                            id="register_password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
                    <label for="register_password_confirmation" class="block text-xs font-semibold text-white">
                        Confirmar Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 1 1 6 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="password_confirmation"
                            type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                            id="register_password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-1.5 bg-black/30 border border-white/20 rounded-lg text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
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
                class="w-full max-w-md py-1.5 px-4 bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl cursor-pointer">
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
            <span class="px-2 text-slate-300 bg-black/70 rounded">O continúa con</span>
        </div>
    </div>

    <!-- Botón de Google -->
    <div class="flex justify-center">
        <button type="button" onclick="window.location='{{ route('auth.google') }}'"
            class="w-full max-w-md flex items-center justify-center py-1.5 px-4 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-lg cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="#F2F2F2"/>
            <path d="M19.6 12.2c0-.82-.1-1.42-.25-2.05H12v3.72h4.3c-.15.96-.74 2.31-2.04 3.22v2.45h3.16c1.89-1.73 2.98-4.3 2.98-7.34z" fill="#4285F4"/>
            <path d="M13.46 15.13c-.83.8-2.18 1.9-4.46 1.9-3.37 0-6.18-2.74-6.18-6.12 0-3.38 2.81-6.12 6.18-6.12 1.88 0 3.14.61 4.33 1.75l2.45-2.41C16.27 2.5 14.21 1.5 12 1.5 6.48 1.5 2 6.01 2 11.5s4.48 10 10 10c2.7 0 4.76-.88 6.3-2.64l-2.13-1.73z" fill="#34A853"/>
            <path d="M12 22c2.1 0 3.92-.64 5.23-1.82l-3.16-2.45c-.91.64-2.05 1.08-3.39 1.08-2.27 0-4.26-1.48-4.99-3.64H3.42v2.5C4.74 20.33 8.15 22 12 22z" fill="#EA4335"/>
            <path d="M7.07 14.17c-.18-.64-.27-1.31-.27-2s.1-1.36.26-2v-2.5H3.42C2.85 7.87 2.5 9.61 2.5 11.5s.35 3.63.92 5.17l3.16-2.5z" fill="#FBBC04"/>
            </svg>
            Continuar con Google
        </button>
    </div>

    <!-- Link de login -->
    <div class="text-center mt-2">
        <p class="text-slate-300 text-xs">
            ¿Ya tienes una cuenta? 
            <a href="javascript:void(0)" @click.prevent="$store.loginModal.open = true; $store.registerModal.open = false" class="text-slate-300 hover:text-white font-semibold transition-colors duration-300">
                Inicia sesión aquí
            </a>
        </p>
    </div>

</div>
