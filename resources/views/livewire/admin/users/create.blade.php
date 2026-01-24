<div class="bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full mx-auto overflow-hidden flex flex-col relative" style="background-color: #1e293b !important; color: #ffffff !important;">
    <!-- Header con gradiente -->
    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 px-6 py-4 flex justify-between items-center" style="background: linear-gradient(to right, #3b82f6, #06b6d4) !important;">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-cyan-400 to-indigo-500">
                <svg class="w-7 h-7 text-blue-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-2c0-2.66-5.33-4-8-4z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-white">Crear Nuevo Usuario</h1>
        </div>
        <button type="button" class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1 transition-colors text-2xl font-bold focus:outline-none" wire:click="$dispatch('close-modal')" style="color: #ffffff !important;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke: currentColor !important;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="p-6 overflow-y-auto flex-1 text-white">

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-900/30 border border-red-700 rounded-xl text-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-900/30 border border-green-700 rounded-xl text-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-900/30 border border-red-700 rounded-xl text-red-200">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if($showConfirmModal)
            <div class="fixed inset-0 z-[70] flex items-center justify-center p-4"
                style="background: rgba(0,0,0,0.5);"
                x-data
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80]"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                    
                    <!-- Icono de confirmación -->
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-cyan-100">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Título -->
                    <h3 class="text-xl font-bold text-slate-800 text-center mb-2">
                        Confirmar creación de usuario
                    </h3>

                    <!-- Descripción -->
                    <p class="text-slate-600 text-center mb-6">
                        Se creará un nuevo usuario con los siguientes datos:
                    </p>

                    <!-- Datos del usuario -->
                    <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl p-4 mb-6 space-y-3">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Nombre</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</p>
                                <p class="text-sm font-semibold text-slate-800 break-all">{{ $email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Rol</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role === 'owner' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $role === 'owner' ? 'Dueño' : 'Vendedor' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3">
                        <button type="button" 
                            wire:click="cancelCreateUser" 
                            class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition-all">
                            Cancelar
                        </button>
                        <button type="button" 
                            wire:click="confirmCreateUser" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-xl hover:shadow-lg font-medium transition-all">
                            Crear Usuario
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-200 mb-1" style="color: #e2e8f0 !important;">Nombre</label>
                <input type="text" id="name" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" style="background-color: #334155 !important; color: #ffffff !important; border-color: #475569 !important;" wire:model.defer="name" required>
                @error('name')
                    <span class="text-red-400 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-200 mb-1" style="color: #e2e8f0 !important;">Correo Electrónico</label>
                <input type="email" id="email" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" style="background-color: #334155 !important; color: #ffffff !important; border-color: #475569 !important;" wire:model.defer="email" required>
                @error('email')
                    <span class="text-red-400 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-200 mb-1" style="color: #e2e8f0 !important;">Contraseña</label>
                <input type="password" id="password" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" style="background-color: #334155 !important; color: #ffffff !important; border-color: #475569 !important;" wire:model.defer="password" required>
                @error('password')
                    <span class="text-red-400 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="role" class="block text-sm font-semibold text-slate-200 mb-1" style="color: #e2e8f0 !important;">Rol</label>
                <select id="role" class="w-full px-4 py-3 bg-slate-700 border border-slate-600 text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" style="background-color: #334155 !important; color: #ffffff !important; border-color: #475569 !important;" wire:model.defer="role" required>
                    @if(auth()->user()->isAdmin())
                        <option value="owner">Propietario</option>
                    @endif
                </select>
                @error('role')
                    <span class="text-red-400 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-xl shadow-md mt-4">Crear Usuario</button>
        </form>
    </div>
</div>