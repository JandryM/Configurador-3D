<div x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    savedConfigs: [],
    _lsKey: 'prf_cfgs_global_{{ Auth::id() ?? 0 }}',
    initConfigs() {
        try { this.savedConfigs = JSON.parse(localStorage.getItem(this._lsKey) || '[]'); } catch(e) { this.savedConfigs = []; }
    },
    saveConfig(cfg) {
        const isDuplicate = this.savedConfigs.some(existing =>
            (existing.product_id ?? null) === (cfg.product_id ?? null) &&
            parseFloat(existing.parameters?.width  ?? 0).toFixed(3) === parseFloat(cfg.parameters?.width  ?? 0).toFixed(3) &&
            parseFloat(existing.parameters?.height ?? 0).toFixed(3) === parseFloat(cfg.parameters?.height ?? 0).toFixed(3) &&
            (existing.parameters?.color      ?? '') === (cfg.parameters?.color      ?? '') &&
            (existing.parameters?.glassColor ?? '') === (cfg.parameters?.glassColor ?? '')
        );
        if (isDuplicate) return;
        this.savedConfigs.push(cfg);
        localStorage.setItem(this._lsKey, JSON.stringify(this.savedConfigs));
    },
    removeConfig(i) {
        this.savedConfigs.splice(i, 1);
        localStorage.setItem(this._lsKey, JSON.stringify(this.savedConfigs));
    },
    updateConfigQty(i, delta) {
        const cfg = this.savedConfigs[i];
        const qty = Math.max(1, parseInt(cfg.quantity || 1));
        const unitPrice = parseFloat(cfg.calculated_price || 0) / qty;
        const newQty = Math.max(1, qty + delta);
        this.savedConfigs[i] = { ...cfg, quantity: newQty, calculated_price: unitPrice * newQty };
        localStorage.setItem(this._lsKey, JSON.stringify(this.savedConfigs));
    },
    clearSaved() {
        this.savedConfigs = [];
        localStorage.removeItem(this._lsKey);
    },
    get configsTotal() {
        return this.savedConfigs.reduce((s, c) => s + parseFloat(c.calculated_price || 0), 0);
    }
}"
     x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val)); initConfigs()"
     @configuraciones-guardadas.window="clearSaved()"
     :class="darkMode ? 'dark' : ''"
     class="w-full h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 dark:from-gray-950 dark:via-slate-950 dark:to-slate-900 px-3 sm:px-4 md:px-5 lg:px-6 py-3 pt-16 sm:pt-20 md:pt-20 lg:pt-20 overflow-hidden transition-colors duration-300">
        

        <!-- Mensajes -->
        @if (session()->has('message'))
            <div class="mb-2 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30 border-2 border-emerald-300 dark:border-emerald-700 p-2 sm:p-3 shadow-lg max-w-7xl mx-auto">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 dark:text-emerald-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs sm:text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-2 sm:gap-3 md:gap-4 max-w-[1920px] mx-auto h-[calc(100vh-5rem)] sm:h-[calc(100vh-6rem)] md:h-[calc(100vh-6rem)]">
            <!-- Panel Izquierdo: Configuración -->
            <div class="lg:col-span-3 space-y-2 sm:space-y-2.5 md:space-y-3 h-full overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: rgba(59,130,246,0.3) transparent;">

                <!-- Dimensiones -->
                <div class="bg-white/90 dark:bg-slate-900/95 backdrop-blur-sm rounded-lg md:rounded-xl shadow-md md:shadow-lg border-2 border-blue-100/50 dark:border-slate-800/50 p-2.5 sm:p-3 md:p-4">
                    <div class="flex items-center mb-2 sm:mb-3">
                        <div class="p-1.5 sm:p-2 mr-2 flex items-center justify-center">
                            <!-- Ícono de regla/medidas con efecto brillante -->
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <defs>
                                    <filter id="glow-dimensions" x="-40%" y="-40%" width="180%" height="180%">
                                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <g filter="url(#glow-dimensions)">
                                    <!-- Regla horizontal -->
                                    <rect x="5" y="10" width="14" height="4" rx="1" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <!-- Marcas de medida -->
                                    <line x1="7" y1="10" x2="7" y2="14" stroke="currentColor" stroke-width="1"/>
                                    <line x1="9" y1="10" x2="9" y2="14" stroke="currentColor" stroke-width="1"/>
                                    <line x1="11" y1="10" x2="11" y2="14" stroke="currentColor" stroke-width="1"/>
                                    <line x1="13" y1="10" x2="13" y2="14" stroke="currentColor" stroke-width="1"/>
                                    <line x1="15" y1="10" x2="15" y2="14" stroke="currentColor" stroke-width="1"/>
                                    <line x1="17" y1="10" x2="17" y2="14" stroke="currentColor" stroke-width="1"/>
                                </g>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 dark:text-slate-100">Dimensiones</h3>
                    </div>
                    
                    @foreach(['height' => 'Alto', 'width' => 'Ancho', 'depth' => 'Profundidad'] as $param => $label)
                        @if($param === 'depth' && in_array($productType, ['window', 'mesh', 'door'])) @continue @endif
                        @if(isset($parameterLimits[$param]))
                        <div class="mb-2 sm:mb-3">
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</label>
                                <div class="flex items-center gap-1.5">
                                    <div class="relative group">
                                        <input 
                                            type="number"
                                            x-data
                                            x-on:blur="
                                                if ($el.value !== '') {
                                                    let v = parseFloat($el.value);
                                                    if (!isNaN(v)) {
                                                        $el.value = v.toFixed(3);
                                                        $dispatch('input', $el.value);
                                                    }
                                                }
                                            "
                                            x-on:input="
                                                if ($el.value.includes('.')) {
                                                    let [int, dec] = $el.value.split('.');
                                                    if (dec && dec.length > 3) {
                                                        $el.value = int + '.' + dec.slice(0,3);
                                                        $dispatch('input', $el.value);
                                                    }
                                                }
                                            "
                                            wire:model.blur="parameters.{{ $param }}"
                                            min="{{ $parameterLimits[$param]['min'] }}"
                                            max="{{ $parameterLimits[$param]['max'] }}"
                                            step="0.001"
                                            pattern="^\\d+(\\.\\d{1,3})?$"
                                            class="w-16 sm:w-20 px-2 py-1.5 pr-6 border-2 border-blue-200/50 dark:border-blue-700/50 bg-gradient-to-br from-white to-blue-50 dark:from-slate-800 dark:to-slate-700 dark:text-white rounded-lg text-xs sm:text-sm text-right font-semibold focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300 shadow-sm hover:shadow-md focus:shadow-lg outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                        
                                        <!-- Flechas personalizadas -->
                                        <div class="absolute right-1 top-1/2 -translate-y-1/2 flex flex-col opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button type="button" 
                                                    class="w-4 h-3 flex items-center justify-center hover:bg-blue-500/20 rounded-t transition-colors cursor-pointer"
                                                    tabindex="-1"
                                                    wire:click="incrementParameter('{{ $param }}')">
                                                <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 12l5-5 5 5"/>
                                                </svg>
                                            </button>
                                            <button type="button" 
                                                    class="w-4 h-3 flex items-center justify-center hover:bg-blue-500/20 rounded-b transition-colors cursor-pointer"
                                                    tabindex="-1"
                                                    wire:click="decrementParameter('{{ $param }}')">
                                                <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 8l-5 5-5-5"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">m</span>
                                </div>
                            </div>
                            <div class="relative group">
                                <input type="range" wire:model.live="parameters.{{ $param }}"
                                    min="{{ $parameterLimits[$param]['min'] }}" max="{{ $parameterLimits[$param]['max'] }}" step="0.001"
                                    class="w-full h-1.5 sm:h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer">
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                    Desplazate para modificar medidas
                                </span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <!-- Aluminio -->
                <div class="bg-white/90 dark:bg-slate-900/95 backdrop-blur-sm rounded-lg md:rounded-xl shadow-md md:shadow-lg border-2 border-blue-100/50 dark:border-slate-800/50 p-2.5 sm:p-3 md:p-4 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-2 sm:mb-3">
                        <div class="p-1.5 sm:p-2 mr-2 flex items-center justify-center">
                            <!-- Ícono de perfil de aluminio con efecto brillante -->
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <defs>
                                    <filter id="glow-aluminum" x="-40%" y="-40%" width="180%" height="180%">
                                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <g filter="url(#glow-aluminum)">
                                    <!-- Perfil de aluminio: forma de "L" y detalles internos -->
                                    <rect x="5" y="5" width="14" height="14" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <polyline points="7,17 7,7 17,7" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <line x1="7" y1="12" x2="17" y2="12" stroke="currentColor" stroke-width="1"/>
                                    <line x1="12" y1="7" x2="12" y2="17" stroke="currentColor" stroke-width="1"/>
                                </g>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 dark:text-slate-100">Aluminio</h3>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-1.5 sm:gap-2 md:gap-3">
                        @foreach($availableColors as $colorName => $color)
                            @if(!str_contains(strtolower($colorName), 'glass') && !str_contains(strtolower($colorName), 'vidrio'))
                            <div class="relative group">
                                <button type="button"
                                        wire:click="updateParameter('color', '{{ $colorName }}')"
                                        class="w-12 h-12 sm:w-14 sm:h-14 md:w-11 md:h-11 lg:w-12 lg:h-12 rounded-full border-2 sm:border-3 transition-all duration-300 hover:scale-110 hover:shadow-lg {{ $parameters['color'] === $colorName ? 'border-blue-600 ring-2 sm:ring-4 ring-blue-200 shadow-xl' : 'border-slate-300 hover:border-slate-400' }} cursor-pointer"
                                        aria-label="{{ $color->color_name }}" tabindex="0">
                                    <div class="w-full h-full rounded-full"
                                        style="background-image: url('{{ asset(
                                            (str_ends_with($color->texture_path, '/aluminum/black/')
                                                ? 'textures/aluminum/black/custom-picker.jpg'
                                                : (str_ends_with($color->texture_path, '/aluminum/bronze/')
                                                    ? 'textures/aluminum/bronze/custom-picker.jpg'
                                                    : (str_ends_with($color->texture_path, '/aluminum/white/')
                                                        ? 'textures/aluminum/white/custom-picker.jpg'
                                                        : (str_ends_with($color->texture_path, '/aluminum/natural/')
                                                            ? 'textures/aluminum/natural/custom-picker.jpg'
                                                            : (str_ends_with($color->texture_path, '/aluminum/woody/')
                                                                ? 'textures/aluminum/woody/custom-picker.jpg'
                                                                : $color->texture_path)))))
                                        ) }}'); background-size: cover; background-position: center;"></div>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                    @php
                                        $colorNameDisplay = $color->color_name;
                                        $translations = [
                                            'Natural' => 'Natural',
                                            'White' => 'Blanco',
                                            'Woody' => 'Madera',
                                            'Black Anodized' => 'Negro Anodizado',
                                            ];
                                    @endphp
                                    {{ $translations[$colorNameDisplay] ?? $colorNameDisplay }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-2 sm:mt-3 text-center">
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 py-1.5 sm:py-2 px-2 sm:px-3 rounded-lg border border-blue-200 dark:border-blue-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">
                            @php
                                $colorName = $availableColors[$parameters['color']]->color_name ?? $parameters['color'];
                                $translations = [
                                    'Natural' => 'Natural',
                                    'White' => 'Blanco',
                                    'Black Anodized' => 'Negro Anodizado',
                                    'Woody' => 'Madera',
                                    'Bronze' => 'Bronze',
                                    'Silver' => 'Plateado',
                                    'Gold' => 'Dorado',
                                    'Transparent Glass' => 'Vidrio Transparente',
                                    'Tinted Glass' => 'Vidrio Tintado',
                                    'Frosted Glass' => 'Vidrio Esmerilado',
                                ];
                                echo $translations[$colorName] ?? $colorName;
                            @endphp
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Estructura (Vidrio) -->
                @if($productType !== 'mesh' && count(array_filter(array_keys($availableColors->toArray()), fn($k) => str_contains(strtolower($k), 'glass') || str_contains(strtolower($k), 'vidrio'))) > 0)
                <div class="bg-white/90 dark:bg-slate-900/95 backdrop-blur-sm rounded-lg md:rounded-xl shadow-md md:shadow-lg border-2 border-cyan-100/50 dark:border-slate-800/50 p-2.5 sm:p-3 md:p-4 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center mb-2 sm:mb-3">
                        <div class="p-1.5 sm:p-2 mr-2 flex items-center justify-center">
                            <!-- Ícono de vidrio roto con efecto brillante -->
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <defs>
                                    <filter id="glow" x="-40%" y="-40%" width="180%" height="180%">
                                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <g filter="url(#glow)">
                                    <rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <!-- Fracturas -->
                                    <polyline points="7,7 12,10 17,7" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <polyline points="7,17 12,14 17,17" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    <polyline points="7,7 8,12 12,10 16,12 17,7" stroke="currentColor" stroke-width="1" fill="none"/>
                                    <line x1="12" y1="10" x2="12" y2="14" stroke="currentColor" stroke-width="1"/>
                                </g>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 dark:text-slate-100">Vidrio</h3>
                    </div>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-1.5 sm:gap-2 md:gap-3">
                        @foreach($availableColors as $colorName => $color)
                            @if(str_contains(strtolower($colorName), 'glass') || str_contains(strtolower($colorName), 'vidrio'))
                            <div class="relative group">
                                <button type="button"
                                        wire:click="updateParameter('glassColor', '{{ $colorName }}')"
                                        class="w-12 h-12 sm:w-14 sm:h-14 md:w-11 md:h-11 lg:w-12 lg:h-12 border-2 sm:border-3 transition-all duration-300 hover:scale-110 hover:shadow-lg rounded-lg {{ $parameters['glassColor'] === $colorName ? 'border-cyan-600 ring-2 sm:ring-4 ring-cyan-200 shadow-xl' : 'border-slate-300 hover:border-slate-400' }} cursor-pointer"
                                        aria-label="{{ $color->color_name }}" tabindex="0">
                                    <div class="w-full h-full"
                                        style="background-image: url('{{ asset(
                                            (str_ends_with($color->texture_path, '/glass/transparent/')
                                                ? 'textures/glass/transparent/custom-picker.jpg'
                                                : (str_ends_with($color->texture_path, '/glass/reflective_blue/')
                                                    ? 'textures/glass/reflective_blue/custom-picker.jpg'
                                                    : (str_ends_with($color->texture_path, '/glass/reflective_gray_dark/')
                                                        ? 'textures/glass/reflective_gray_dark/custom-picker.jpg'
                                                        : $color->texture_path)))
                                        ) }}'); background-size: cover; background-position: center;"></div>
                                </button>
                                @php
                                    $glassTranslations = [
                                        'Transparent Glass' => 'Transparente',
                                        'Reflective Blue Sky Glass' => 'Azul Cielo Reflectivo',
                                        'Reflective Gray Dark Glass' => 'Gris Oscuro Reflectivo',
                                    ];
                                    $glassColorName = $color->color_name;
                                @endphp
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                    {{ $glassTranslations[$glassColorName] ?? $glassColorName }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-2 sm:mt-3 text-center">
                        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/30 dark:to-blue-900/30 py-1.5 sm:py-2 px-2 sm:px-3 rounded-lg border border-cyan-200 dark:border-cyan-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">
                            @php
                                $glassColorName = $availableColors[$parameters['glassColor']]->color_name ?? $parameters['glassColor'];
                                $glassTranslations = [
                                    'Transparent Glass' => 'Transparente',
                                    'Reflective Blue Sky Glass' => 'Azul Cielo Reflectivo',
                                    'Reflective Gray Dark Glass' => 'Gris Oscuro Reflectivo',
                                ];
                                echo $glassTranslations[$glassColorName] ?? $glassColorName;
                            @endphp
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Panel Central: Visor 3D -->
            <div class="lg:col-span-6 flex flex-col bg-white/90 dark:bg-slate-900/95 backdrop-blur-sm rounded-lg md:rounded-xl shadow-md md:shadow-lg border-2 border-slate-200/50 dark:border-slate-800/50 p-2 sm:p-3 md:p-4 h-full overflow-hidden">
                <div class="flex flex-col items-center w-full h-full">
                    <div wire:ignore id="parametric-3d-viewer" class="w-full flex-1 min-h-0 bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 dark:from-gray-950 dark:via-slate-950 dark:to-slate-900 rounded-lg relative overflow-hidden">
                    </div>
                    
                    <div class="mt-2 sm:mt-3 flex flex-wrap justify-center gap-2 sm:gap-3">
                        <div class="relative group">
                        <button type="button" 
                                onclick="resetParametricView()"
                                class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm bg-gradient-to-r from-slate-100 to-slate-200 hover:from-slate-200 hover:to-slate-300 text-slate-700 font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg border border-slate-300 cursor-pointer"
                                >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden xs:inline sm:inline">Resetear</span>
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Restaurar vista 3D
                        </span>
                        </div>
                        <div class= "relative group">
                        <button type="button" 
                                onclick="takeParametricScreenshot()"
                                class="px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl cursor-pointer"
                                >
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="hidden xs:inline sm:inline">Capturar</span>
                        </button>
                        <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                            Descargar imagen PNG
                        </span>
                        </div>
                    </div>

                    <div class="mt-2 sm:mt-3 text-center">
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg border border-blue-200 dark:border-blue-800 inline-block">
                            <p class="text-xs sm:text-sm md:text-base font-bold text-slate-800 dark:text-slate-100">{{ $product->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Resumen -->
            <div class="lg:col-span-3 h-full overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: rgba(59,130,246,0.3) transparent;">

                <!-- Resumen de Proforma -->
                <div class="bg-white/90 dark:bg-slate-900/95 backdrop-blur-sm rounded-lg md:rounded-xl shadow-md md:shadow-lg border-2 border-blue-100/50 dark:border-slate-800/50 p-2.5 sm:p-3 md:p-4">
                    <div class="flex items-center mb-2 sm:mb-3">
                        <div class="p-1.5 sm:p-2 mr-2 flex items-center justify-center">
                            <!-- Ícono de documento/lista con efecto brillante -->
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <defs>
                                    <filter id="glow-summary" x="-40%" y="-40%" width="180%" height="180%">
                                        <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <g filter="url(#glow-summary)">
                                    <!-- Documento -->
                                    <rect x="6" y="5" width="12" height="14" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <!-- Líneas de resumen -->
                                    <line x1="8" y1="9" x2="16" y2="9" stroke="currentColor" stroke-width="1.5"/>
                                    <line x1="8" y1="12" x2="16" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                    <line x1="8" y1="15" x2="13" y2="15" stroke="currentColor" stroke-width="1.5"/>
                                </g>
                            </svg>
                        </div>
                        <h3 class="text-sm sm:text-base md:text-lg font-bold text-slate-800 dark:text-slate-100">Resumen</h3>
                    </div>
                    
                    <div class="mb-2 sm:mb-3 pb-2 sm:pb-3 border-b-2 border-slate-200 dark:border-slate-700">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg md:rounded-xl p-3 sm:p-4 text-center shadow-lg">
                            <p class="text-xs text-blue-100 mb-1 font-medium">Precio Total</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-black text-white">${{ number_format($calculatedPrice, 2) }}</p>
                        </div>
                    </div>

                    <div class="space-y-2 sm:space-y-2.5 mb-2 sm:mb-3">
                        <!-- Dimensiones -->
                        <div class="flex justify-between items-center p-2 sm:p-2.5 bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-700/50 dark:to-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Dimensiones:</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100">{{ number_format($parameters['height'], 3) }}m × {{ number_format($parameters['width'], 3) }}m</span>
                        </div>

                        <!-- Área -->
                        <div class="flex justify-between items-center p-2 sm:p-2.5 bg-gradient-to-r from-slate-50 to-cyan-50 dark:from-slate-700/50 dark:to-cyan-900/30 rounded-lg border border-cyan-100 dark:border-cyan-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Área total:</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100">{{ number_format($parameters['width'] * $parameters['height'], 3) }}m²</span>
                        </div>

                        <!-- Color -->
                        <div class="flex justify-between items-center p-2 sm:p-2.5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Color Aluminio:</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100">
                                @php
                                    $colorName = $availableColors[$parameters['color']]->color_name ?? $parameters['color'];
                                    $translations = [
                                        'Natural' => 'Natural',
                                        'White' => 'Blanco',
                                        'Black Anodized' => 'Negro Anodizado',
                                        'Woody' => 'Madera',
                                        'Bronze' => 'Bronze',
                                        'Silver' => 'Plateado',
                                        'Gold' => 'Dorado',
                                    ];
                                    echo $translations[$colorName] ?? $colorName;
                                @endphp
                            </span>
                        </div>

                        <!-- Color Vidrio -->
                        @if($productType !== 'mesh' && isset($parameters['glassColor']) && isset($availableColors[$parameters['glassColor']]))
                        <div class="flex justify-between items-center p-2 sm:p-2.5 bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/30 dark:to-blue-900/30 rounded-lg border border-cyan-200 dark:border-cyan-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Color Vidrio:</span>
                            <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100">
                                @php
                                    $glassColorName = $availableColors[$parameters['glassColor']]->color_name;
                                    $glassTranslations = [
                                        'Transparent Glass' => 'Transparente',
                                        'Reflective Blue Sky Glass' => 'Azul Cielo Reflectivo',
                                        'Reflective Gray Dark Glass' => 'Gris Oscuro Reflectivo',
                                    ];
                                    echo $glassTranslations[$glassColorName] ?? $glassColorName;
                                @endphp
                            </span>
                        </div>
                        @endif

                        <!-- Precio Unitario -->
                        <div class="flex justify-between items-center p-2 sm:p-2.5 bg-gradient-to-r from-emerald-50 to-green-50 dark:from-emerald-900/30 dark:to-green-900/30 rounded-lg border-2 border-emerald-200 dark:border-emerald-800">
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300">Precio unitario:</span>
                            <span class="text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-400">${{ number_format($calculatedPrice / max(1, $quantity), 2) }}</span>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="mb-2 sm:mb-3 pb-2 sm:pb-3 border-b-2 border-slate-200 dark:border-slate-700">
                        <label class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100 mb-2 block text-center">Cantidad</label>
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 dark:from-slate-700/50 dark:to-blue-900/30 p-2 sm:p-3 rounded-lg border-2 border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-center gap-2 sm:gap-3">
                                <div class="relative group">
                                <button type="button"
                                        wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                                        class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-slate-400 to-slate-600 hover:from-slate-500 hover:to-slate-700 text-white rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center hover:scale-110 active:scale-95 border-2 border-slate-300/50 dark:border-slate-600/50 cursor-pointer">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">-1</span>
                                </div>
                                <div class="px-4 py-1.5 bg-white dark:bg-slate-800 rounded-lg border-2 border-blue-300 dark:border-blue-700 shadow-inner">
                                    <span class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $quantity }}</span>
                                </div>      
                                <div class= "relative group">
                                <button type="button"
                                        wire:click="$set('quantity', {{ $quantity + 1 }})"
                                        class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center hover:scale-110 active:scale-95 border-2 border-blue-300/50 dark:border-cyan-500/50 cursor-pointer">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">+1</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 sm:space-y-2.5">
                        @if(Auth::check())
                            @if(isset($userProfileComplete) && !$userProfileComplete)
                                <div class="w-full bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 text-amber-800 font-semibold py-2 sm:py-2.5 px-2.5 sm:px-3 rounded-lg text-center shadow-lg">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <button type="button"
                                            onclick="Livewire.dispatch('openProfileModal')"
                                            class="text-xs sm:text-sm font-bold text-blue-700 underline underline-offset-2 hover:text-blue-900 transition-colors cursor-pointer ml-0.5">
                                        Completa tus datos
                                    </button>
                                </div>
                            @endif
                            <div class="relative group">
                                <button type="button"
                                        @click="savedConfigs.length > 0 && $wire.set('showProformaModal', true)"
                                        :disabled="savedConfigs.length === 0"
                                        class="relative w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl text-xs sm:text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed border border-blue-500/20 cursor-pointer">
                                    <!-- Ícono de documento/lista con efecto brillante -->
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 inline-block mr-1.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <defs>
                                            <filter id="glow-summary-btn" x="-40%" y="-40%" width="180%" height="180%">
                                                <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                                                <feMerge>
                                                    <feMergeNode in="coloredBlur"/>
                                                    <feMergeNode in="SourceGraphic"/>
                                                </feMerge>
                                            </filter>
                                        </defs>
                                        <g filter="url(#glow-summary-btn)">
                                            <rect x="6" y="5" width="12" height="14" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                            <line x1="8" y1="9" x2="16" y2="9" stroke="currentColor" stroke-width="1.5"/>
                                            <line x1="8" y1="12" x2="16" y2="12" stroke="currentColor" stroke-width="1.5"/>
                                            <line x1="8" y1="15" x2="13" y2="15" stroke="currentColor" stroke-width="1.5"/>
                                        </g>
                                    </svg>
                                    <span>VISUALIZAR PROFORMA</span>
                                    <!-- Badge con count -->
                                    <span x-show="savedConfigs.length > 0" x-cloak
                                          x-text="savedConfigs.length"
                                          class="absolute -top-2.5 -right-2.5 min-w-[1.5rem] h-6 rounded-full bg-red-400 text-slate-900 text-xs font-black flex items-center justify-center px-1.5 shadow-xl border-2 border-white ring-2 ring-red-300/60">
                                    </span>
                                </button>
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-50">
                                    Ver detalles de la proforma
                                </span>
                            </div>
                            @if(!(isset($userProfileComplete) && !$userProfileComplete))
                            <button type="button"
                                    @click="saveConfig({
                                        product_id: {{ $product->id }},
                                        product_name: '{{ addslashes($product->name) }}',
                                        parameters: $wire.parameters,
                                        quantity: $wire.quantity,
                                        calculated_price: $wire.calculatedPrice,
                                        material_costs: $wire.materialCosts,
                                        savedAt: new Date().toISOString()
                                    })"
                                    :disabled="$wire.calculatedPrice == 0 || $wire.calculatedPrice == null"
                                    class="w-full bg-transparent hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium py-1.5 sm:py-2 px-3 rounded-lg transition-all duration-200 text-xs sm:text-sm disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer border border-slate-300/60 dark:border-slate-700/60 hover:border-slate-400 dark:hover:border-slate-500">
                                Guardar configuración
                            </button>
                            @endif
                        @else
                            <div class="w-full bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-300 text-amber-800 font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg text-center shadow-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs sm:text-sm">Inicia sesión para continuar</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modales -->
        <div x-data="{ show: @entangle('showProformaModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/30 backdrop-blur-[1px] transition-opacity"></div>
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="w-full max-w-2xl relative overflow-visible">
                    <button @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10 cursor-pointer">&times;</button>
                    @if(Auth::check())
                        @if($savedState)
                        <!-- Panel de éxito post-guardado -->
                        <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full p-8 relative text-white flex flex-col items-center gap-5 text-center">
                            <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-500 flex items-center justify-center mx-auto mt-2">
                                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-emerald-400 text-xs font-bold uppercase tracking-widest mb-1">&iexcl;Guardado exitosamente!</p>
                                <h3 class="text-3xl font-black text-white">{{ $savedProformaNumber }}</h3>
                                <p class="text-white/60 text-sm mt-2">Tu configuraci&oacute;n fue guardada en esta proforma</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 w-full pt-2">
                                <button type="button"
                                        @click="show = false; $wire.openUserProformasModal()"
                                        class="flex-1 px-5 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-semibold rounded-xl transition-all hover:scale-[1.02] cursor-pointer">
                                     Ver mis proformas
                                </button>
                                <button type="button"
                                        @click="show = false"
                                        class="flex-1 px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition-all hover:scale-[1.02] cursor-pointer">
                                    Configurar otro producto
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-2xl w-full p-6 md:p-8 relative text-white overflow-hidden">
                            @if (session()->has('message'))
                                <div x-data="{ show: true }" 
                                     x-init="setTimeout(() => show = false, 2000)" 
                                     x-show="show" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 scale-90" 
                                     x-transition:enter-end="opacity-100 scale-100" 
                                     x-transition:leave="transition ease-in duration-300" 
                                     x-transition:leave-start="opacity-100 scale-100" 
                                     x-transition:leave-end="opacity-0 scale-90" 
                                     class="fixed inset-0 z-[100] flex items-center justify-center">
                                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
                                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-8 relative mx-4 border-2 border-green-500">
                                        <button type="button" @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-green-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                                <path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">
                                                @if(str_contains(session('message'), 'Orden creada'))
                                                    ¡Orden creada exitosamente!
                                                @elseif(str_contains(session('message'), 'agregada'))
                                                    ¡Configuración agregada!
                                                @else
                                                    ¡Operación exitosa!
                                                @endif
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-300 text-center">{{ session('message') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="overflow-y-auto max-h-[75vh] pr-2" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);">
                                <div class="mb-4 p-4 bg-blue-500/20 border border-blue-400/50 rounded-lg">
                                    <p class="text-blue-200 text-sm font-medium mb-1">Configuraciones guardadas</p>
                                    <p class="text-blue-100/70 text-xs">Elige dónde guardar tus configuraciones: crea una nueva proforma o agrégalas a una existente.</p>
                                </div>

                                @include('livewire.proformas.proforma', [
                                    'product' => $product,
                                    'parameters' => $parameters,
                                    'quantity' => $quantity,
                                    'materialCosts' => $materialCosts,
                                    'calculatedPrice' => $calculatedPrice,
                                    'notes' => $parameters['notes'] ?? null,
                                    'directCost' => $directCost ?? null,
                                    'indirectCost' => $indirectCost ?? null,
                                    'wastePercentage' => $wastePercentage ?? null
                                ])

                                <!-- Configuraciones guardadas en localStorage -->
                                <div x-show="savedConfigs.length > 0" x-cloak class="mt-6 pt-6 border-t border-white/20">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-lg font-bold text-white">Mis configuraciones (<span x-text="savedConfigs.length"></span>)</h4>
                                        <button type="button" @click="clearSaved()"
                                                class="text-xs text-white/50 hover:text-red-400 transition-colors cursor-pointer px-2 py-1 rounded">
                                            Limpiar todo
                                        </button>
                                    </div>
                                    <div class="space-y-3 max-h-64 overflow-y-auto">
                                        <template x-for="(cfg, i) in savedConfigs" :key="i">
                                            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 hover:bg-white/15 transition-all flex items-start gap-3">
                                                <div class="flex-1">
                                                    <p class="font-semibold text-white text-sm mb-1" x-text="cfg.product_name ?? '{{ $product->name }}'"></p>
                                                    <div class="text-xs text-white/70 space-y-1">
                                                        <div class="flex gap-3 flex-wrap">
                                                            <span x-text="(cfg.parameters?.width ?? 0).toFixed(2) + 'm × ' + (cfg.parameters?.height ?? 0).toFixed(2) + 'm'"></span>
                                                            <span x-text="cfg.parameters?.color ?? ''"></span>
                                                        </div>
                                                        <div class="flex items-center gap-2 mt-2">
                                                            <button type="button" @click="updateConfigQty(i, -1)"
                                                                    class="w-7 h-7 flex items-center justify-center bg-white/10 hover:bg-red-500/70 text-white rounded-md font-bold transition-all duration-150 hover:scale-110 cursor-pointer text-base leading-none">
                                                                &minus;
                                                            </button>
                                                            <span class="min-w-[2rem] text-center font-bold text-white text-sm" x-text="cfg.quantity ?? 1"></span>
                                                            <button type="button" @click="updateConfigQty(i, 1)"
                                                                    class="w-7 h-7 flex items-center justify-center bg-white/10 hover:bg-green-500/70 text-white rounded-md font-bold transition-all duration-150 hover:scale-110 cursor-pointer text-base leading-none">
                                                                &plus;
                                                            </button>
                                                            <span class="text-cyan-300 font-bold text-sm ml-1" x-text="'$' + parseFloat(cfg.calculated_price || 0).toFixed(2)"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeConfig(i)"
                                                        class="text-white/40 hover:text-red-400 text-xl font-bold leading-none transition-colors cursor-pointer flex-shrink-0">
                                                    &times;
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-4 p-4 bg-gradient-to-r from-violet-600/30 to-purple-600/30 rounded-lg border border-violet-400/30">
                                        <div class="flex justify-between items-center">
                                            <span class="text-white font-semibold">Total:</span>
                                            <span class="text-2xl font-bold text-white" x-text="'$' + configsTotal.toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 pt-6 border-t border-white/20">
                                <!-- Notas de la proforma -->
                                <div class="mb-4" x-data="{ showNotes: false }">
                                    <button type="button"
                                            @click="showNotes = !showNotes"
                                            class="text-xs text-white/50 hover:text-cyan-300 transition-colors cursor-pointer focus:outline-none">
                                        <span x-text="showNotes ? '▲ Ocultar notas' : '▼ Añadir notas opcionales'"></span>
                                    </button>
                                    <div x-show="showNotes" x-transition class="mt-2">
                                        <textarea wire:model.defer="parameters.notes"
                                                  rows="2" maxlength="250"
                                                  placeholder="Notas para esta proforma (opcional)"
                                                  class="w-full px-3 py-2 rounded-lg border border-white/20 bg-black/30 text-white text-xs placeholder-white/40 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-200/30 resize-none outline-none"></textarea>
                                    </div>
                                </div>
                                <!-- Botones principales -->
                                <div class="flex flex-col sm:flex-row justify-end gap-3">
                                    @if(isset($userProfileComplete) && !$userProfileComplete)
                                    <div class="w-full bg-gradient-to-r from-yellow-100 to-amber-100 border border-yellow-300/50 text-yellow-800 font-semibold py-2 px-4 rounded-xl text-center shadow-md mb-2">
                                        Debes completar tus datos personales para guardar una proforma.
                                    </div>
                                    @endif
                                    <button type="button"
                                            @click="$wire.guardarConfiguracionesGuardadas(savedConfigs, null)"
                                            :disabled="savedConfigs.length === 0 || !@json($userProfileComplete ?? true)"
                                            class="px-4 py-2 text-sm font-medium border border-cyan-600/40 rounded-lg text-white bg-gradient-to-r from-cyan-700 to-slate-700 hover:from-cyan-800 hover:to-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transform transition-all duration-200 hover:scale-[1.02] hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                        <span x-show="savedConfigs.length > 1" x-cloak x-text="`Crear Proforma (${savedConfigs.length} configs)`"></span>
                                        <span x-show="savedConfigs.length <= 1">Crear Nueva Proforma</span>
                                    </button>
                                    @if(count($availableProformas) > 0)
                                    <button type="button"
                                            wire:click="openProformaSelectorModal"
                                            :disabled="savedConfigs.length === 0 || !@json($userProfileComplete ?? true)"
                                            class="px-4 py-2 text-sm font-medium bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-lg transition-all duration-200 hover:scale-[1.02] hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">
                                        Agregar a Proforma Existente
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="bg-black/70 backdrop-blur-md rounded-2xl shadow-xl w-full p-8 relative flex flex-col items-center">
                            <svg class="w-16 h-16 text-yellow-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <h3 class="text-xl font-bold text-white mb-2">Acceso restringido</h3>
                            <p class="text-white/70 text-center">Debes iniciar sesión para visualizar, guardar o descargar la proforma.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Modal Selector de Proformas (Separado) -->
    <div x-data="{ show: @entangle('showProformaSelectorModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-[60]">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="w-full max-w-lg relative">
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-2xl w-full p-6 relative text-white border border-white/10">
                    <button @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-white text-3xl font-bold z-10">&times;</button>
                    
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-white mb-2">Selecciona una Proforma</h3>
                        <p class="text-white/70 text-sm">Elige a qué proforma deseas agregar esta configuración</p>
                    </div>

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2 mb-6" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);">
                        @forelse($availableProformas as $proforma)
                        <label class="flex items-start p-4 bg-white/5 rounded-xl cursor-pointer hover:bg-white/10 transition-all border-2 @if($selectedProformaToAdd == $proforma['id']) border-cyan-500 bg-cyan-500/10 shadow-lg shadow-cyan-500/20 @else border-white/10 @endif group">
                            <input type="radio" 
                                   wire:model.live="selectedProformaToAdd" 
                                   value="{{ $proforma['id'] }}"
                                   class="mt-1 mr-4 w-5 h-5 flex-shrink-0 text-cyan-500">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-bold text-lg text-white">{{ $proforma['number'] }}</div>
                                    <span class="px-2 py-0.5 text-xs font-medium bg-cyan-500/20 text-cyan-300 rounded-full">
                                        {{ $proforma['items_count'] }} {{ $proforma['items_count'] == 1 ? 'ítem' : 'ítems' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white/60">Total actual:</span>
                                    <span class="font-bold text-green-400">${{ number_format($proforma['total_price'], 2) }}</span>
                                </div>
                                <div class="text-xs text-white/50 mt-1">
                                    Creada: {{ \Carbon\Carbon::parse($proforma['created_at'])->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="text-center text-white/70 py-8">
                            <svg class="w-16 h-16 mx-auto mb-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-medium">No tienes proformas disponibles</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="flex gap-3">
                        <button type="button" 
                                wire:click="closeProformaSelectorModal"
                                class="flex-1 px-5 py-3 text-sm font-medium bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-all hover:scale-[1.02] cursor-pointer">
                            Cancelar
                        </button>
                        <button type="button" 
                                @click="$wire.guardarConfiguracionesGuardadas(savedConfigs, $wire.selectedProformaToAdd)"
                                :disabled="!$wire.selectedProformaToAdd || savedConfigs.length === 0"
                                class="flex-1 px-5 py-3 text-sm font-medium bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 cursor-pointer">
                            <span x-show="savedConfigs.length > 1" x-cloak x-text="`Agregar ${savedConfigs.length} configs`"></span>
                            <span x-show="savedConfigs.length <= 1">Agregar a Proforma</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Advertencia de Costos -->
    @if($showCostUpdateWarning)
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 border-2 border-amber-400 relative">
            <button type="button" wire:click="cancelarActualizarCostosYAgregar" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
            <div class="flex flex-col items-center">
                <svg class="w-16 h-16 text-amber-400 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="text-xl font-bold text-amber-700 mb-2 text-center">¡Atención! Costos desactualizados</h3>
                @if($pendingItemId)
                <p class="text-gray-700 text-center mb-4">Los costos de producción han cambiado. Para actualizar esta configuración, <span class="font-semibold text-amber-700">se creará una nueva proforma con los precios actualizados</span> de todos los productos.<br>¿Deseas continuar?</p>
                @else
                <p class="text-gray-700 text-center mb-4">Si agregas este producto a la proforma seleccionada, <span class="font-semibold text-amber-700">se creará una nueva proforma con los precios actualizados</span> de todos los productos.<br>¿Deseas continuar?</p>
                @endif
                <div class="flex gap-4 mt-4">
                    <button type="button" wire:click="cancelarActualizarCostosYAgregar" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold">Cancelar</button>
                    @if($pendingItemId)
                    <button type="button" wire:click="confirmarActualizarItemConNuevosCostos" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg font-semibold shadow-lg">Crear Nueva Proforma</button>
                    @else
                    <button type="button" wire:click="confirmarActualizarCostosYAgregar" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg font-semibold shadow-lg">Crear Nueva Proforma</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal de Confirmación para Crear Nueva Proforma -->
    <div x-data="{ show: @entangle('showCreateConfirmModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-[100]">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
                <button type="button" @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl font-bold cursor-pointer">&times;</button>
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-cyan-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">¿Crear nueva proforma?</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-center mb-6">Se creará una nueva proforma con la configuración actual. ¿Deseas continuar?</p>
                    <div class="flex gap-4 w-full">
                        <button type="button" @click="show = false" class="flex-1 px-5 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-800 dark:text-white rounded-lg font-semibold transition-all cursor-pointer">Cancelar</button>
                        <button type="button" wire:click="crearNuevaProforma" @click="show = false" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-lg font-semibold shadow-lg transition-all cursor-pointer">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Ordenar Proforma -->
    <div x-data="{ show: @entangle('showOrderConfirmModal') }" x-show="show" x-transition x-cloak class="fixed inset-0 z-[100]">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-8 relative">
                <button type="button" @click="show = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 dark:hover:text-white text-2xl font-bold">&times;</button>
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-amber-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">¿Ordenar proforma?</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-center mb-6">Se creará una orden con esta proforma. Esta acción no se puede deshacer. ¿Deseas continuar?</p>
                    <div class="flex gap-4 w-full">
                        <button type="button" @click="show = false" class="flex-1 px-5 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 text-gray-800 dark:text-white rounded-lg font-semibold transition-all">Cancelar</button>
                        <button type="button" wire:click="orderProforma" @click="show = false" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-amber-600 to-orange-700 hover:from-amber-700 hover:to-orange-800 text-white rounded-lg font-semibold shadow-lg transition-all">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let parametricViewer = null;
    let initAttempts = 0;
    const maxAttempts = 10;
    let initialProductParams = null; // Guardar parámetros iniciales

    function startViewer() {
        initParametricViewer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startViewer);
    } else {
        startViewer();
    }

    function initParametricViewer() {
        initAttempts++;

        if (typeof THREE === 'undefined') {
            updateViewerStatus('Three.js no está disponible');
            return;
        }

        if (typeof createParametricProduct3D !== 'function') {
            if (initAttempts < maxAttempts) {
                setTimeout(initParametricViewer, 1000);
                return;
            }
            updateViewerStatus('Error: Función de configurador no disponible');
            return;
        }

        const productType = '{{ $productType }}';
        const initialParams = @json($parameters);
        // Lógica explícita para el path de textura según tipo de producto
        let colorTexturePath = '{{ $colorTexturePath }}';
        if (productType === 'closet' && (!colorTexturePath || colorTexturePath.includes('aluminum'))) {
            colorTexturePath = '/textures/melamina/natural/';
        }
        initialParams.texturePath = colorTexturePath;
        initialParams.frameColor = initialParams.frameColor || 0xC0C0C0;
        initialParams.glassColor = initialParams.glassColor || '#E0F6FF';

        // Guardar una copia de los parámetros iniciales
        initialProductParams = JSON.parse(JSON.stringify(initialParams));

        try {
            updateViewerStatus('Generando modelo 3D...');
            parametricViewer = createParametricProduct3D(
                'parametric-3d-viewer', 
                productType, 
                initialParams
            );
        } catch (error) {
            updateViewerStatus('Error generando modelo 3D: ' + error.message);
        }
    }

    function updateViewerStatus(message) {
        const statusElement = document.querySelector('#parametric-3d-viewer .text-center p');
        if (statusElement) {
            statusElement.textContent = message;
        }
    }

    function hideLoader() {
        const loader = document.querySelector('#parametric-3d-viewer .absolute');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    Livewire.on('configuraciones-guardadas', () => {
        window.dispatchEvent(new CustomEvent('configuraciones-guardadas'));
    });

    Livewire.on('updateModel3D', (data) => {
        
        let parameters = null;
        if (Array.isArray(data) && data.length > 0) {
            parameters = data[0].parameters || data[0];
        } else if (data && typeof data === 'object') {
            parameters = data.parameters || data;
        }
        
        if (parametricViewer && parameters) {
            parametricViewer.updateParameters(parameters);
        }
    });

    setTimeout(() => {
        if (!parametricViewer) {
            startViewer();
        }
    }, 1000);

    // Función para actualizar el fondo del visor 3D según el modo oscuro
    function updateViewer3DBackground(isDark) {
        if (parametricViewer && parametricViewer.scene) {
            // Usar el mismo color que dark:via-slate-950 (#0f172a) y light:from-slate-50 (#f8fafc)
            const bgColor = isDark ? 0x0f172a : 0xf8fafc;
            parametricViewer.scene.background = new THREE.Color(bgColor);
            if (parametricViewer.renderer) {
                parametricViewer.renderer.setClearColor(bgColor);
            }
        }
    }

    // Observar cambios en el modo oscuro
    const darkModeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const isDark = document.documentElement.classList.contains('dark');
                updateViewer3DBackground(isDark);
            }
        });
    });

    // Iniciar observador cuando el visor esté listo
    setTimeout(() => {
        if (parametricViewer) {
            darkModeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
            // Establecer el fondo inicial
            const isDark = document.documentElement.classList.contains('dark');
            updateViewer3DBackground(isDark);
        }
    }, 2000);

    window.resetParametricView = function() {
        if (parametricViewer && initialProductParams) {
            // Resetear la cámara
            parametricViewer.resetZoom();
            
            // Resetear los parámetros del producto en el visor 3D
            parametricViewer.updateParameters(initialProductParams);
            
            // Resetear los parámetros en Livewire
            @this.set('parameters', initialProductParams);
        }
    };

    window.takeParametricScreenshot = function() {
        if (parametricViewer) {
            const dataURL = parametricViewer.screenshot(1200, 800);
            const link = document.createElement('a');
            link.download = '{{ Str::slug($product->name) }}_configurador_3d.png';
            link.href = dataURL;
            link.click();
        } else {
            alert('El visor 3D no está disponible');
        }
    };

</script>
@endscript
