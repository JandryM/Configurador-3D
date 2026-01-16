<div>
    <!-- Encabezado de la sección -->
    <x-page-header 
        title="Gestión de Órdenes"
        description="Administra y aprueba las órdenes de producción"
        gradient="from-indigo-400 to-purple-500"
        :show-button="false"
        icon-gradient="from-blue-500 to-indigo-600"
    >
        <x-slot name="icon">
            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
            </svg>
        </x-slot>
    </x-page-header>

    @if (session()->has('message'))
        <div class="mb-4 animate-in fade-in slide-in-from-top-2 duration-500"
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 2000)"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white/70 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl p-6 bg-gradient-to-r from-green-500 to-emerald-600 text-white">
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 animate-in fade-in slide-in-from-top-2 duration-500"
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 2000)"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white/70 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl p-6 bg-gradient-to-r from-red-500 to-rose-600 text-white">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Estadísticas -->
    <x-stats-grid columns="4">
            <x-stat-card 
                title="Total Órdenes" 
                :value="$total"
                gradient="from-blue-500 to-indigo-600"
                hover-color="blue-300"
            >
                <x-slot name="icon">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Ganancia Total" 
                value="${{ number_format($gananciaTotal, 2) }}"
                gradient="from-green-500 to-emerald-600"
                hover-color="green-300"
            >
                <x-slot name="icon">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Productos" 
                :value="$cantidadProductos"
                gradient="from-purple-500 to-pink-600"
                hover-color="purple-300"
            >
                <x-slot name="icon">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                    </svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card 
                title="Completadas" 
                :value="$ordenesTerminadas"
                gradient="from-yellow-500 to-orange-600"
                hover-color="yellow-300"
            >
                <x-slot name="icon">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-4 xl:h-4 2xl:w-7 2xl:h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </x-slot>
            </x-stat-card>
        </x-stats-grid>

    <!-- Tabla de órdenes -->
    <x-table-container
        :has-pagination="true"
        :page="$page"
        :per-page="$perPage"
        :total="$total"
        item-name="orden"
    >
        <!-- Filtros y búsqueda dentro de la tabla -->
        <div class="bg-white border-b border-slate-200 shadow-lg mb-2 md:mb-3 lg:mb-4">
            <div class="flex flex-wrap gap-1 sm:gap-1.5 md:gap-2 lg:gap-3 xl:gap-3 2xl:gap-4 items-end">
                <!-- Búsqueda -->
                <div class="flex-1 min-w-[200px] sm:min-w-[220px] md:min-w-[250px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar Orden
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Número o cliente..."
                            class="w-full pl-8 sm:pl-9 md:pl-10 lg:pl-10 xl:pl-8 2xl:pl-10 pr-2 sm:pr-3 md:pr-4 lg:pr-4 xl:pr-3 2xl:pr-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 border-slate-200 bg-slate-50/50 text-slate-800 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm hover:border-slate-300 shadow-lg"
                        >
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-4.5 md:h-4.5 lg:w-4.5 lg:h-4.5 xl:w-3.5 xl:h-3.5 2xl:w-5 2xl:h-5 text-slate-400 absolute left-2 sm:left-2.5 md:left-3 lg:left-3 xl:left-2 2xl:left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro por estado -->
                <div class="min-w-[140px] sm:min-w-[150px] md:min-w-[180px]">
                    <label class="block text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm font-semibold text-slate-700 mb-0.5 sm:mb-1 md:mb-1.5 lg:mb-1.5 xl:mb-1 2xl:mb-2">
                        <svg class="w-3 h-3 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 lg:w-3.5 lg:h-3.5 xl:w-3 xl:h-3 2xl:w-4 2xl:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Estado
                    </label>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full px-2 sm:px-3 md:px-4 lg:px-4 xl:px-3 2xl:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2 xl:py-1.5 2xl:py-2.5 border-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 active:scale-[0.98] transition-all duration-200 text-xs sm:text-xs md:text-sm lg:text-sm xl:text-xs 2xl:text-sm cursor-pointer shadow-md font-medium {{ $statusFilter !== '' ? 'border-blue-400 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-900 shadow-lg hover:border-blue-500 hover:shadow-xl hover:from-blue-200 hover:to-blue-100' : 'border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100/50 text-slate-800 hover:border-blue-300 hover:shadow-lg hover:from-blue-50 hover:to-slate-50' }} [&>option]:bg-white [&>option]:text-slate-800 [&>option]:py-2 [&>option:hover]:bg-gradient-to-r [&>option:hover]:from-blue-50 [&>option:hover]:to-blue-100 [&>option:checked]:bg-blue-500 [&>option:checked]:text-white"
                    >
                        <option value="" class="py-2">✨ Todos</option>
                        <option value="pending" class="py-2">⏳ Pendiente</option>
                        <option value="approved" class="py-2">✅ Aprobada</option>
                        <option value="in_production" class="py-2">🔧 En Producción</option>
                        <option value="completed" class="py-2">🎉 Completada</option>
                        <option value="cancelled" class="py-2">❌ Cancelada</option>
                    </select>
                </div>
            </div>
        </div>

        <thead>
            <tr class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 border-b-2 border-indigo-200 shadow-sm">
                            <x-table-header>Número</x-table-header>
                            <x-table-header>Cliente</x-table-header>
                            <x-table-header>Producto</x-table-header>
                            <x-table-header>Cantidad</x-table-header>
                            <x-table-header>Monto</x-table-header>
                            <x-table-header>Estado</x-table-header>
                            <x-table-header>Fecha Creación</x-table-header>
                            <x-table-header>Fecha Estimada</x-table-header>
                            <x-table-header align="center">Acciones</x-table-header>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all duration-150">
                                <x-table-cell>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $order['number'] }}</p>
                                            <p class="text-sm text-slate-500">Orden</p>
                                        </div>
                                    </div>
                                </x-table-cell>
                                <x-table-cell>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $order['client'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $order['email'] }}</p>
                                    </div>
                                </x-table-cell>
                                <x-table-cell>
                                    <span class="text-slate-700">{{ $order['product_name'] }}</span>
                                </x-table-cell>
                                <x-table-cell>
                                    <span class="text-slate-700">
                                        {{ $order['quantity'] ?? 1 }} {{ ($order['quantity'] ?? 1) == 1 ? 'unidad' : 'unidades' }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell>
                                    <span class="font-medium text-slate-800">
                                        ${{ number_format($order['amount'], 2) }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-blue-100 text-blue-800',
                                            'in_production' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'approved' => 'Aprobada',
                                            'in_production' => 'En Producción',
                                            'completed' => 'Completada',
                                            'cancelled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order['status']] ?? $order['status'] }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell>
                                    <span class="text-slate-700">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell>
                                    @if($order['estimated_finish_at'])
                                        <div>
                                            <span class="text-slate-700 font-medium">
                                                {{ \Carbon\Carbon::parse($order['estimated_finish_at'])->format('d/m/Y') }}
                                            </span>
                                            @if($order['status'] === 'in_production')
                                                @php
                                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($order['estimated_finish_at']), false);
                                                @endphp
                                                @if($daysRemaining > 0)
                                                    <p class="text-xs text-green-600">{{ ceil($daysRemaining) }} día(s) restantes</p>
                                                @elseif($daysRemaining < 0)
                                                    <p class="text-xs text-red-600">Retrasado {{ abs(floor($daysRemaining)) }} día(s)</p>
                                                @else
                                                    <p class="text-xs text-green-600">Finaliza hoy</p>
                                                @endif
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </x-table-cell>
                                <x-table-cell align="center">
                                    <div class="flex justify-center space-x-2">
                                        <x-action-button 
                                            color="blue"
                                            tooltip="Ver detalles"
                                            wire:click="showOrder({{ $order['id'] }})"
                                        >
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </x-action-button>

                                        @if($canModify)
                                            @if($order['status'] === 'pending')
                                                <x-action-button 
                                                    color="green"
                                                    tooltip="Aprobar orden"
                                                    wire:click="approveOrder({{ $order['id'] }})"
                                                >
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </x-action-button>
                                            @endif

                                            @if($order['status'] === 'approved')
                                                <x-action-button 
                                                    color="purple"
                                                    tooltip="Iniciar producción"
                                                    wire:click="startProduction({{ $order['id'] }})"
                                                >
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </x-action-button>
                                            @endif

                                            @if($order['status'] === 'in_production')
                                                <x-action-button 
                                                    color="green"
                                                    tooltip="Marcar como completada"
                                                    wire:click="completeOrder({{ $order['id'] }})"
                                                >
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </x-action-button>
                                        @endif

                                        @if(in_array($order['status'], ['pending', 'approved']))
                                            <x-action-button 
                                                color="red"
                                                tooltip="Cancelar orden"
                                                wire:click="cancelOrder({{ $order['id'] }})"
                                            >
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </x-action-button>
                                        @endif
                                        @endif
                                    </div>
                                </x-table-cell>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-8 text-slate-500">
                                    No hay órdenes registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
    </x-table-container>

    <!-- Modal de Detalles de Orden -->
    @if($showOrderModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1000;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/30 backdrop-blur-[1px]" wire:click="closeModal"></div>
                
                <div class="bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-4xl mx-auto text-left align-middle transition-all transform relative" style="max-height: 90vh; display: flex; flex-direction: column;">
                    <!-- Header -->
                    <div class="sticky top-0 bg-gradient-to-r from-indigo-400 to-purple-500 border-b border-slate-700 px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Detalles de Orden: {{ $selectedOrder['number'] }}</h2>
                                <p class="text-sm text-white/90">Gestión completa de la orden</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeModal" class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-colors duration-200 cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Contenido -->
                    <div class="overflow-y-auto px-6 py-4 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800 hover:scrollbar-thumb-slate-500" style="flex: 1; scrollbar-width: thin;">
                        <!-- Información de la Orden -->
                        <div class="mb-6 p-4 bg-slate-800 rounded-lg border border-slate-600">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-slate-400">Cliente</p>
                                    <p class="font-medium text-slate-200">{{ $selectedOrder['client'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-400">Estado Actual</p>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
                                            'approved' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
                                            'in_production' => 'bg-purple-500/20 text-purple-300 border border-purple-500/30',
                                            'completed' => 'bg-green-500/20 text-green-300 border border-green-500/30',
                                            'cancelled' => 'bg-red-500/20 text-red-300 border border-red-500/30',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'approved' => 'Aprobada',
                                            'in_production' => 'En Producción',
                                            'completed' => 'Completada',
                                            'cancelled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$selectedOrder['status']] ?? 'bg-slate-500/20 text-slate-300 border border-slate-500/30' }}">
                                        {{ $statusLabels[$selectedOrder['status']] ?? $selectedOrder['status'] }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-400">Cantidad</p>
                                    <p class="font-medium text-slate-200">{{ $selectedOrder['quantity'] ?? 1 }} {{ ($selectedOrder['quantity'] ?? 1) == 1 ? 'unidad' : 'unidades' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-400">Monto Total</p>
                                    <p class="font-bold text-lg text-emerald-400">${{ number_format($selectedOrder['amount'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-400">Fecha de Creación</p>
                                    <p class="font-medium text-slate-200">{{ \Carbon\Carbon::parse($selectedOrder['created_at'])->format('d/m/Y H:i') }}</p>
                                </div>
                                @if(isset($selectedOrder['proforma_id']) && $selectedOrder['proforma_id'])
                                    <div>
                                        <p class="text-sm text-slate-400 mb-2">Proforma</p>
                                        <button 
                                            wire:click="goToProforma({{ $selectedOrder['proforma_id'] }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-700 hover:bg-slate-600 border border-slate-500 text-slate-300 hover:text-slate-100 rounded-lg text-sm transition-all duration-200 cursor-pointer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Ver Original</span>
                                        </button>
                                    </div>
                                @endif
                                @if($selectedOrder['status'] === 'in_production' && $selectedOrder['estimated_finish_at'])
                                    <div class="col-span-2">
                                        <p class="text-sm text-slate-400">Fecha Estimada de Finalización</p>
                                        <p class="font-medium text-purple-400">
                                            {{ \Carbon\Carbon::parse($selectedOrder['estimated_finish_at'])->format('d/m/Y') }}
                                        </p>
                                        @php
                                            $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($selectedOrder['estimated_finish_at']), false);
                                        @endphp
                                        @if($daysRemaining > 0)
                                            <p class="text-xs text-slate-400">Faltan {{ ceil($daysRemaining) }} día(s)</p>
                                        @elseif($daysRemaining < 0)
                                            <p class="text-xs text-red-400">Retrasado por {{ abs(floor($daysRemaining)) }} día(s)</p>
                                        @else
                                            <p class="text-xs text-green-400">Finaliza hoy</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones Rápidas -->
                        @if($canModify)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-slate-200 mb-3">Acciones de Gestión</h3>
                                <div class="flex flex-wrap gap-3">
                                    @if($selectedOrder['status'] === 'pending')
                                        <button wire:click="approveOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                            ✓ Aprobar Orden
                                        </button>
                                        <button wire:click="cancelOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                            ✗ Cancelar Orden
                                        </button>
                                    @elseif($selectedOrder['status'] === 'approved')
                                        <button wire:click="startProduction({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                            ▶ Iniciar Producción
                                        </button>
                                        <button wire:click="cancelOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                            ✗ Cancelar Orden
                                        </button>
                                    @elseif($selectedOrder['status'] === 'in_production')
                                        <button wire:click="completeOrder({{ $selectedOrder['id'] }})" 
                                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                            ✓ Marcar como Completada
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mb-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                <p class="text-sm text-yellow-300">
                                    <strong>Modo Solo Lectura:</strong> No tienes permisos para modificar el estado de las órdenes. Solo puedes visualizar la información.
                                </p>
                            </div>
                        @endif

                        <!-- Detalle de Productos de la Proforma -->
                        @if(isset($selectedOrder['items']) && is_array($selectedOrder['items']) && count($selectedOrder['items']) > 0)
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-slate-200 mb-2">Productos ({{ count($selectedOrder['items']) }})</h3>
                                <div class="space-y-2">
                                    @foreach($selectedOrder['items'] as $index => $item)
                                        <div class="bg-slate-800 rounded-lg border border-slate-600 overflow-hidden transition-all duration-200 hover:shadow-md hover:border-slate-500">
                                            <!-- Header del producto (siempre visible) -->
                                            <button 
                                                type="button"
                                                onclick="document.getElementById('product-detail-{{ $index }}').classList.toggle('hidden'); document.getElementById('chevron-{{ $index }}').classList.toggle('rotate-180')"
                                                class="w-full px-3 py-2 flex items-center justify-between hover:bg-slate-700/50 transition-colors cursor-pointer"
                                            >
                                                <div class="flex items-center space-x-2 flex-1 min-w-0">
                                                    <div class="w-7 h-7 bg-gradient-to-r from-pink-400 to-fuchsia-500 rounded-md flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div class="text-left flex-1 min-w-0">
                                                        <p class="font-semibold text-slate-200 text-sm truncate">
                                                            {{ $item['product_name'] ?? ($item['product'] ? $item['product']->name : 'Producto eliminado') }}
                                                        </p>
                                                        <p class="text-xs text-slate-400">
                                                            <span class="font-medium text-fuchsia-400">{{ $item['quantity'] ?? 1 }}×</span> ${{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 2) }}
                                                            <span class="mx-1">•</span>
                                                            <span class="font-semibold text-emerald-400">${{ number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? $item['price'] ?? 0), 2) }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <svg 
                                                    id="chevron-{{ $index }}"
                                                    class="w-5 h-5 text-slate-400 transition-transform duration-200 flex-shrink-0" 
                                                    fill="currentColor" 
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>

                                            <!-- Detalle del producto (colapsable) -->
                                            <div id="product-detail-{{ $index }}" class="hidden border-t border-slate-600">
                                                <div class="px-3 py-2 bg-slate-700/30">
                                                    @php
                                                        // Campos permitidos y sus traducciones
                                                        $allowedFields = [
                                                            'height' => 'Alto',
                                                            'width' => 'Ancho',
                                                            'color' => 'Color aluminio',
                                                            'glassColor' => 'Color de vidrio',
                                                            'material' => 'Material',
                                                            'quantity' => 'Cantidad',
                                                            'profile' => 'Perfil',
                                                            'glass' => 'Vidrio',
                                                            'type' => 'Tipo',
                                                            'finish' => 'Acabado',
                                                        ];
                                                    @endphp
                                                    @if(isset($item['configuration']['parameters']))
                                                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Configuración</p>
                                                        <div class="grid grid-cols-3 gap-1.5 mb-2">
                                                            @foreach($allowedFields as $key => $label)
                                                                @if(isset($item['configuration']['parameters'][$key]) && !is_array($item['configuration']['parameters'][$key]))
                                                                    <div class="bg-slate-700 border border-slate-600 rounded px-2 py-1.5">
                                                                        <p class="text-xs text-slate-400 leading-tight">{{ $label }}</p>
                                                                        <p class="text-xs font-semibold text-slate-200 leading-tight mt-0.5">{{ $item['configuration']['parameters'][$key] }}</p>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if(isset($item['configuration']['parameters']['notes']) && !empty($item['configuration']['parameters']['notes']))
                                                        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded px-2 py-1.5">
                                                            <p class="text-xs font-medium text-yellow-300 mb-0.5">📝 Notas</p>
                                                            <p class="text-xs text-slate-300">{{ $item['configuration']['parameters']['notes'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Tiempo Estimado -->
    @if($showEstimatedTimeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1100;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="cancelEstimatedTimeModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Tiempo Estimado de Producción</h3>
                                    <p class="text-sm text-white/80">Ingresa los días estimados</p>
                                </div>
                            </div>
                            <button type="button" wire:click="cancelEstimatedTimeModal" class="text-white/80 hover:text-white transition-colors cursor-pointer p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        @if(session()->has('error'))
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 bg-red-50">{{ session('error') }}</p>
                            </div>
                        @endif

                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-slate-700 bg-white">
                                    Tiempo estimado de producción *
                                </label>
                                <button 
                                    type="button"
                                    wire:click="toggleCustomDate"
                                    class="text-xs px-3 py-1.5 rounded-lg border-2 shadow-md font-semibold transition-all duration-200 cursor-pointer
                                        {{ $customDate 
                                            ? 'bg-gradient-to-r from-purple-200 to-purple-100 border-purple-400 text-purple-800 hover:from-purple-300 hover:to-purple-200' 
                                            : 'bg-gradient-to-r from-indigo-100 to-purple-100 border-purple-300 text-purple-700 hover:from-indigo-200 hover:to-purple-200' 
                                        }}"
                                >
                                    {{ $customDate ? '📅 Fecha personalizada' : '🔧 Personalizar fecha' }}
                                </button>
                            </div>

                            @if(!$customDate)
                                <!-- Selector de periodos predefinidos -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 3)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 3 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 3 ? 'text-purple-600' : 'text-slate-700' }}">3</div>
                                            <div class="text-xs {{ $estimatedDays == 3 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 7)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 7 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 7 ? 'text-purple-600' : 'text-slate-700' }}">7</div>
                                            <div class="text-xs {{ $estimatedDays == 7 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (1 semana)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 14)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 14 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 14 ? 'text-purple-600' : 'text-slate-700' }}">14</div>
                                            <div class="text-xs {{ $estimatedDays == 14 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (2 semanas)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 21)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 21 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 21 ? 'text-purple-600' : 'text-slate-700' }}">21</div>
                                            <div class="text-xs {{ $estimatedDays == 21 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (3 semanas)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 30)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 30 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 30 ? 'text-purple-600' : 'text-slate-700' }}">30</div>
                                            <div class="text-xs {{ $estimatedDays == 30 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (1 mes)</div>
                                        </div>
                                    </button>

                                    <button 
                                        type="button"
                                        wire:click="$set('estimatedDays', 60)"
                                        class="p-4 rounded-xl border-2 transition-all {{ $estimatedDays == 60 ? 'border-purple-500 bg-purple-50' : 'border-slate-200 bg-white hover:border-slate-300' }} cursor-pointer"
                                    >
                                        <div class="text-center">
                                            <div class="text-2xl font-bold {{ $estimatedDays == 60 ? 'text-purple-600' : 'text-slate-700' }}">60</div>
                                            <div class="text-xs {{ $estimatedDays == 60 ? 'text-purple-600' : 'text-slate-500' }} bg-transparent">días (2 meses)</div>
                                        </div>
                                    </button>
                                </div>
                            @else
                                <!-- Selector de fecha personalizada -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 mb-2 bg-white">
                                        Selecciona la fecha estimada de finalización
                                    </label>
                                    <input 
                                        type="date" 
                                        wire:model.live="customEstimatedDate"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition shadow-sm bg-white text-slate-900"
                                    >
                                </div>
                            @endif
                        </div>

                        <!-- Vista previa de fecha -->
                        @php
                            $previewDate = null;
                            if ($customDate && $customEstimatedDate) {
                                $previewDate = \Carbon\Carbon::parse($customEstimatedDate);
                            } elseif (!$customDate && $estimatedDays) {
                                $previewDate = now()->addDays($estimatedDays);
                            }
                        @endphp

                        @if($previewDate)
                            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-purple-900 bg-purple-50">Fecha estimada de finalización:</span>
                                </div>
                                <p class="text-lg font-bold text-purple-700 bg-purple-50">
                                    {{ $previewDate->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-purple-600 mt-1 bg-purple-50">
                                    ({{ now()->diffInDays($previewDate, false) >= 0 ? 'En ' . ceil(now()->diffInDays($previewDate)) . ' día(s)' : 'Fecha pasada' }})
                                </p>
                            </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="flex space-x-3 bg-white">
                            <button 
                                type="button"
                                wire:click="cancelEstimatedTimeModal"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="button"
                                wire:click="confirmStartProduction"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all cursor-pointer"
                            >
                                ▶ Iniciar Producción
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    <!-- Modal de Confirmación: Aprobar Orden -->
    @if($showApproveConfirmModal && $pendingActionOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1250;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeApproveConfirmModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 rounded-t-2xl">
                        <!-- Barra de carga -->
                        <div wire:loading wire:target="confirmApproveOrder" class="absolute top-0 left-0 right-0 h-1 bg-white/30 overflow-hidden rounded-t-2xl">
                            <div class="h-full bg-white animate-pulse" style="width: 100%; animation: shimmer 1.5s infinite;"></div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Confirmar Aprobación</h3>
                                    <p class="text-sm text-white/80">Verificar antes de aprobar la orden</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeApproveConfirmModal" class="text-white/80 hover:text-white transition-colors cursor-pointer">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <p class="text-sm text-green-800 mb-2">
                                <strong>✓ ¿Está seguro de aprobar esta orden?</strong>
                            </p>
                            <p class="text-xs text-green-700">
                                Al aprobar la orden se enviará una notificación al cliente con los datos bancarios para realizar el pago.
                            </p>
                        </div>

                        <!-- Detalles de la Orden -->
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Detalles de la Orden
                            </h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Número de Orden</p>
                                    <p class="font-bold text-gray-800">#{{ $pendingActionOrder['id'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Cliente</p>
                                    <p class="font-semibold text-gray-800">{{ $pendingActionOrder['client'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 mb-2">Productos</p>
                                    <div class="space-y-1">
                                        @if(isset($pendingActionOrder['items']) && count($pendingActionOrder['items']) > 0)
                                            @foreach($pendingActionOrder['items'] as $item)
                                                <div class="flex justify-between items-center bg-white/50 rounded-lg px-3 py-2">
                                                    <span class="font-medium text-gray-800">{{ $item['product_name'] }}</span>
                                                    <span class="text-sm text-gray-600">x{{ $item['quantity'] }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-sm text-gray-600">{{ $pendingActionOrder['product_name'] ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total</p>
                                    <p class="font-bold text-green-600 text-lg">${{ number_format($pendingActionOrder['amount'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-6 flex space-x-3">
                            <button 
                                type="button"
                                wire:click="closeApproveConfirmModal"
                                wire:loading.attr="disabled"
                                wire:target="confirmApproveOrder"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="button"
                                wire:click="confirmApproveOrder"
                                wire:loading.attr="disabled"
                                wire:target="confirmApproveOrder"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="confirmApproveOrder">✓ Aprobar Orden</span>
                                <span wire:loading wire:target="confirmApproveOrder" class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Confirmación: Cancelar Orden -->
    @if($showCancelConfirmModal && $pendingActionOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1250;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeCancelConfirmModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4 rounded-t-2xl">
                        <!-- Barra de carga -->
                        <div wire:loading wire:target="confirmCancelOrder" class="absolute top-0 left-0 right-0 h-1 bg-white/30 overflow-hidden rounded-t-2xl">
                            <div class="h-full bg-white animate-pulse" style="width: 100%; animation: shimmer 1.5s infinite;"></div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Confirmar Cancelación</h3>
                                    <p class="text-sm text-white/80">Esta acción no se puede deshacer</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeCancelConfirmModal" class="text-white/80 hover:text-white transition-colors cursor-pointer">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-sm text-red-800 mb-2">
                                <strong>⚠️ ¿Está seguro de cancelar esta orden?</strong>
                            </p>
                            <p class="text-xs text-red-700">
                                Esta acción es irreversible. La orden cambiará a estado "Cancelada" y no podrá ser procesada.
                            </p>
                        </div>

                        <!-- Detalles de la Orden -->
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Detalles de la Orden
                            </h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Número de Orden</p>
                                    <p class="font-bold text-gray-800">#{{ $pendingActionOrder['id'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Estado Actual</p>
                                    <p class="font-semibold">
                                        @php
                                            $statusColors = [
                                                'pending' => 'text-yellow-600',
                                                'approved' => 'text-blue-600',
                                                'in_production' => 'text-purple-600',
                                                'completed' => 'text-green-600',
                                                'cancelled' => 'text-red-600'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pendiente',
                                                'approved' => 'Aprobada',
                                                'in_production' => 'En Producción',
                                                'completed' => 'Completada',
                                                'cancelled' => 'Cancelada'
                                            ];
                                        @endphp
                                        <span class="{{ $statusColors[$pendingActionOrder['status']] ?? 'text-gray-600' }}">
                                            {{ $statusLabels[$pendingActionOrder['status']] ?? $pendingActionOrder['status'] }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Cliente</p>
                                    <p class="font-semibold text-gray-800">{{ $pendingActionOrder['client'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 mb-2">Productos</p>
                                    <div class="space-y-1">
                                        @if(isset($pendingActionOrder['items']) && count($pendingActionOrder['items']) > 0)
                                            @foreach($pendingActionOrder['items'] as $item)
                                                <div class="flex justify-between items-center bg-white/50 rounded-lg px-3 py-2">
                                                    <span class="font-medium text-gray-800">{{ $item['product_name'] }}</span>
                                                    <span class="text-sm text-gray-600">x{{ $item['quantity'] }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-sm text-gray-600">{{ $pendingActionOrder['product_name'] ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total</p>
                                    <p class="font-bold text-gray-800 text-lg">${{ number_format($pendingActionOrder['amount'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-6 flex space-x-3">
                            <button 
                                type="button"
                                wire:click="closeCancelConfirmModal"
                                wire:loading.attr="disabled"
                                wire:target="confirmCancelOrder"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
                            >
                                Volver
                            </button>
                            <button 
                                type="button"
                                wire:click="confirmCancelOrder"
                                wire:loading.attr="disabled"
                                wire:target="confirmCancelOrder"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-semibold rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="confirmCancelOrder">✗ Cancelar Orden</span>
                                <span wire:loading wire:target="confirmCancelOrder" class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Confirmación: Completar Orden -->
    @if($showCompleteConfirmModal && $pendingActionOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1250;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeCompleteConfirmModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 rounded-t-2xl">
                        <!-- Barra de carga -->
                        <div wire:loading wire:target="confirmCompleteOrder" class="absolute top-0 left-0 right-0 h-1 bg-white/30 overflow-hidden rounded-t-2xl">
                            <div class="h-full bg-white animate-pulse" style="width: 100%; animation: shimmer 1.5s infinite;"></div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Confirmar Completación</h3>
                                    <p class="text-sm text-white/80">Verificar antes de marcar como completada</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeCompleteConfirmModal" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-800 mb-2">
                                <strong>🎉 ¿Está seguro de completar esta orden?</strong>
                            </p>
                            <p class="text-xs text-blue-700">
                                Al marcar la orden como completada, indicará que el producto ha sido finalizado y entregado al cliente. Esta acción cambiará el estado de la orden a "Completada".
                            </p>
                        </div>

                        <!-- Detalles de la Orden -->
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 rounded-xl p-5 border border-gray-200">
                            <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                                Detalles de la Orden
                            </h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Número de Orden</p>
                                    <p class="font-bold text-gray-800">#{{ $pendingActionOrder['id'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Cliente</p>
                                    <p class="font-semibold text-gray-800">{{ $pendingActionOrder['client'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-xs text-gray-500 mb-2">Productos</p>
                                    <div class="space-y-1">
                                        @if(isset($pendingActionOrder['items']) && count($pendingActionOrder['items']) > 0)
                                            @foreach($pendingActionOrder['items'] as $item)
                                                <div class="flex justify-between items-center bg-white/50 rounded-lg px-3 py-2">
                                                    <span class="font-medium text-gray-800">{{ $item['product_name'] }}</span>
                                                    <span class="text-sm text-gray-600">x{{ $item['quantity'] }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-sm text-gray-600">{{ $pendingActionOrder['product_name'] ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Total</p>
                                    <p class="font-bold text-blue-600 text-lg">${{ number_format($pendingActionOrder['amount'], 2) }}</p>
                                </div>
                                @if(isset($pendingActionOrder['estimated_finish_at']) && $pendingActionOrder['estimated_finish_at'])
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Fecha Estimada</p>
                                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pendingActionOrder['estimated_finish_at'])->format('d/m/Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="mt-6 flex space-x-3">
                            <button 
                                type="button"
                                wire:click="closeCompleteConfirmModal"
                                wire:loading.attr="disabled"
                                wire:target="confirmCompleteOrder"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="button"
                                wire:click="confirmCompleteOrder"
                                wire:loading.attr="disabled"
                                wire:target="confirmCompleteOrder"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            >
                                <span wire:loading.remove wire:target="confirmCompleteOrder">✓ Completar Orden</span>
                                <span wire:loading wire:target="confirmCompleteOrder" class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de Stock Insuficiente -->
    @if($showInsufficientStockModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: 1200;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity bg-black/50 backdrop-blur-sm" wire:click="closeInsufficientStockModal"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-auto text-left align-middle transition-all transform relative">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 py-4 rounded-t-2xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Stock Insuficiente</h3>
                                    <p class="text-sm text-white/80">No hay suficientes materiales para iniciar la producción</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeInsufficientStockModal" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-6">
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Stock Insuficiente:</strong> Los siguientes materiales no tienen suficiente inventario (incluyendo retazos disponibles). Debes realizar una compra antes de iniciar la producción.
                            </p>
                        </div>

                        <div class="space-y-3">
                            @foreach($insufficientMaterials as $item)
                                <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800 text-lg mb-3">{{ $item['material']->name }}</h4>
                                            
                                            <!-- Información principal -->
                                            <div class="grid grid-cols-3 gap-4 mb-3">
                                                <div class="bg-white rounded-lg p-3 border border-red-200">
                                                    <p class="text-xs text-gray-600 mb-1">Necesario</p>
                                                    <p class="font-bold text-red-700 text-lg">{{ number_format($item['needed'], 2) }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item['material']->unit_measure }}</p>
                                                </div>
                                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                    <p class="text-xs text-gray-600 mb-1">Disponible</p>
                                                    <p class="font-bold text-gray-800 text-lg">{{ number_format($item['available'], 2) }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item['material']->unit_measure }}</p>
                                                </div>
                                                <div class="bg-white rounded-lg p-3 border border-red-300">
                                                    <p class="text-xs text-gray-600 mb-1">Faltante</p>
                                                    <p class="font-bold text-red-600 text-lg">{{ number_format($item['missing'], 2) }}</p>
                                                    <p class="text-xs text-gray-500">{{ $item['material']->unit_measure }}</p>
                                                </div>
                                            </div>

                                            <!-- Desglose de inventario (si es por pieza) -->
                                            @if($item['material']->is_by_piece)
                                                @php
                                                    $stockPieces = $item['material']->stock_quantity;
                                                    $remaindersTotal = $item['material']->remainders()->where('status', 'available')->sum('remaining_length');
                                                @endphp
                                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <p class="text-xs font-semibold text-blue-900 mb-2">📦 Desglose de inventario:</p>
                                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Piezas completas:</span>
                                                            <span class="font-semibold text-gray-800">{{ $stockPieces }} × {{ number_format($item['material']->piece_size, 2) }}{{ $item['material']->unit_measure }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600">Retazos disponibles:</span>
                                                            <span class="font-semibold text-gray-800">{{ number_format($remaindersTotal, 2) }}{{ $item['material']->unit_measure }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-4 flex-shrink-0">
                                            <div class="w-16 h-16 bg-red-200 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Acciones -->
                        <div class="mt-6 flex space-x-3">
                            <button 
                                type="button"
                                wire:click="closeInsufficientStockModal"
                                class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors"
                            >
                                Cerrar
                            </button>
                            <button 
                                type="button"
                                onclick="window.location.href='/admin/inventory'"
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg transition-all"
                            >
                                📦 Ir a Inventario
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
