<div>
    <!-- Modal Backdrop -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-900/75 backdrop-blur-sm" wire:click="closeModal"></div>
                
                <!-- Modal panel -->
                <div class="relative inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl border border-slate-200/50">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Mi Perfil</h2>
                                <p class="text-sm text-slate-600">Actualice su información personal</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Mensajes de estado -->
                    @if($showSuccessMessage)
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">¡Datos guardados correctamente!</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-blue-800 font-medium">{{ session('info') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('verification-success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg" id="verification-success-message">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('verification-success') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Form -->
                    <form wire:submit.prevent="updateProfile">
                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre Completo
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           wire:model="name" 
                                           id="name"
                                           class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900 placeholder-slate-400 text-sm"
                                           placeholder="Ingrese su nombre completo">
                                </div>
                                @error('name') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <!-- Email (solo lectura) -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                    Correo Electrónico
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                    <input type="email" 
                                           value="{{ $email }}"
                                           id="email"
                                           readonly
                                           class="block w-full pl-10 pr-16 py-3 border border-slate-200 rounded-lg bg-slate-50/80 backdrop-blur-sm text-slate-600 text-sm cursor-not-allowed">
                                    
                                    <!-- Indicador de verificación -->
                                    @php
                                        $user = Auth::user();
                                        $isVerified = !is_null($user->email_verified_at);
                                    @endphp
                                    
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        @if($isVerified)
                                            <div class="flex items-center space-x-1 bg-green-100 text-green-800 px-2 py-1 rounded-md text-xs font-medium">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Verificado</span>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-1 bg-orange-100 text-orange-800 px-2 py-1 rounded-md text-xs font-medium">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Sin verificar</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="mt-1 text-xs">
                                    @if($isVerified)
                                        <p class="text-green-600">✓ Tu correo electrónico está verificado.</p>
                                    @else
                                        <div class="space-y-1">
                                            <p class="text-orange-600">⚠️ Tu correo electrónico no está verificado.</p>
                                            <button type="button" 
                                                    wire:click="resendVerification"
                                                    class="text-blue-600 hover:text-blue-800 underline font-medium">
                                                Reenviar correo de verificación
                                            </button>
                                        </div>
                                    @endif
                                    <p class="text-slate-500 mt-1">El correo electrónico no se puede cambiar por seguridad.</p>
                                </div>
                            </div>
                            
                            <!-- Teléfono -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                    Teléfono
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                    </div>
                                    <input type="tel" 
                                           wire:model="phone" 
                                           id="phone"
                                           class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900 placeholder-slate-400 text-sm"
                                           placeholder="Ej: 0987654321">
                                </div>
                                @error('phone') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <!-- Dirección -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                                    Dirección
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           wire:model="address" 
                                           id="address"
                                           class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900 placeholder-slate-400 text-sm"
                                           placeholder="Ingrese su dirección completa">
                                </div>
                                @error('address') 
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            
                            <!-- Provincia y Ciudad en dos columnas -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Provincia -->
                                <div>
                                    <label for="province" class="block text-sm font-medium text-slate-700 mb-2">
                                        Provincia
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <select wire:model.live="province" 
                                                id="province"
                                                class="block w-full pl-10 pr-8 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900 text-sm">
                                            <option value="">Seleccione una provincia</option>
                                            @foreach(array_keys(config('ecuador.provinces')) as $provinceOption)
                                                <option value="{{ $provinceOption }}">{{ $provinceOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('province') 
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>
                                
                                <!-- Ciudad -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-slate-700 mb-2">
                                        Ciudad
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <select wire:model="city" 
                                                id="city"
                                                {{ empty($cities) ? 'disabled' : '' }}
                                                class="block w-full pl-10 pr-8 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900 text-sm {{ empty($cities) ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                            <option value="">{{ empty($cities) ? 'Seleccione provincia primero' : 'Seleccione una ciudad' }}</option>
                                            @foreach($cities as $cityOption)
                                                <option value="{{ $cityOption }}">{{ $cityOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('city') 
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 focus:ring-2 focus:ring-slate-500 focus:border-transparent transition-all duration-200">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-slate-600 to-slate-700 rounded-lg hover:from-slate-700 hover:to-slate-800 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                                <div wire:loading wire:target="updateProfile" class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </div>
                                <span wire:loading.remove wire:target="updateProfile">
                                    Guardar Cambios
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-modal-after-delay', () => {
                setTimeout(() => {
                    Livewire.dispatch('closeProfileModal');
                }, 2000); // 2 segundos
            });
            
            Livewire.on('auto-close-message', (data) => {
                setTimeout(() => {
                    const messageElement = document.getElementById(data[0].type + '-message');
                    if (messageElement) {
                        messageElement.style.transition = 'opacity 0.5s ease-out';
                        messageElement.style.opacity = '0';
                        setTimeout(() => {
                            messageElement.remove();
                        }, 500);
                    }
                }, 2000); // 2 segundos
            });
        });
    </script>
</div>