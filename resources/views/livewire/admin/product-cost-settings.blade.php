<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Configuración de Costos por Producto"
        description="Administra los porcentajes de mano de obra directa, factor de merma y margen de utilidad para cada producto"
        :show-button="true"
        button-text="Costos Globales"
        button-link="{{ route('admin.cost-settings') }}"
        button-color="red"
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
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <label class="block text-sm font-semibold text-slate-700 mb-3">
            Seleccionar Producto Personalizable *
        </label>
        @if($products->count() > 0)
            <select 
                wire:model.live="product_id"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
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
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800">
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
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-900">Configuración Vigente</h3>
                <span class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs font-medium border border-green-200">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Activa
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Mano de Obra Directa</label>
                    <div class="text-3xl font-bold text-slate-900">{{ $currentSetting->direct_cost_percentage }}%</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Factor de Merma</label>
                    <div class="text-3xl font-bold text-slate-900">{{ $currentSetting->waste_percentage }}%</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Margen de Utilidad</label>
                    <div class="text-3xl font-bold text-slate-900">{{ $currentSetting->profit_margin_percentage }}%</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Creado por</label>
                    <div class="text-lg font-semibold text-slate-900">
                        {{ $currentSetting->user->name ?? 'N/A' }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">
                        {{ $currentSetting->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
            @if($currentSetting->notes)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Notas</label>
                    <p class="text-slate-700">{{ $currentSetting->notes }}</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Formulario para nueva configuración --}}
    @if($product_id && $role !== 'seller')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            @if($currentSetting)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Nueva Configuración:</strong> Al guardar, la configuración actual se desactivará y se guardará en el historial. La nueva configuración se aplicará inmediatamente.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="text-lg font-semibold text-slate-900 mb-4">
                {{ $currentSetting ? 'Crear Nueva Configuración' : 'Configuración de Costos' }}
            </h3>

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Costos Directos --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Mano de Obra Directa (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="direct_cost_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                placeholder="Ej: 25.00"
                                required
                            >
                            <span class="absolute right-3 top-2.5 text-slate-500">%</span>
                        </div>
                        @error('direct_cost_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Porcentaje estimado para cubrir el costo del personal operativo</p>
                    </div>

                    {{-- Desperdicio --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Factor de Merma (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="waste_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                placeholder="Ej: 5.00"
                                required
                            >
                            <span class="absolute right-3 top-2.5 text-slate-500">%</span>
                        </div>
                        @error('waste_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Porcentaje técnico de pérdida de material (retazos)</p>
                    </div>

                    {{-- Margen de Ganancia --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Margen de Utilidad (%) *
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model="profit_margin_percentage"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                                placeholder="Ej: 30.00"
                                required
                            >
                            <span class="absolute right-3 top-2.5 text-slate-500">%</span>
                        </div>
                        @error('profit_margin_percentage') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        <p class="text-xs text-slate-500 mt-1">Porcentaje de utilidad deseada sobre el costo de producción</p>
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
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-900 focus:border-slate-900 bg-white text-slate-900 transition-shadow shadow-sm"
                            placeholder="Agrega notas o comentarios sobre esta configuración..."
                        ></textarea>
                        @error('notes') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
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
    @elseif($product_id && $role === 'seller' && !$currentSetting)
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
                            <strong>Información:</strong> Actualmente no existe una configuración de costos para este producto.
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
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Historial de Configuraciones</h3>
            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Mano de Obra
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Merma
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Utilidad
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Creado por
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Notas
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($settingsHistory as $setting)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $setting->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                    {{ $setting->direct_cost_percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                    {{ $setting->waste_percentage }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
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
                            Estás a punto de <strong class="text-slate-900">crear</strong> una nueva configuración de costos:
                        </p>

                        <div class="bg-slate-50 rounded-lg p-4 mb-4">
                            <ul class="text-sm text-slate-700 space-y-2">
                                <li class="flex justify-between">
                                    <span class="text-slate-600">Mano de Obra Directa:</span>
                                    <strong class="text-slate-900">{{ $direct_cost_percentage }}%</strong>
                                </li>
                                <li class="bg-slate-50 flex justify-between">
                                    <span class="text-slate-600">Factor de Merma:</span>
                                    <strong class="text-slate-900">{{ $waste_percentage }}%</strong>
                                </li>
                                <li class="bg-slate-50 flex justify-between">
                                    <span class="text-slate-600">Margen de Utilidad:</span>
                                    <strong class="text-slate-900">{{ $profit_margin_percentage }}%</strong>
                                </li>
                            </ul>
                        </div>

                        @if($currentSetting)
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <strong class="text-blue-900">Importante:</strong> La configuración actual se desactivará y se guardará en el historial.
                                </p>
                            </div>
                        @endif

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
