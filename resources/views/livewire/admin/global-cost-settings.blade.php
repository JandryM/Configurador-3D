<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Configuración de Costos Indirectos"
        description="Administra el porcentaje de costos indirectos aplicado a todos los productos"
        :show-button="true"
        button-text="Costos por Producto"
        button-link="{{ route('admin.product-cost-settings') }}"
        button-color="red"
    >
        <x-slot name="icon">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-slot name="buttonIcon">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
    </x-page-header>

    {{-- Mensajes de éxito/error --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Configuración actual --}}
    @php $role = auth()->user()->role ?? null; @endphp
    @if($currentSetting)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Configuración Vigente</h3>
                @if($role !== 'seller')
                <div class="flex items-center space-x-2">
                    @if($currentSetting->is_locked)
                        <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-medium border border-slate-200">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Bloqueada
                        </span>
                        @if($currentSetting->canBeEdited())
                            <button 
                                wire:click="enableEditMode"
                                class="inline-flex items-center px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition shadow-sm cursor-pointer"
                                title="Editar configuración (máx 2 veces en 1 minuto)"
                            >
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </button>
                        @endif
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium border border-green-200">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Activa
                        </span>
                    @endif
                </div>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Porcentaje de Costos Indirectos</label>
                    <div class="text-3xl font-bold text-slate-900">{{ $currentSetting->indirect_cost_percentage }}%</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Válido Desde</label>
                    <div class="text-lg font-semibold text-slate-900">
                        {{ $currentSetting->valid_from ? $currentSetting->valid_from->format('d/m/Y') : 'Sin límite' }}
                    </div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Válido Hasta</label>
                    <div class="text-lg font-semibold text-slate-900">
                        {{ $currentSetting->valid_until ? $currentSetting->valid_until->format('d/m/Y') : 'Sin límite' }}
                    </div>
                    @if($currentSetting->valid_until)
                        <div class="text-xs text-slate-500 mt-2">
                            @if($currentSetting->isExpired())
                                <span class="inline-flex items-center text-red-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Expirada
                                </span>
                                @if($role === 'seller')
                                    <div class="mt-2 text-xs text-red-700 font-semibold">
                                        Por favor, contacta a un administrador para que actualice la configuración.
                                    </div>
                                @endif
                            @elseif($currentSetting->isExpiringSoon())
                                <span class="inline-flex items-center text-yellow-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Expira pronto
                                </span>
                            @else
                                <span class="inline-flex items-center text-green-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Vigente
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                @if($role !== 'seller' && $currentSetting->canBeEdited())
                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                        <label class="block text-xs font-medium text-slate-600 mb-2">Intentos de Edición</label>
                        <div class="text-lg font-semibold text-slate-900">
                            {{ $currentSetting->edit_attempts }}/2
                        </div>
                        <div class="text-xs text-slate-500 mt-2">
                            @php
                                $timeLeft = $currentSetting->getTimeUntilEditWindowCloses();
                            @endphp
                            @if($timeLeft)
                                <span class="inline-flex items-center text-green-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Quedan {{ $timeLeft }}
                                </span>
                            @else
                                <span class="inline-flex items-center text-green-600 font-medium">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ 2 - $currentSetting->edit_attempts }} restantes
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Formulario para nueva configuración o edición --}}
    @if($role !== 'seller' && $isEditingExisting)
        {{-- Modo de Edición --}}
        <div class="bg-white rounded-xl shadow-sm border-2 border-yellow-400 p-6">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
                <div class="flex justify-between items-start">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Modo Edición:</strong> Estás editando la configuración vigente. 
                                Tienes <strong>{{ 2 - $currentSetting->edit_attempts }}</strong> ediciones restantes.
                                @php
                                    $timeLeft = $currentSetting->getTimeUntilEditWindowCloses();
                                @endphp
                                @if($timeLeft)
                                    <br><strong>Tiempo restante:</strong> {{ $timeLeft }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <button 
                        wire:click="cancelEdit"
                        class="text-yellow-700 hover:text-yellow-900 font-medium text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-slate-900 mb-4">
                Editar Configuración Actual
            </h3>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Porcentaje --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Porcentaje de Costos Indirectos *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="indirect_cost_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                placeholder="Ej: 15.00"
                                required
                            >
                            <span class="absolute right-3 top-2.5 text-slate-500">%</span>
                        </div>
                        @error('indirect_cost_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    {{-- Duración --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Duración de Vigencia
                        </label>
                        <div class="flex items-center space-x-2">
                            <select 
                                wire:model.live="duration_months"
                                class="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                @if($custom_duration) disabled @endif
                            >
                                <option value="0.5">15 días</option>
                                <option value="1">1 mes</option>
                                <option value="2">2 meses</option>
                                <option value="3">3 meses</option>
                                <option value="6">6 meses</option>
                                <option value="12">1 año</option>
                            </select>
                            <button 
                                type="button"
                                wire:click="toggleCustomDuration"
                                class="inline-flex items-center px-3 py-2.5 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition border border-slate-300"
                                title="Click para personalizar fechas"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $custom_duration ? 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' }}"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($custom_duration)
                                Modo personalizado: edita las fechas manualmente
                            @else
                                Modo automático: las fechas se calculan según la duración seleccionada
                            @endif
                        </p>
                    </div>

                    {{-- Información de fechas --}}
                    <div class="md:col-span-2 bg-slate-50 border border-slate-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Válido Desde
                                </label>
                                @if($custom_duration)
                                    <input 
                                        type="date" 
                                        wire:model.live="valid_from"
                                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                    >
                                    @error('valid_from') 
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                    @enderror
                                @else
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($valid_from)->format('d/m/Y') }}
                                    </div>
                                    <p class="text-xs text-slate-500">Fecha actual</p>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Válido Hasta
                                </label>
                                @if($custom_duration)
                                    <input 
                                        type="date" 
                                        wire:model="valid_until"
                                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                    >
                                    @error('valid_until') 
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                    @enderror
                                @else
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($valid_until)->format('d/m/Y') }}
                                    </div>
                                    <p class="text-xs text-slate-500">
                                        @php
                                            $from = \Carbon\Carbon::parse($valid_from);
                                            $to = \Carbon\Carbon::parse($valid_until);
                                            $days = $from->diffInDays($to);
                                        @endphp
                                        En {{ $days }} día{{ $days != 1 ? 's' : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button"
                        wire:click="cancelEdit"
                        class="px-6 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-lg border border-slate-300 transition"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg shadow-sm transition"
                    >
                        Actualizar Configuración
                    </button>
                </div>
            </form>
        </div>
    @elseif($role !== 'seller' && (!$currentSetting || ($currentSetting && ($currentSetting->isExpiringSoon() || $currentSetting->isExpired()))))
        {{-- Formulario solo cuando está próxima a expirar o ya expiró --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @if($currentSetting)
                {{-- Mensaje informativo --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Crear Nueva Configuración:</strong> 
                                @if($currentSetting->valid_until)
                                    La configuración actual 
                                    @if($currentSetting->isExpired())
                                        <span class="text-red-600 font-semibold">está expirada</span> y seguirá en uso hasta que crees una nueva.
                                    @elseif($currentSetting->isExpiringSoon())
                                        <span class="text-yellow-600 font-semibold">expira pronto</span> ({{ $currentSetting->valid_until->format('d/m/Y') }}). Puedes crear la nueva configuración ahora.
                                    @endif
                                @else
                                    Puedes crear una nueva configuración.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="text-lg font-semibold text-slate-900 mb-4">
                {{ $currentSetting ? 'Crear Nueva Configuración' : 'Configuración de Costos' }}
            </h3>

            <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Porcentaje --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Porcentaje de Costos Indirectos *
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            wire:model="indirect_cost_percentage"
                            step="0.01"
                            min="0"
                            max="100"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                            placeholder="Ej: 15.00"
                            required
                        >
                        <span class="absolute right-3 top-2.5 text-slate-500">%</span>
                    </div>
                    @error('indirect_cost_percentage') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                {{-- Duración --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Duración de Vigencia
                    </label>
                    <div class="flex items-center space-x-2">
                        <select 
                            wire:model.live="duration_months"
                            class="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                            @if($custom_duration) disabled @endif
                        >
                            <option value="0.5">15 días</option>
                            <option value="1">1 mes</option>
                            <option value="2">2 meses</option>
                            <option value="3">3 meses</option>
                            <option value="6">6 meses</option>
                            <option value="12">1 año</option>
                        </select>
                        <button 
                            type="button"
                            wire:click="toggleCustomDuration"
                            class="inline-flex items-center px-3 py-2.5 text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition border border-slate-300"
                            title="Click para personalizar fechas"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $custom_duration ? 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' }}"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        @if($custom_duration)
                            Modo personalizado: edita las fechas manualmente
                        @else
                            Modo automático: las fechas se calculan según la duración seleccionada
                        @endif
                    </p>
                </div>

                {{-- Información de fechas --}}
                <div class="md:col-span-2 bg-slate-50 border border-slate-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Válido Desde
                            </label>
                            @if($custom_duration)
                                <input 
                                    type="date" 
                                    wire:model.live="valid_from"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                >
                                @error('valid_from') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                            @else
                                <div class="text-lg font-semibold text-slate-900">
                                    {{ \Carbon\Carbon::parse($valid_from)->format('d/m/Y') }}
                                </div>
                                <p class="text-xs text-slate-500">Hoy</p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Válido Hasta
                            </label>
                            @if($custom_duration)
                                <input 
                                    type="date" 
                                    wire:model="valid_until"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                >
                                @error('valid_until') 
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                                @enderror
                            @else
                                <div class="text-lg font-semibold text-slate-900">
                                    {{ \Carbon\Carbon::parse($valid_until)->format('d/m/Y') }}
                                </div>
                                <p class="text-xs text-slate-500">
                                    @php
                                        $from = \Carbon\Carbon::parse($valid_from);
                                        $to = \Carbon\Carbon::parse($valid_until);
                                        $days = $from->diffInDays($to);
                                    @endphp
                                    En {{ $days }} día{{ $days != 1 ? 's' : '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg shadow-sm transition cursor-pointer"
                >
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
    @elseif($role === 'seller' && !$currentSetting)
        {{-- Mensaje para vendedores cuando no hay configuración --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Información:</strong> Actualmente no existe una configuración de costos indirectos.
                        </p>
                        <div class="mt-2 text-xs text-red-700 font-semibold">
                            Por favor, contacta a un administrador para que registre la configuración inicial.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($role !== 'seller')
        {{-- Mensaje cuando la configuración está vigente y NO puede crear nueva --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">
                            <strong>Configuración Vigente:</strong> La configuración actual está activa y en uso. 
                            @if($currentSetting && $currentSetting->valid_until)
                                Podrás crear una nueva configuración cuando esté próxima a expirar (2-3 días antes del {{ $currentSetting->valid_until->format('d/m/Y') }}) o cuando ya haya expirado.
                            @else
                                Esta configuración no tiene fecha de expiración y seguirá vigente hasta que se cree una nueva cuando sea necesario.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Confirmación --}}
    @if($showConfirmModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1100;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeConfirmModal"></div>
                
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-custom-blue px-6 py-4 rounded-t-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Confirmar Cambios</h3>
                                    <p class="text-sm text-red-400">Revisa los detalles antes de continuar</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeConfirmModal" class="text-slate-300 hover:text-slate-600 hover:bg-slate-50 rounded-lg p-2  transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <p class="text-slate-700 mb-4">
                            @if($pendingAction === 'update')
                                Estás a punto de <strong class="text-slate-900">actualizar</strong> la configuración de costos indirectos al <strong class="text-slate-900">{{ $indirect_cost_percentage }}%</strong>.
                            @else
                                Estás a punto de <strong class="text-slate-900">crear</strong> una nueva configuración de costos indirectos del <strong class="text-slate-900">{{ $indirect_cost_percentage }}%</strong>.
                            @endif
                        </p>

                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong class="text-blue-900">Importante:</strong> Este cambio afectará el cálculo de precios de todos los productos.
                            </p>
                        </div>

                        <p class="text-sm text-slate-600 mb-6">
                            ¿Estás seguro de que deseas continuar?
                        </p>

                        <div class="flex space-x-3">
                            <button 
                                wire:click="closeConfirmModal"
                                class="flex-1 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-medium rounded-lg border border-slate-300 transition cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button 
                                wire:click="confirmAction"
                                class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg shadow-sm transition cursor-pointer"
                            >
                                Sí, Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
