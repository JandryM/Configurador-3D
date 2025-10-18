<div>
    @if($show)
    <!-- Modal de Suspensión -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         x-data="{ open: true }"
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Header del Modal -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Cuenta Suspendida</h3>
                        <p class="text-red-100 text-sm">Tu acceso ha sido restringido</p>
                    </div>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div class="px-6 py-4">
                <div class="mb-4">
                    <p class="text-gray-700 mb-3">{{ $suspensionMessage }}</p>
                    
                    @if($suspensionReason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                        <p class="text-sm font-medium text-red-800 mb-1">Motivo de la suspensión:</p>
                        <p class="text-sm text-red-700">{{ $suspensionReason }}</p>
                    </div>
                    @endif
                    
                    @if($suspendedUntil)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                        <p class="text-sm font-medium text-yellow-800 mb-1">Suspensión hasta:</p>
                        <p class="text-sm text-yellow-700">{{ $suspendedUntil }}</p>
                    </div>
                    @endif
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-700">
                            Para más información o si crees que esto es un error, contacta al administrador del sistema.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button wire:click="logout"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Entendido - Cerrar Sesión
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
