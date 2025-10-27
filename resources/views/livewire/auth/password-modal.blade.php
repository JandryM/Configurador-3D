<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center h-screen px-4 text-center">
                <!-- Fondo oscuro -->
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                <!-- Panel del modal -->
                <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl p-0 md:p-8 w-full max-w-md mx-auto text-white overflow-hidden text-left align-middle transition-all transform">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-slate-600 to-slate-700 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Cambiar Contraseña</h2>
                                <p class="text-sm text-white/80">Actualiza tu contraseña de acceso</p>
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
                                <span class="text-green-800 font-medium">¡Contraseña actualizada correctamente!</span>
                            </div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-red-800 font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Formulario -->
                    <form wire:submit.prevent="updatePassword">
                        <div class="space-y-4">
                            <!-- Contraseña actual -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-white mb-2">Contraseña actual</label>
                                <div class="relative">
                                    <input type="{{ $showCurrentPassword ? 'text' : 'password' }}" wire:model="current_password" id="current_password" class="w-full pr-10 pl-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300" placeholder="Ingresa tu contraseña actual">
                                    <button type="button" wire:click="toggleCurrentPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-cyan-400">
                                        @if($showCurrentPassword)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                                @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <!-- Nueva contraseña -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-white mb-2">Nueva contraseña</label>
                                <div class="relative">
                                    <input type="{{ $showNewPassword ? 'text' : 'password' }}" wire:model="password" id="password" class="w-full pr-10 pl-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300" placeholder="Ingresa la nueva contraseña">
                                    <button type="button" wire:click="toggleNewPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-cyan-400">
                                        @if($showNewPassword)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <!-- Confirmar nueva contraseña -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">Confirmar nueva contraseña</label>
                                <div class="relative">
                                    <input type="{{ $showConfirmPassword ? 'text' : 'password' }}" wire:model="password_confirmation" id="password_confirmation" class="w-full pr-10 pl-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300" placeholder="Confirma la nueva contraseña">
                                    <button type="button" wire:click="toggleConfirmPassword" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-cyan-400">
                                        @if($showConfirmPassword)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268-2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                                @error('password_confirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-white bg-transparent border border-white/20 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-200 hover:bg-white hover:text-black hover:scale-[1.02] hover:shadow-lg">Cancelar</button>
                            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-cyan-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                <div wire:loading wire:target="updatePassword" class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Guardando...
                                </div>
                                <span wire:loading.remove wire:target="updatePassword">Guardar Cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>