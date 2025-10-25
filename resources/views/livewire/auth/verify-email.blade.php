

<div x-data="{ open: @entangle('showModal') }" x-show="open" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-black/40 z-50" style="display: none;">
    <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl p-0 md:p-8 w-full max-w-md relative overflow-hidden text-white">
        <button @click="open = false; $wire.closeModal()" class="absolute top-3 right-3 text-slate-400 text-3xl font-bold focus:outline-none">&times;</button>

        <!-- Encabezado del formulario -->
        <div class="text-center mb-4">
            <h1 class="text-lg font-bold text-white mb-1">Verificar tu correo electrónico</h1>
            <p class="text-slate-200 text-xs">Solicita un código de verificación para tu correo electrónico</p>
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
                    Por favor, ingresa el código de verificación que hemos enviado a tu correo electrónico.
                </p>
            </div>

            <!-- Mensaje de éxito (cuando se reenvía el email o ya está verificado) -->
            @if ($showSuccessMessage)
                <div class="text-center p-3 bg-green-500/20 backdrop-blur-sm rounded-lg border border-green-400/30">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-green-200 text-xs">
                        {{ $successMessage }}
                    </p>
                </div>
            @endif

            <!-- Botones de acción -->
            <div class="space-y-3">
                @if(!$codeSent)
                    <!-- Botón enviar código -->
                    <button wire:click="sendVerification"
                        class="w-full py-2 px-4 bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 text-white text-sm font-medium rounded-lg border border-cyan-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                        </svg>
                        Enviar código de verificación
                    </button>
                @else
                    <!-- Input para código y botón de verificar -->
                    <form wire:submit.prevent="verifyCode" class="space-y-2">
                        <input type="text" wire:model="input_code" wire:keyup="checkInputCode" maxlength="6" minlength="6" pattern="[0-9]{6}"
                            class="w-full py-2.5 px-4 rounded-xl border border-white/20 bg-black/30 text-white placeholder-slate-300 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none text-center text-lg tracking-widest transition-all duration-300"
                            placeholder="Ingresa el código de 6 dígitos" autocomplete="one-time-code" required />
                        @error('input_code')
                            <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <!-- El botón de verificar ya no es necesario, la verificación es automática -->
                    </form>
                    <button wire:click="sendVerification" type="button"
                        class="w-full py-2 px-4 mt-2 border border-white/20 text-sm font-medium rounded-lg text-white bg-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200 flex items-center justify-center">
                        Reenviar código
                    </button>
                @endif

                <!-- Separador -->
                <div class="relative my-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                </div>

                <!-- Botón cerrar sesión -->
                <button wire:click="logout"
                    class="w-full py-2 px-4 border border-white/20 text-sm font-medium rounded-lg text-white bg-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200 flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                    </svg>
                    Cerrar sesión
                </button>
            </div>
        </div>

    </div>
    </div>
</div>
