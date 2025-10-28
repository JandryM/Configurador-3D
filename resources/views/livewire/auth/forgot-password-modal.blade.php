<div>
    
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl p-0 md:p-8 w-full max-w-md mx-auto text-white overflow-hidden text-left align-middle transition-all transform">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                @if($step === 1)
                                    <h2 class="text-xl font-bold text-white">Recuperar Contraseña</h2>
                                    <p class="text-sm text-white/80">Te enviaremos un código por correo para restablecer tu contraseña</p>
                                @elseif($step === 2)
                                    <h2 class="text-xl font-bold text-white">Verifica tu código</h2>
                                    <p class="text-sm text-white/80">Ingresa el código que recibiste en tu correo.</p>
                                @elseif($step === 3)
                                    <h2 class="text-xl font-bold text-white">Nueva contraseña</h2>
                                    <p class="text-sm text-white/80">Ingresa y confirma tu nueva contraseña.</p>
                                @elseif($step === 4)
                                    <h2 class="text-xl font-bold text-white">¡Listo!</h2>
                                    <p class="text-sm text-white/80">Tu contraseña fue restablecida correctamente.</p>
                                @endif
                            </div>
                        </div>
                        <button wire:click="closeModal" @click="$store.forgotPasswordModal.open = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    @if($showSuccessMessage && $step === 1)
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">¡Código enviado! Revisa tu correo.</span>
                            </div>
                        </div>
                    @endif
                    @if($showErrorMessage)
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-red-800 font-medium">{{ $errorMessage }}</span>
                            </div>
                        </div>
                    @endif
                    @if($step === 1)
                        <form wire:submit.prevent="sendResetCode">
                            <div class="space-y-4">
                                <div>
                                    <label for="forgot_email" class="block text-sm font-medium text-white mb-2">Correo electrónico</label>
                                    <input type="email" wire:model="email" id="forgot_email" class="w-full pl-4 pr-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all duration-300" placeholder="tu@email.com">
                                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-white bg-transparent border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:bg-white hover:text-black hover:scale-[1.02] hover:shadow-lg">Cancelar</button>
                                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-blue-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                    <span>Enviar código</span>
                                </button>
                            </div>
                        </form>
                    @elseif($step === 2)
                        <form wire:submit.prevent="verifyCode">
                            <div class="space-y-4">
                                <div>
                                    <label for="reset_code" class="block text-sm font-medium text-white mb-2">Código recibido</label>
                                    <input type="text" wire:model="code" id="reset_code" maxlength="6" class="w-full pl-4 pr-4 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all duration-300" placeholder="Ingresa el código de 6 dígitos">
                                    @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-white bg-transparent border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:bg-white hover:text-black hover:scale-[1.02] hover:shadow-lg">Cancelar</button>
                                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-blue-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                    <span>Verificar código</span>
                                </button>
                            </div>
                        </form>
                    @elseif($step === 3)
                        <form wire:submit.prevent="resetPassword">
                            <div class="space-y-4">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-white mb-2">Nueva contraseña</label>
                                    <div class="relative">
                                        <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="new_password" id="new_password" class="w-full pl-4 pr-12 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all duration-300" placeholder="Nueva contraseña">
                                        <button type="button" wire:click="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-white transition-colors duration-200">
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
                                    @error('new_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-white mb-2">Confirmar contraseña</label>
                                    <div class="relative">
                                        <input type="{{ $showPasswordConfirmation ? 'text' : 'password' }}" wire:model="new_password_confirmation" id="new_password_confirmation" class="w-full pl-4 pr-12 py-2.5 bg-black/30 border border-white/20 rounded-xl text-white placeholder-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all duration-300" placeholder="Confirmar contraseña">
                                        <button type="button" wire:click="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-300 hover:text-white transition-colors duration-200">
                                            @if($showPasswordConfirmation)
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
                                    @error('new_password_confirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-slate-200">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-white bg-transparent border border-white/20 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent transition-all duration-200 hover:bg-white hover:text-black hover:scale-[1.02] hover:shadow-lg">Cancelar</button>
                                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-blue-600/40 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl">
                                    <span>Restablecer contraseña</span>
                                </button>
                            </div>
                        </form>
                    @elseif($step === 4)
                        <!-- Solo mostrar el mensaje de éxito y cerrar el modal automáticamente -->
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-800 font-medium">¡Contraseña restablecida correctamente!</span>
                            </div>
                        </div>
</div>


                    @endif
                </div>
            </div>
        </div>


</div>

<!-- Script para cerrar el modal automáticamente después de restablecer la contraseña -->
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('close-modal-after-delay', () => {
        setTimeout(() => {
            if(window.Alpine && Alpine.store('forgotPasswordModal')) {
                Alpine.store('forgotPasswordModal').open = false;
            }
        }, 1500); // 1.5 segundos
    });
});
</script>
