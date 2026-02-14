<div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-auto overflow-hidden flex flex-col relative border border-slate-100">
    <!-- Header Minimalista -->
    <div class="bg-white px-6 py-4 flex justify-between items-center border-b border-slate-100">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-slate-50 border border-slate-100">
                <svg class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-800">Crear Nuevo Usuario</h1>
        </div>
        <button type="button" class="text-slate-400 hover:text-slate-600 rounded-lg p-1 transition-colors focus:outline-none" wire:click="$dispatch('close-modal')">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="p-6 overflow-y-auto flex-1">
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl text-red-700 text-sm">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ __($error) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($showConfirmModal)
            <div class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-900/20 backdrop-blur-sm"
                x-data
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-[80] border border-slate-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                    
                    <!-- Icono de confirmación -->
                    <div class="flex items-center justify-center mb-6">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-slate-50 border border-slate-100">
                            <svg class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Título -->
                    <h3 class="text-xl font-bold text-slate-900 text-center mb-1">
                        Confirmar creación
                    </h3>

                    <!-- Descripción -->
                    <p class="text-slate-500 text-center text-sm mb-6">
                        Se creará un nuevo usuario con los siguientes datos:
                    </p>

                    <!-- Datos del usuario -->
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6 space-y-3">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Nombre</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
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
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Rol</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $role === 'owner' ? 'Propietario' : 'Vendedor' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3">
                        <button type="button" 
                            wire:click="cancelCreateUser" 
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition-all">
                            Cancelar
                        </button>
                        <button type="button" 
                            wire:click="confirmCreateUser" 
                            class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 shadow-sm font-medium transition-all cursor-pointer">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-5">
            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                <input type="text" id="name" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400" wire:model.defer="name" required placeholder="Ej. Juan Pérez">
                @error('name')
                    <span class="text-red-500 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                <input type="email" id="email" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400" wire:model.defer="email" required placeholder="ejemplo@correo.com">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                <input type="password" id="password" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all placeholder-slate-400" wire:model.defer="password" required placeholder="••••••••">
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Rol</label>
                <div class="relative">
                    <select id="role" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition-all appearance-none" wire:model.defer="role" required>
                        @if(auth()->user()->isAdmin())
                            <option value="owner">Propietario</option>
                        @endif
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('role')
                    <span class="text-red-500 text-xs mt-1 block">{{ __($message) }}</span>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 px-6 rounded-lg shadow-sm transition-all flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>