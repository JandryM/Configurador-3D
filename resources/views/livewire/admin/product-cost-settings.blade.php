<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Configuración de Costos por Producto"
        description="Administra los porcentajes de costos directos, desperdicio y margen de ganancia para cada producto"
        gradient="from-cyan-600 to-teal-700"
        :show-button="true"
        button-text="Costos Globales"
        button-link="{{ route('admin.cost-settings') }}"
    >
        <x-slot name="icon">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
        <x-slot name="buttonIcon">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
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

    {{-- Selector de Producto --}}
    <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 card-hover">
        <label class="block text-sm font-semibold text-slate-700 mb-3">
            Seleccionar Producto Personalizable *
        </label>
        @if($products->count() > 0)
            <select 
                wire:model.live="product_id"
                class="w-full px-4 py-3 border border-slate-200/50 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition shadow-sm bg-white/80 backdrop-blur-sm text-slate-900"
            >
                <option value="" class="bg-white text-slate-900">-- Selecciona un producto --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" class="bg-white text-slate-900">{{ $product->name }}</option>
                @endforeach
            </select>
            @error('product_id') 
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> 
            @enderror
            <p class="text-xs text-slate-500 mt-2">Solo se muestran productos personalizables</p>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>No hay productos personalizables disponibles.</strong> Solo se pueden configurar costos para productos con tipo "Personalizable" y que permitan personalización.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @php $role = auth()->user()->role ?? null; @endphp

    {{-- Configuración actual del producto --}}
    @if($product_id && $currentSetting)
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">Configuración Vigente</h3>
                <span class="px-4 py-2 bg-gradient-to-r from-green-200 to-emerald-300 text-green-700 rounded-lg text-sm font-semibold shadow-md">
                    ✓ Activa
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-4 border border-slate-200/50">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Costos Directos</label>
                    <div class="text-3xl font-bold text-blue-600">{{ $currentSetting->direct_cost_percentage }}%</div>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-4 border border-slate-200/50">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Desperdicio</label>
                    <div class="text-3xl font-bold text-yellow-600">{{ $currentSetting->waste_percentage }}%</div>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-4 border border-slate-200/50">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Margen de Ganancia</label>
                    <div class="text-3xl font-bold text-green-600">{{ $currentSetting->profit_margin_percentage }}%</div>
                </div>
                <div class="bg-white/40 backdrop-blur-sm rounded-xl p-4 border border-slate-200/50">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Creado por</label>
                    <div class="text-lg font-semibold text-slate-800">
                        {{ $currentSetting->user->name ?? 'N/A' }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        {{ $currentSetting->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
            @if($currentSetting->notes)
                <div class="mt-4 pt-4 border-t border-slate-200/50">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Notas</label>
                    <p class="text-slate-700">{{ $currentSetting->notes }}</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Formulario para nueva configuración --}}
    @if($product_id && $role !== 'seller')
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 card-hover">
            @if($currentSetting)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Nueva Configuración:</strong> Al guardar, la configuración actual se desactivará y se guardará en el historial. La nueva configuración se aplicará inmediatamente.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="text-lg font-semibold text-slate-800 mb-4">
                {{ $currentSetting ? 'Crear Nueva Configuración' : 'Configuración de Costos' }}
            </h3>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Costos Directos --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Costos Directos (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="direct_cost_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2 border border-slate-200/50 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900"
                                placeholder="Ej: 25.00"
                                required
                            >
                            <span class="absolute right-3 top-2 text-slate-500 bg-transparent">%</span>
                        </div>
                        @error('direct_cost_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Porcentaje de costos directos del producto</p>
                    </div>

                    {{-- Desperdicio --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Desperdicio (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="waste_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2 border border-slate-200/50 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900"
                                placeholder="Ej: 5.00"
                                required
                            >
                            <span class="absolute right-3 top-2 text-slate-500 bg-transparent">%</span>
                        </div>
                        @error('waste_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Porcentaje adicional por desperdicio</p>
                    </div>

                    {{-- Margen de Ganancia --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Margen de Ganancia (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="profit_margin_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2 border border-slate-200/50 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900"
                                placeholder="Ej: 30.00"
                                required
                            >
                            <span class="absolute right-3 top-2 text-slate-500 bg-transparent">%</span>
                        </div>
                        @error('profit_margin_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Margen de ganancia sobre el costo total</p>
                    </div>

                    {{-- Notas --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Notas (Opcional)
                        </label>
                        <textarea 
                            wire:model="notes"
                            rows="3"
                            maxlength="1000"
                            class="w-full px-4 py-2 border border-slate-200/50 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent bg-white/80 backdrop-blur-sm text-slate-900"
                            placeholder="Agrega notas o comentarios sobre esta configuración..."
                        ></textarea>
                        @error('notes') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-teal-700 hover:from-cyan-700 hover:to-teal-800 text-white font-semibold rounded-lg shadow-lg transition-all"
                    >
                        💾 Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    @elseif($product_id && $role === 'seller' && !$currentSetting)
        {{-- Mensaje para vendedores cuando no hay configuración --}}
        <div class="glass-card rounded-2xl shadow-xl p-6 card-hover">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>ℹ️ Información:</strong> Actualmente no existe una configuración de costos para este producto.
                        </p>
                        <div class="mt-2 text-xs text-red-700 font-semibold">
                            Por favor, contacta a un administrador para que registre la configuración inicial.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Historial de Configuraciones --}}
    @if($product_id && $settingsHistory->count() > 0)
        <div class="glass-card rounded-2xl shadow-xl p-6 card-hover">
            <h3 class="text-xl font-bold text-slate-800 mb-6">📋 Historial de Configuraciones</h3>
            <div class="overflow-x-auto rounded-xl border border-slate-200/50">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50 backdrop-blur-sm">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Costos Directos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Desperdicio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Margen
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Creado por
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Notas
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 backdrop-blur-sm divide-y divide-slate-200/50">
                        @foreach($settingsHistory as $setting)
                            <tr class="hover:bg-white/60 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $setting->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                    {{ $setting->direct_cost_percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">
                                    {{ $setting->waste_percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    {{ $setting->profit_margin_percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $setting->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $setting->notes ? Str::limit($setting->notes, 50) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Modal de Confirmación --}}
    @if($showConfirmModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1100;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeConfirmModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-yellow-500 to-amber-600 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Confirmar Cambios</h3>
                                    <p class="text-sm text-white/80">Revisa los detalles antes de continuar</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeConfirmModal" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <p class="text-slate-600 mb-4 bg-white">
                            Estás a punto de <strong class="text-slate-900">crear</strong> una nueva configuración de costos:
                        </p>

                        <div class="bg-slate-50 rounded-lg p-4 mb-4">
                            <ul class="text-sm text-slate-700 space-y-2 bg-slate-50">
                                <li class="bg-slate-50 flex justify-between">
                                    <span class="text-slate-600">Costos Directos:</span>
                                    <strong class="text-slate-900">{{ $direct_cost_percentage }}%</strong>
                                </li>
                                <li class="bg-slate-50 flex justify-between">
                                    <span class="text-slate-600">Desperdicio:</span>
                                    <strong class="text-slate-900">{{ $waste_percentage }}%</strong>
                                </li>
                                <li class="bg-slate-50 flex justify-between">
                                    <span class="text-slate-600">Margen de Ganancia:</span>
                                    <strong class="text-slate-900">{{ $profit_margin_percentage }}%</strong>
                                </li>
                            </ul>
                        </div>

                        @if($currentSetting)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                                <p class="text-sm text-blue-700 bg-blue-50">
                                    <strong class="text-blue-900">⚠️ Importante:</strong> La configuración actual se desactivará y se guardará en el historial.
                                </p>
                            </div>
                        @endif

                        <p class="text-sm text-slate-600 mb-6 bg-white">
                            ¿Estás seguro de que deseas continuar?
                        </p>

                        <div class="flex space-x-3 bg-white">
                            <button 
                                wire:click="closeConfirmModal"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors"
                            >
                                ✕ Cancelar
                            </button>
                            <button 
                                wire:click="confirmAction"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg transition-all"
                            >
                                ✓ Sí, Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
