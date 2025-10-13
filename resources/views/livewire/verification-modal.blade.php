<div>
    @if($showModal)
        <!-- Overlay del modal -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1100] flex items-center justify-center p-4"
             wire:click="closeModal">
            
            <!-- Contenido del modal -->
            <div class="bg-gradient-to-br from-slate-800/90 to-gray-900/90 backdrop-blur-xl rounded-2xl border border-white/20 w-full max-w-md shadow-2xl"
                 wire:click.stop>
                
                <!-- Encabezado del modal -->
                <div class="px-6 py-4 border-b border-white/10">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white">Verificar correo electrónico</h2>
                        <button wire:click="closeModal" 
                                class="text-slate-400 hover:text-white transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Cuerpo del modal -->
                <div class="px-6 py-6 space-y-4">
                    <!-- Mensaje de éxito -->
                    @if($showSuccessMessage)
                        <div class="p-4 bg-green-500/20 backdrop-blur-sm rounded-lg border border-green-400/30">
                            <div class="flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-green-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-green-200 font-medium">¡Listo!</span>
                            </div>
                            <p class="text-green-200 text-sm text-center">
                                {{ $successMessage }}
                            </p>
                        </div>
                    @else
                        <!-- Mensaje de instrucciones -->
                        <div class="text-center p-4 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20">
                        <div class="flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-200 text-sm">
                            Te enviaremos un enlace de verificación a tu correo electrónico.
                        </p>
                        @auth
                            <p class="text-slate-300 text-xs mt-2">
                                {{ Auth::user()->email }}
                            </p>
                        @endauth
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    @if(!$showSuccessMessage)
                        <div class="space-y-3">
                        <!-- Botón reenviar email -->
                        <button wire:click="sendVerification" 
                                class="w-full py-3 px-4 bg-gradient-to-r from-slate-600 to-gray-600 hover:from-slate-700 hover:to-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                            </svg>
                            Enviar enlace de verificación
                        </button>

                        <!-- Botón cancelar -->
                        <button wire:click="closeModal" 
                                class="w-full py-3 px-4 border border-slate-300 text-sm font-medium rounded-lg text-slate-300 bg-transparent hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200 flex items-center justify-center">
                                Cancelar
                        </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-verification-modal-after-delay', () => {
                setTimeout(() => {
                    @this.closeModal();
                }, 2000); // 2 segundos
            });
        });
    </script>
</div>