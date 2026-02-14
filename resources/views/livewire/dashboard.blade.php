<div class="!bg-slate-50 !text-slate-900">
<!-- ============================================ -->
<!-- HEADER - DASHBOARD MEJORADO -->
<!-- ============================================ -->
<div class="fade-in mb-6">
    <div class="!bg-gradient-to-r from-orange-500 via-amber-500 to-teal-500 rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <svg class="w-8 h-8 text-white/90 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                </div>
                <p class="text-white/90 text-sm">Bienvenido de nuevo, <span class="font-semibold text-white">{{ $userName }}</span>. Aquí tienes una visión general de tu actividad.</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Estado del sistema -->
                <div class="hidden md:flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-sm text-white font-medium">Sistema activo</span>
                </div>
                
                <!-- Campana de notificaciones -->
                @if($userRole === 'admin' || $userRole === 'owner')
                
                <!-- Cuenta Bancaria (solo para owner) -->
                @if($userRole === 'owner')
                    @php
                        $hasBankAccount = DB::table('bank_accounts')->where('user_id', auth()->id())->exists();
                    @endphp
                    <button 
                        onclick="Livewire.dispatch('openBankAccountModal')" 
                        class="relative bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all duration-200 hover:scale-105 cursor-pointer"
                        title="{{ $hasBankAccount ? 'Configuración de cuenta bancaria' : 'Configura tu cuenta bancaria' }}"
                    >
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        @if(!$hasBankAccount)
                        <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                            !
                        </span>
                        @endif
                    </button>
                @endif
                
                <div class="relative" 
                     x-data="{ 
                         open: false, 
                         isDraggingNotification: false,
                         preventClickAway: false,
                         init() {
                             window.addEventListener('notification-drag-start', () => { 
                                 this.isDraggingNotification = true;
                                 this.preventClickAway = true;
                             });
                             window.addEventListener('notification-drag-end', () => { 
                                 this.isDraggingNotification = false;
                                 setTimeout(() => { this.preventClickAway = false; }, 300);
                             });
                             
                             // Capturar mouseup/touchend global para finalizar arrastre
                             const handleGlobalEnd = () => {
                                 if (this.isDraggingNotification) {
                                     window.dispatchEvent(new CustomEvent('notification-drag-end'));
                                 }
                             };
                             window.addEventListener('mouseup', handleGlobalEnd);
                             window.addEventListener('touchend', handleGlobalEnd);
                         }
                     }" 
                     @click.away="if(!preventClickAway) { open = false; }">
                    <button @click="open = !open" class="relative bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-xl p-3 transition-all duration-200 hover:scale-105 cursor-pointer">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(count($alertas) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                            {{ count($alertas) }}
                        </span>
                        @endif
                    </button>
                    
                    <!-- Dropdown de alertas -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl !border !border-slate-200 z-50 max-h-[32rem] overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header del dropdown -->
                        <div class="!bg-gradient-to-r from-orange-500 via-amber-500 to-teal-500 px-4 py-3 !border-b !border-orange-400">
                            <div class="flex items-center justify-between">
                                <h3 class="text-white font-bold text-sm">Notificaciones</h3>
                                @if(count($alertas) > 0)
                                <span class="bg-white/20 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ count($alertas) }} {{ count($alertas) == 1 ? 'alerta' : 'alertas' }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Lista de alertas -->
                        <div class="max-h-96 overflow-y-auto">
                            @if(count($alertas) > 0)
                                @foreach($alertas as $alerta)
                                @if(isset($alerta['url']))
                                <a href="{{ $alerta['url'] }}" class="block p-4 !border-b !border-slate-100 hover:!border-l-4 transition-all duration-200 cursor-pointer
                                    @if($alerta['tipo'] === 'danger') hover:bg-red-50 hover:!border-l-red-500
                                    @elseif($alerta['tipo'] === 'warning') hover:bg-yellow-50 hover:!border-l-yellow-500
                                    @else hover:bg-blue-50 hover:!border-l-blue-500 @endif">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200
                                                @if($alerta['tipo'] === 'danger') bg-red-100
                                                @elseif($alerta['tipo'] === 'warning') bg-yellow-100
                                                @else bg-blue-100 @endif">
                                                @if($alerta['tipo'] === 'danger')
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($alerta['tipo'] === 'warning')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="font-semibold text-sm text-slate-900">{{ $alerta['titulo'] }}</h4>
                                                <span class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-1 rounded-full
                                                    @if($alerta['tipo'] === 'danger') bg-red-100 text-red-700
                                                    @elseif($alerta['tipo'] === 'warning') bg-yellow-100 text-yellow-700
                                                    @else bg-blue-100 text-blue-700 @endif">
                                                    {{ $alerta['count'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerta['mensaje'] }}</p>
                                        </div>
                                    </div>
                                </a>
                                @elseif(isset($alerta['action']))
                                <div onclick="Livewire.dispatch('{{ $alerta['action'] }}')" class="block p-4 !border-b !border-slate-100 hover:!border-l-4 transition-all duration-200 cursor-pointer
                                    @if($alerta['tipo'] === 'danger') hover:bg-red-50 hover:!border-l-red-500
                                    @elseif($alerta['tipo'] === 'warning') hover:bg-yellow-50 hover:!border-l-yellow-500
                                    @else hover:bg-blue-50 hover:!border-l-blue-500 @endif">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200
                                                @if($alerta['tipo'] === 'danger') bg-red-100
                                                @elseif($alerta['tipo'] === 'warning') bg-yellow-100
                                                @else bg-blue-100 @endif">
                                                @if($alerta['tipo'] === 'danger')
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($alerta['tipo'] === 'warning')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="font-semibold text-sm text-slate-900">{{ $alerta['titulo'] }}</h4>
                                                <span class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-1 rounded-full
                                                    @if($alerta['tipo'] === 'danger') bg-red-100 text-red-700
                                                    @elseif($alerta['tipo'] === 'warning') bg-yellow-100 text-yellow-700
                                                    @else bg-blue-100 text-blue-700 @endif">
                                                    {{ $alerta['count'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerta['mensaje'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200
                                                @if($alerta['tipo'] === 'danger') bg-red-100
                                                @elseif($alerta['tipo'] === 'warning') bg-yellow-100
                                                @else bg-blue-100 @endif">
                                                @if($alerta['tipo'] === 'danger')
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($alerta['tipo'] === 'warning')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="font-semibold text-sm text-slate-900">{{ $alerta['titulo'] }}</h4>
                                                <span class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-1 rounded-full
                                                    @if($alerta['tipo'] === 'danger') bg-red-100 text-red-700
                                                    @elseif($alerta['tipo'] === 'warning') bg-yellow-100 text-yellow-700
                                                    @else bg-blue-100 text-blue-700 @endif">
                                                    {{ $alerta['count'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerta['mensaje'] }}</p>
                                        </div>
                                    </div>
                                </a>
                                @elseif($alerta['tipo'] === 'info' && isset($alerta['id']))
                                <div 
                                    x-data="{ 
                                        startX: 0, 
                                        currentX: 0, 
                                        isDragging: false,
                                        showTooltip: false,
                                        alertId: '{{ $alerta['id'] }}',
                                        init() {
                                            // Escuchar el evento global de finalización
                                            window.addEventListener('notification-drag-end', () => {
                                                if (this.isDragging) {
                                                    this.finalizeDrag();
                                                }
                                            });
                                        },
                                        startTouch(e) {
                                            this.showTooltip = false;
                                            this.startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
                                            this.isDragging = true;
                                            window.dispatchEvent(new CustomEvent('notification-drag-start'));
                                            e.preventDefault();
                                        },
                                        moveTouch(e) {
                                            if (!this.isDragging) return;
                                            this.currentX = (e.type.includes('mouse') ? e.clientX : e.touches[0].clientX) - this.startX;
                                            if (this.currentX < 0) this.currentX = 0;
                                            $el.style.transform = 'translateX(' + this.currentX + 'px)';
                                            $el.style.opacity = 1 - (this.currentX / 250);
                                        },
                                        finalizeDrag() {
                                            if (!this.isDragging) return;
                                            if (this.currentX > 170) {
                                                $wire.marcarAlertaVista(this.alertId);
                                            } else {
                                                $el.style.transform = 'translateX(0)';
                                                $el.style.opacity = '1';
                                            }
                                            this.isDragging = false;
                                            this.currentX = 0;
                                        }
                                    }"
                                    @mousedown="startTouch($event)"
                                    @mousemove="moveTouch($event)"
                                    @touchstart="startTouch($event)"
                                    @touchmove="moveTouch($event)"
                                    @mouseenter="if(!isDragging) { showTooltip = true; }"
                                    @mouseleave="showTooltip = false;"
                                    style="transition: transform 0.3s ease, opacity 0.3s ease; touch-action: pan-y;"
                                    class="p-4 !border-b !border-slate-100 hover:bg-slate-50 transition-colors relative select-none cursor-pointer overflow-hidden">
                                    
                                    <!-- Ícono de tacho de basura rojo que aparece al deslizar -->
                                    <div x-show="isDragging && currentX > 50" 
                                         class="absolute left-0 top-0 h-full w-20 flex items-center justify-center bg-red-500 transition-opacity"
                                         style="opacity: 0.9;">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Tooltip "Desliza para eliminar" -->
                                    <div x-show="showTooltip && !isDragging" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-3 py-1.5 rounded-lg shadow-lg whitespace-nowrap z-10">
                                        Desliza para eliminar
                                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-900"></div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-blue-100">
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="font-semibold text-sm text-slate-900">{{ $alerta['titulo'] }}</h4>
                                                <span 
                                                    class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                                                    {{ $alerta['count'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerta['mensaje'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="p-4 !border-b !border-slate-100 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                                @if($alerta['tipo'] === 'danger') bg-red-100
                                                @elseif($alerta['tipo'] === 'warning') bg-yellow-100
                                                @else bg-blue-100 @endif">
                                                @if($alerta['tipo'] === 'danger')
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($alerta['tipo'] === 'warning')
                                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <h4 class="font-semibold text-sm text-slate-900">{{ $alerta['titulo'] }}</h4>
                                                <span class="flex-shrink-0 ml-2 text-xs font-bold px-2 py-1 rounded-full
                                                    @if($alerta['tipo'] === 'danger') bg-red-100 text-red-700
                                                    @elseif($alerta['tipo'] === 'warning') bg-yellow-100 text-yellow-700
                                                    @else bg-blue-100 text-blue-700 @endif">
                                                    {{ $alerta['count'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerta['mensaje'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            @else
                                <div class="p-8 text-center">
                                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-slate-500 font-medium">No hay notificaciones</p>
                                    <p class="text-xs text-slate-400 mt-1">Estás al día con todo</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- TARJETAS KPI - MÉTRICAS PRINCIPALES -->
<!-- ============================================ -->
@if($userRole === 'admin' || $userRole === 'owner')
<div class="fade-in mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Proformas Activas -->
        <div class="!bg-white rounded-2xl shadow-md !border !border-slate-100 p-5 hover:shadow-xl hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 !bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-teal-500/30">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600">Proformas Activas</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-slate-900">{{ $proformasActivas }}</p>
                <div class="flex items-center text-xs">
                    @if($cambioProformas > 0)
                        <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-green-600 font-semibold">{{ $cambioProformas }}%</span>
                    @elseif($cambioProformas < 0)
                        <svg class="w-4 h-4 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-red-600 font-semibold">{{ abs($cambioProformas) }}%</span>
                    @else
                        <span class="text-slate-500 font-medium">0%</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Proformas con Costos Desactualizados -->
        <div class="!bg-white rounded-2xl shadow-md !border !border-slate-100 p-5 hover:shadow-xl hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 !bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-orange-500/30">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600">Costos Desactualizados</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-slate-900">{{ $proformasConCostosDesactualizados }}</p>
                <span class="text-xs text-orange-600 font-medium">Requieren recálculo</span>
            </div>
        </div>
        
        <!-- Órdenes en Producción -->
        <div class="!bg-white rounded-2xl shadow-md !border !border-slate-100 p-5 hover:shadow-xl hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 !bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-teal-500/30">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600">En Producción</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-slate-900">{{ $ordenesEnProduccion }}</p>
                <div class="flex items-center text-xs">
                    @if($cambioOrdenes > 0)
                        <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-green-600 font-semibold">{{ $cambioOrdenes }}%</span>
                    @elseif($cambioOrdenes < 0)
                        <svg class="w-4 h-4 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-red-600 font-semibold">{{ abs($cambioOrdenes) }}%</span>
                    @else
                        <span class="text-slate-500 font-medium">0%</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Rentabilidad Estimada (MTD) -->
        <div class="!bg-white rounded-2xl shadow-md !border !border-slate-100 p-5 hover:shadow-xl hover:scale-105 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="w-12 h-12 !bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-orange-500/30">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-600">Rentabilidad (MTD)</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-slate-900">${{ number_format($rentabilidadMes, 0) }}</p>
                <div class="flex items-center text-xs">
                    @if($cambioRentabilidad > 0)
                        <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-green-600 font-semibold">${{ number_format($cambioRentabilidad, 0) }}</span>
                    @elseif($cambioRentabilidad < 0)
                        <svg class="w-4 h-4 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span class="text-red-600 font-semibold">${{ number_format(abs($cambioRentabilidad), 0) }}</span>
                    @else
                        <span class="text-slate-500 font-medium">$0</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- WIDGET DE ANÁLISIS DE RENTABILIDAD -->
<!-- ============================================ -->
@if($userRole === 'admin' || $userRole === 'owner')
<div class="fade-in mb-6">
    <div class="!bg-white rounded-2xl shadow-lg !border !border-slate-100 p-6">
        <div class="flex items-center mb-5">
            <div class="w-12 h-12 !bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-teal-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold !bg-gradient-to-r from-teal-600 to-emerald-600 bg-clip-text text-transparent">Análisis de Rentabilidad</h3>
                <p class="text-sm text-slate-600 mt-1">📊 Órdenes completadas - {{ now()->format('F Y') }}</p>
            </div>
        </div>
        
        @if($totalOrdenesAnalizadas > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Sección 1: Métricas Generales -->
            <div class="!bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 !border !border-blue-200">
                <h4 class="text-sm font-bold text-blue-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    📊 Resumen del Mes
                </h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-blue-800">Órdenes Completadas:</span>
                        <span class="text-sm font-bold text-blue-900">{{ $totalOrdenesAnalizadas }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-blue-800">Valor Total Vendido:</span>
                        <span class="text-sm font-bold text-blue-900">${{ number_format($valorTotalOrdenes, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-blue-800">Ganancia Total:</span>
                        <span class="text-sm font-bold text-green-700">${{ number_format($gananciaTotal, 2) }}</span>
                    </div>
                    <div class="!bg-white/50 rounded p-2 mt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-blue-800 font-medium">Margen Promedio:</span>
                            <span class="text-lg font-bold text-blue-900">{{ number_format($margenPromedioMes, 1) }}%</span>
                        </div>
                        @if($margenMesPasado > 0)
                        <div class="flex items-center justify-end mt-1">
                            @if($tendenciaMargen >= 0)
                                <svg class="w-3 h-3 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-xs text-green-600 font-semibold">+{{ number_format($tendenciaMargen, 1) }}% vs mes pasado</span>
                            @else
                                <svg class="w-3 h-3 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-xs text-red-600 font-semibold">{{ number_format($tendenciaMargen, 1) }}% vs mes pasado</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    @if($ordenMayorMargen && $ordenMenorMargen)
                    <div class="!bg-white rounded p-2 mt-3 !border-t-2 !border-blue-200">
                        <div class="text-xs text-blue-800 font-medium mb-2">Órdenes Destacadas:</div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-blue-700">🏆 Mayor Margen:</span>
                            <div class="text-right">
                                <div class="font-semibold text-blue-900">{{ $ordenMayorMargen['order_number'] }}</div>
                                <div class="text-green-700 font-bold">{{ number_format($ordenMayorMargen['margen'], 1) }}%</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-blue-700">⚠️ Menor Margen:</span>
                            <div class="text-right">
                                <div class="font-semibold text-blue-900">{{ $ordenMenorMargen['order_number'] }}</div>
                                <div class="{{ $ordenMenorMargen['margen'] < 10 ? 'text-red-700' : 'text-orange-700' }} font-bold">
                                    {{ number_format($ordenMenorMargen['margen'], 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Sección 2: Productos Más Vendidos -->
            <div class="!bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg p-4 !border !border-orange-200">
                <h4 class="text-sm font-bold text-orange-900 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    🎯 Productos Más Vendidos
                </h4>
                <div class="space-y-2">
                    @if(count($productosMasVendidos) > 0)
                        @foreach($productosMasVendidos as $index => $producto)
                        <div class="!bg-white/50 rounded p-3 !border !border-orange-100">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0 pr-2">
                                    <div class="font-semibold text-sm text-gray-900 truncate" title="{{ $producto['nombre'] }}">
                                        {{ $producto['nombre'] }}
                                    </div>
                                    <div class="text-xs text-orange-700 mt-1">
                                        {{ $producto['cantidad_ordenes'] }} {{ $producto['cantidad_ordenes'] == 1 ? 'orden' : 'órdenes' }}
                                        · ${{ number_format($producto['ventas_totales'], 0) }}
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-lg font-bold {{ $index == 0 ? 'text-orange-600' : 'text-orange-500' }}">
                                        {{ number_format($producto['porcentaje_ordenes'], 0) }}%
                                    </div>
                                    <div class="text-xs text-orange-600">órdenes</div>
                                </div>
                            </div>
                            
                            <!-- Barra de porcentaje -->
                            <div class="relative">
                                <div class="w-full bg-white rounded-full h-2 overflow-hidden">
                                    <div class="{{ $index == 0 ? 'bg-orange-500' : ($index == 1 ? 'bg-orange-400' : 'bg-orange-300') }} h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $producto['porcentaje_ordenes'] }}%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-orange-700">{{ number_format($producto['porcentaje_ventas'], 1) }}% en ventas</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(count($productosMasVendidos) > 3)
                        <div class="text-center pt-2">
                            <span class="text-xs text-orange-600">+{{ count($productosMasVendidos) - 3 }} productos más</span>
                        </div>
                        @endif
                    @else
                        <p class="text-xs text-purple-700 text-center py-4">No hay datos suficientes</p>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-slate-600 font-medium">No hay órdenes completadas este mes</p>
            <p class="text-xs text-slate-500 mt-1">Los datos aparecerán cuando completes órdenes</p>
        </div>
        @endif
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- GRÁFICOS: ÓRDENES Y PROFORMAS -->
<!-- ============================================ -->
@if($userRole === 'admin' || $userRole === 'owner')
<div class="fade-in mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Resumen de Proformas del mes (25%) -->
        <div class="!bg-white rounded-2xl shadow-lg !border !border-slate-100 p-5">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
                <div class="w-8 h-8 !bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-2 shadow-md shadow-blue-500/30">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="!bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Proformas - {{ now()->format('M Y') }}</span>
            </h3>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-xs font-medium text-slate-700">Creadas</span>
                    </div>
                    <span class="text-lg font-bold text-blue-700">{{ $proformasCreadasMes }}</span>
                </div>
                
                <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-xs font-medium text-slate-700">Aprobadas</span>
                    </div>
                    <span class="text-lg font-bold text-green-700">{{ $proformasAprobadasMes }}</span>
                </div>
                
                <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-xs font-medium text-slate-700">Expiradas</span>
                    </div>
                    <span class="text-lg font-bold text-red-700">{{ $proformasExpiradasMes }}</span>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de evolución de proformas (50%) -->
        <div class="lg:col-span-2 !bg-white rounded-2xl shadow-lg !border !border-slate-100 p-5">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 !bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-2 shadow-md shadow-blue-500/30">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
                <span class="text-base font-bold !bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">📊 Evolución - Últimos 6 Meses</span>
            </div>
            <div id="proformasLineChart" style="height: 240px;"></div>
            <div class="flex items-center justify-center gap-4 text-xs mt-3 flex-wrap">
                <div class="flex items-center bg-blue-50 px-3 py-1.5 rounded-lg">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-slate-700 font-medium">Creadas</span>
                </div>
                <div class="flex items-center bg-green-50 px-3 py-1.5 rounded-lg">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                    <span class="text-slate-700 font-medium">Aprobadas</span>
                </div>
                <div class="flex items-center bg-red-50 px-3 py-1.5 rounded-lg">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-slate-700 font-medium">Expiradas</span>
                </div>
            </div>
        </div>
        
        <!-- Estado de Órdenes (25%) -->
        <div class="!bg-white rounded-2xl shadow-lg !border !border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 !bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-2 shadow-md shadow-blue-500/30">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-base font-bold !bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">📦 Estado de Órdenes</span>
                </div>
                <span class="text-xs text-slate-500 font-medium">Total: {{ $totalOrdenes }}</span>
            </div>
            <div class="flex justify-center mb-3">
                <div id="ordenesDonutChart" style="width: 200px; height: 200px;"></div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="!bg-gradient-to-br from-yellow-50 to-yellow-100 !border-2 !border-yellow-200 rounded-xl p-3 text-center hover:shadow-md transition-all">
                    <div class="flex items-center justify-center mb-1">
                        <div class="w-2 h-2 bg-yellow-400 rounded-full mr-1"></div>
                        <span class="text-xs font-semibold text-slate-700">Pendiente</span>
                    </div>
                    <p class="text-xl font-bold text-yellow-700">{{ $ordenesPendientes }}</p>
                    <p class="text-xs text-yellow-600">{{ $porcentajeOrdenes['pendientes'] }}%</p>
                </div>
                <div class="!bg-gradient-to-br from-cyan-50 to-cyan-100 !border-2 !border-cyan-200 rounded-xl p-3 text-center hover:shadow-md transition-all">
                    <div class="flex items-center justify-center mb-1">
                        <div class="w-2 h-2 bg-cyan-400 rounded-full mr-1"></div>
                        <span class="text-xs font-semibold text-slate-700">Aprobadas</span>
                    </div>
                    <p class="text-xl font-bold text-cyan-700">{{ $ordenesAprobadas }}</p>
                    <p class="text-xs text-cyan-600">{{ $porcentajeOrdenes['aprobadas'] }}%</p>
                </div>
                <div class="!bg-gradient-to-br from-indigo-50 to-indigo-100 !border-2 !border-indigo-200 rounded-xl p-3 text-center hover:shadow-md transition-all">
                    <div class="flex items-center justify-center mb-1">
                        <div class="w-2 h-2 bg-indigo-400 rounded-full mr-1"></div>
                        <span class="text-xs font-semibold text-slate-700">Producción</span>
                    </div>
                    <p class="text-xl font-bold text-indigo-700">{{ $ordenesEnProduccion }}</p>
                    <p class="text-xs text-indigo-600">{{ $porcentajeOrdenes['produccion'] }}%</p>
                </div>
                <div class="!bg-gradient-to-br from-green-50 to-green-100 !border-2 !border-green-200 rounded-xl p-3 text-center hover:shadow-md transition-all">
                    <div class="flex items-center justify-center mb-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-1"></div>
                        <span class="text-xs font-semibold text-slate-700">Finalizadas</span>
                    </div>
                    <p class="text-xl font-bold text-green-700">{{ $ordenesCompletadas }}</p>
                    <p class="text-xs text-green-600">{{ $porcentajeOrdenes['completadas'] }}%</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- ACTIVIDAD RECIENTE -->
<!-- ============================================ -->
@if($userRole === 'admin' || $userRole === 'owner')
<div class="fade-in mb-6">
    <div class="!bg-white rounded-2xl shadow-lg !border !border-slate-100 p-6">
        <h3 class="text-xl font-bold mb-5 flex items-center">
            <div class="w-12 h-12 !bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-green-500/30">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <span class="!bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Actividad reciente</span>
        </h3>
        <div class="space-y-3">
            @forelse($actividadReciente as $actividad)
            <div class="rounded-xl !border-2 overflow-hidden transition-all hover:shadow-xl
                @if($actividad->tipo === 'proforma_creada') !border-blue-200 !bg-blue-50/30
                @elseif($actividad->tipo === 'costo_cambiado') !border-orange-200 !bg-orange-50/30
                @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'pending') !border-yellow-200 !bg-yellow-50/30
                @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'approved') !border-cyan-200 !bg-cyan-50/30
                @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'in_production') !border-indigo-200 !bg-indigo-50/30
                @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'completed') !border-green-200 !bg-green-50/30
                @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'cancelled') !border-red-200 !bg-red-50/30
                @else !border-slate-200 !bg-slate-50/30 @endif">
                    <div class="flex items-start p-4 transition-colors activity-block
                        @if($actividad->tipo === 'proforma_creada') hover:!bg-blue-100/50
                        @elseif($actividad->tipo === 'costo_cambiado') hover:!bg-orange-100/50
                        @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'pending') hover:!bg-yellow-100/50
                        @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'approved') hover:!bg-cyan-100/50
                        @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'in_production') hover:!bg-indigo-100/50
                        @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'completed') hover:!bg-green-100/50
                        @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'cancelled') hover:!bg-red-100/50
                        @else hover:!bg-slate-100/50 @endif"
                        @if(
                            ($actividad->tipo === 'proforma_creada' && $actividad->configuraciones && count($actividad->configuraciones) > 0)
                            || ($actividad->tipo === 'proforma_a_orden')
                        )
                            onclick="if(!event.target.classList.contains('proforma-link')){this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.chevron-icon').classList.toggle('rotate-180');}"
                        @endif
                    >
                    <!-- Icono según tipo de actividad -->
        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 flex-shrink-0 shadow-lg
            @if($actividad->tipo === 'proforma_creada') !bg-gradient-to-br from-blue-500 to-blue-600 shadow-blue-500/40
            @elseif($actividad->tipo === 'costo_cambiado') !bg-gradient-to-br from-orange-500 to-orange-600 shadow-orange-500/40
            @elseif($actividad->tipo === 'orden_produccion') !bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-indigo-500/40
            @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'pending') !bg-gradient-to-br from-yellow-500 to-yellow-600 shadow-yellow-500/40
            @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'approved') !bg-gradient-to-br from-cyan-500 to-cyan-600 shadow-cyan-500/40
            @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'in_production') !bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-indigo-500/40
            @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'completed') !bg-gradient-to-br from-green-500 to-green-600 shadow-green-500/40
            @elseif($actividad->tipo === 'proforma_a_orden' && $actividad->estado === 'cancelled') !bg-gradient-to-br from-red-500 to-red-600 shadow-red-500/40
            @else !bg-gradient-to-br from-slate-500 to-slate-600 shadow-slate-500/40
            @endif">
            @if($actividad->tipo === 'proforma_creada')
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                </svg>
            @elseif($actividad->tipo === 'costo_cambiado')
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
            @elseif($actividad->tipo === 'orden_produccion')
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            @elseif($actividad->tipo === 'proforma_a_orden')
                @php
                    $iconColor = 'text-white';
                @endphp
                <svg class="w-6 h-6 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                    @if($actividad->estado === 'pending')
                        <circle cx="10" cy="10" r="8" fill="currentColor" />
                    @elseif($actividad->estado === 'approved')
                        <rect x="4" y="4" width="12" height="12" rx="3" />
                    @elseif($actividad->estado === 'in_production')
                        <path d="M5 8a1 1 0 011-1h8a1 1 0 011 1v6a2 2 0 01-2 2H7a2 2 0 01-2-2V8zm2-3a3 3 0 016 0v1H7V5z" />
                    @elseif($actividad->estado === 'completed')
                        <path d="M7 10l2 2 4-4" stroke="currentColor" stroke-width="2" fill="none" />
                    @elseif($actividad->estado === 'cancelled')
                        <line x1="6" y1="6" x2="14" y2="14" stroke="currentColor" stroke-width="2" />
                        <line x1="14" y1="6" x2="6" y2="14" stroke="currentColor" stroke-width="2" />
                    @else
                        <!-- Icono de edición/actualización por defecto -->
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    @endif
                </svg>
            @endif
        </div>
                    
                    <!-- Contenido -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-800">
                            @if($actividad->tipo === 'proforma_creada')
                                @php
                                    \Carbon\Carbon::setLocale('es');
                                    $totalConfiguraciones = $actividad->configuraciones ? count($actividad->configuraciones) : 0;
                                    $ultimaActualizacion = $actividad->configuraciones ? collect($actividad->configuraciones)->max('updated_at') : null;
                                    $configuracionesNuevas = 0;
                                    if ($ultimaActualizacion) {
                                        $configuracionesNuevas = collect($actividad->configuraciones)->filter(function($config) use ($ultimaActualizacion) {
                                            $diffSeconds = \Carbon\Carbon::parse($ultimaActualizacion)->diffInSeconds(\Carbon\Carbon::parse($config->updated_at));
                                            return $diffSeconds <= 30;
                                        })->count();
                                    }
                                @endphp
                                @if($totalConfiguraciones > 1)
                                    <span class="font-semibold text-slate-900">{{ $actividad->usuario }}</span> cambió las configuraciones de la proforma 
                                    <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $actividad->proforma_id }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline proforma-link bg-blue-100 px-2 py-0.5 rounded">#{{ $actividad->referencia }}</a>
                                    @if($configuracionesNuevas > 0)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md">
                                            +{{ $configuracionesNuevas }} configuración{{ $configuracionesNuevas > 1 ? 'es' : '' }}
                                        </span>
                                    @endif
                                @else
                                    <span class="font-semibold text-slate-900">{{ $actividad->usuario }}</span> creó la proforma 
                                    <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $actividad->proforma_id }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline proforma-link bg-blue-100 px-2 py-0.5 rounded">#{{ $actividad->referencia }}</a>
                                    @if($totalConfiguraciones == 1)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md">
                                            +1 configuración
                                        </span>
                                    @endif
                                @endif
                            @elseif($actividad->tipo === 'costo_cambiado')
                                Los <span class="font-semibold text-slate-900">costos indirectos</span> cambiaron al <span class="font-bold text-white bg-gradient-to-r from-orange-500 to-orange-600 px-2 py-1 rounded shadow-md">{{ $actividad->referencia }}</span>
                            @elseif($actividad->tipo === 'proforma_a_orden')
                                @php
                                    $estado = $actividad->estado;
                                    $usuario = $actividad->usuario;
                                    $ordenId = $actividad->orden_id;
                                    $proformaId = $actividad->proforma_id;
                                    $proformaNum = $actividad->proforma_numero;
                                @endphp
                                @if($estado === 'pending')
                                    <span class="font-semibold text-slate-900">{{ $usuario }}</span> ha pasado a orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @elseif($estado === 'approved')
                                    La orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> del usuario <span class="font-semibold text-slate-900">{{ $usuario }}</span> ha sido <span class="font-bold text-white bg-gradient-to-r from-cyan-500 to-cyan-600 px-2 py-1 rounded shadow-md">aprobada</span> para la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @elseif($estado === 'in_production')
                                    La orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> del usuario <span class="font-semibold text-slate-900">{{ $usuario }}</span> está <span class="font-bold text-white bg-gradient-to-r from-indigo-500 to-indigo-600 px-2 py-1 rounded shadow-md">en producción</span> para la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @elseif($estado === 'completed')
                                    La orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> del usuario <span class="font-semibold text-slate-900">{{ $usuario }}</span> ha sido <span class="font-bold text-white bg-gradient-to-r from-green-500 to-green-600 px-2 py-1 rounded shadow-md">finalizada</span> para la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @elseif($estado === 'cancelled')
                                    La orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> del usuario <span class="font-semibold text-slate-900">{{ $usuario }}</span> ha sido <span class="font-bold text-white bg-gradient-to-r from-red-500 to-red-600 px-2 py-1 rounded shadow-md">cancelada</span> para la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @else
                                    <span class="font-semibold text-slate-900">{{ $usuario }}</span> ha actualizado la orden <a href="{{ route('admin.orders.index') }}?order_id={{ $ordenId }}" class="font-mono font-bold text-indigo-700 hover:text-indigo-900 hover:underline bg-indigo-100 px-2 py-0.5 rounded">#{{ $ordenId }}</a> para la proforma <a href="{{ route('admin.proformas.index') }}?proforma_id={{ $proformaId }}" class="font-mono font-bold text-blue-700 hover:text-blue-900 hover:underline bg-blue-100 px-2 py-0.5 rounded">#{{ $proformaNum }}</a>
                                @endif
                            @endif
                        </p>
                        <p class="text-xs font-medium text-slate-600 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($actividad->created_at)->locale('es')->diffForHumans() }}
                        </p>
                    </div>
                    
                    <!-- Chevron para expandir (solo si hay configuraciones) -->
                        @if(
                            ($actividad->tipo === 'proforma_creada' && $actividad->configuraciones && count($actividad->configuraciones) > 0)
                            || ($actividad->tipo === 'proforma_a_orden')
                        )
                        <div class="ml-2 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400 transition-transform chevron-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @endif
                </div>
                
                <!-- Configuraciones colapsables -->
                @if($actividad->tipo === 'proforma_creada' && $actividad->configuraciones && count($actividad->configuraciones) > 0)
                <div class="hidden !bg-gradient-to-r from-slate-50 to-slate-100 !border-t-2 !border-slate-200 p-4 pl-16 space-y-2">
                    @php
                        \Carbon\Carbon::setLocale('es');
                        $ultimaActualizacion = collect($actividad->configuraciones)->max('updated_at');
                    @endphp
                    @foreach($actividad->configuraciones as $config)
                    @php
                        $esNueva = \Carbon\Carbon::parse($config->updated_at)->greaterThanOrEqualTo(\Carbon\Carbon::parse($ultimaActualizacion)->subSeconds(10));
                    @endphp
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-slate-800"><span class="font-semibold text-slate-900">{{ $config->producto }}</span></span>
                            @if($config->quantity > 1)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-slate-200 text-slate-700">
                                    x{{ $config->quantity }}
                                </span>
                            @endif
                            @if($esNueva)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    +{{ $config->quantity }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-green-700">${{ number_format($config->price, 2) }}</span>
                            <span class="text-slate-600">{{ \Carbon\Carbon::parse($config->created_at)->locale('es')->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                @if($actividad->tipo === 'proforma_a_orden')
                <div class="hidden bg-slate-50 border-t border-slate-200 p-4 pl-16 space-y-2">
                    @php
                        \Carbon\Carbon::setLocale('es');
                    @endphp
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="8" fill="currentColor" />
                            </svg>
                            <span class="text-slate-800">Estado de la orden: <span class="font-semibold text-slate-900">{{ ucfirst($actividad->estado) }}</span></span>
                        </div>
                        @if($actividad->estado === 'in_production')
                        <div class="ml-4 text-slate-600">
                            @php
                                $order = DB::table('orders')->where('id', $actividad->orden_id)->first();
                            @endphp
                            @if($order && $order->estimated_finish_at)
                                @php
                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($order->estimated_finish_at), false);
                                @endphp
                                <span class="font-semibold">Tiempo estimado de finalización:</span>
                                <span class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($order->estimated_finish_at)->format('d/m/Y') }}</span>
                                @if($daysRemaining > 0)
                                    <span class="text-xs text-green-600">{{ ceil($daysRemaining) }} día(s) restantes</span>
                                @elseif($daysRemaining < 0)
                                    <span class="text-xs text-red-600">Retrasado {{ abs(floor($daysRemaining)) }} día(s)</span>
                                @else
                                    <span class="text-xs text-green-600">Finaliza hoy</span>
                                @endif
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-slate-700">No hay actividad reciente</p>
                <p class="text-xs mt-1 text-slate-600">Los eventos del sistema aparecerán aquí</p>
            </div>
            @endforelse
        </div>
        <!-- Controles de paginación reactiva -->
        <div class="flex justify-center items-center gap-3 mt-2">
            <nav aria-label="Paginación actividad reciente" class="flex items-center gap-3 w-full justify-center">
                <div class="flex items-center w-full">
                    <div class="flex-1 flex justify-start">
                        <button wire:click="anteriorPaginaActividad" @if($paginaActividad <= 1) disabled aria-disabled="true" @endif
                            class="flex items-center gap-1 px-2 py-1 rounded-lg border border-blue-200 bg-blue-50 text-blue-600 font-medium shadow-sm hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-300 transition disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                            aria-label="Página anterior" tabindex="0">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            <span class="hidden sm:inline">Anterior</span>
                        </button>
                    </div>
                    <div class="flex flex-col items-center">
                        <div x-data="{
                            paginaActual: @entangle('paginaActividad'),
                            totalPaginas: {{ ceil($totalActividadReciente / $actividadPorPagina) }},
                            todasPaginas() {
                                let arr = [];
                                for (let i = 1; i <= this.totalPaginas; i++) arr.push(i);
                                return arr;
                            },
                            carouselOffset() {
                                let offset;
                                if (this.totalPaginas <= 3) {
                                    offset = 0;
                                } else if (this.paginaActual === 1) {
                                    offset = 0;
                                } else if (this.paginaActual === this.totalPaginas) {
                                    offset = -((this.totalPaginas - 3) * 1.75);
                                } else {
                                    offset = -((this.paginaActual - 2) * 1.75);
                                }
                                return offset + 'rem';
                            }
                        }" class="flex gap-1.5 items-center">
                            <!-- Botón ir al inicio -->
                            <button
                                @click="$wire.actualizarActividadReciente(1); paginaActual = 1"
                                :disabled="paginaActual == 1"
                                class="w-6 h-6 rounded-lg border flex items-center justify-center text-xs font-semibold bg-blue-50 text-blue-600 border-blue-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-blue-100"
                                aria-label="Ir a la primera página"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            </button>
                            <!-- Carousel de números -->
                            <div class="relative w-[5.5rem] h-8 overflow-hidden flex items-center justify-start">
                                <div class="flex gap-1 transition-transform duration-500 ease-in-out" :style="'transform: translateX(' + carouselOffset() + ');'">
                                    <template x-for="(i, idx) in todasPaginas()" :key="i">
                                        <button
                                            @click="$wire.actualizarActividadReciente(i); paginaActual = i"
                                            :class="
                                                paginaActual == i
                                                    ? 'w-6 h-6 rounded-full border-2 flex items-center justify-center text-xs font-bold bg-blue-500 text-white border-blue-500 shadow-md scale-110 z-10'
                                                    : 'w-6 h-6 rounded-full border flex items-center justify-center text-xs font-semibold bg-white text-blue-600 border-blue-200 hover:bg-blue-50 hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300'
                                            "
                                            :aria-label="'Ir a página ' + i"
                                            :aria-current="paginaActual == i ? 'page' : null"
                                            style="transition: all 0.3s ease;"
                                        >
                                            <span x-text="i"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <!-- Botón ir al final -->
                            <button
                                @click="$wire.actualizarActividadReciente(totalPaginas); paginaActual = totalPaginas"
                                :disabled="paginaActual == totalPaginas"
                                class="w-6 h-6 rounded-lg border flex items-center justify-center text-xs font-semibold bg-blue-50 text-blue-600 border-blue-200 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-blue-100"
                                aria-label="Ir a la última página"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 flex justify-end">
                        <button wire:click="siguientePaginaActividad" @if($paginaActividad >= ceil($totalActividadReciente / $actividadPorPagina)) disabled aria-disabled="true" @endif
                            class="flex items-center gap-1 px-2 py-1 rounded-lg border border-blue-200 bg-blue-50 text-blue-600 font-medium shadow-sm hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-300 transition disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                            aria-label="Página siguiente" tabindex="0">
                            <span class="hidden sm:inline">Siguiente</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </nav>
        </div>
    </div>
        </div>
    </div>
</div>

@endif

@if($userRole === 'seller')
    <!-- Contenido específico para vendedores -->
    <div class="glass-card rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800">Panel de Ventas</h2>
        <p class="text-sm text-slate-600">Gestión de clientes y ventas.</p>
    </div>
@endif

@if($userRole === 'client')
    <!-- Contenido específico para clientes -->
    <div class="glass-card rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-slate-800">Panel de Usuario</h2>
        <p class="text-sm text-slate-600">Consulta tus pedidos y perfil.</p>
    </div>
@endif

@push('scripts')
<script>
    // Datos para gráfico de evolución de proformas
    window.dashboardProformasLine = {
        meses: {!! json_encode(array_map(fn($d) => $d['mes'], $datosGraficoProformas)) !!},
        creadas: {!! json_encode(array_map(fn($d) => $d['creadas'], $datosGraficoProformas)) !!},
        expiradas: {!! json_encode(array_map(fn($d) => $d['expiradas'], $datosGraficoProformas)) !!},
        aprobadas: {!! json_encode(array_map(fn($d) => $d['aprobadas'], $datosGraficoProformas)) !!}
    };

    // Datos para gráfico Donut de Estado de Órdenes
    window.dashboardDonutSeries = [
        {{ $ordenesPendientes }},
        {{ $ordenesAprobadas }},
        {{ $ordenesEnProduccion }},
        {{ $ordenesCompletadas }},
        {{ $ordenesCanceladas }}
    ];
    window.dashboardDonutLabels = ['Pendiente', 'Aprobadas', 'En producción', 'Finalizadas', 'Canceladas'];
</script>
@endpush

