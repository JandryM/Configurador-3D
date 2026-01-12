<?php

namespace App\Livewire\Dashboard;
use Livewire\Component;
use App\Models\Proforma;
use App\Models\Order;;
use App\Models\GlobalCostSetting;
use App\Models\ProformaItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    public $userRole;
    public $userName;
    public $proformasActivas;
    public $proformasPorExpirar;
    public $lastCostUpdate;
    public $proformasConCostosDesactualizados;
    public $proformasActivasAyer;
    public $cambioProformas;
    public $ordenesPendientes;
    public $ordenesAprobadas;
    public $ordenesEnProduccion;
    public $ordenesCompletadas;
    public $ordenesCanceladas;
    public $ordenesEnProduccionAyer;
    public $cambioOrdenes;
    public $rentabilidadMes;
    public $rentabilidadMesPasado;
    public $cambioRentabilidad;
    public $costosIndirectos;
    public $diasRestantesCostos;
    public $datosGrafico;
    public $totalOrdenes;
    public $porcentajeOrdenes;
    public $alertas;
    public $alertasVistas = [];
    public $actividadReciente;
    public $paginaActividad = 1;
    public $actividadPorPagina = 5;
    public $totalActividadReciente = 0;
    public $proformasCreadasMes;
    public $proformasExpiradasMes;
    public $proformasAprobadasMes;
    public $datosGraficoProformas;
    
    // Métricas de Análisis de Rentabilidad
    public $totalOrdenesAnalizadas;
    public $margenPromedioMes;
    public $margenMesPasado;
    public $tendenciaMargen;
    public $productosMasVendidos;
    public $ordenMayorMargen;
    public $ordenMenorMargen;
    public $valorTotalOrdenes;
    public $gananciaTotal;

    #[On('costos-actualizados')]
    #[On('costos-productos-actualizados')]
    #[On('cuenta-bancaria-actualizada')]
    public function refreshDashboard()
    {
        $this->mount();
    }

    public function mount()
    {
        \Carbon\Carbon::setLocale('es');
        $this->userRole = auth()->user()->role;
        $this->userName = auth()->user()->name;
        $this->alertasVistas = session()->get('alertas_vistas', []);

        // KPIs de Proformas
        $this->proformasActivas = \DB::table('proformas')
            ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereNull('orders.id')
            ->where('proformas.is_expired', false)
            ->where('proformas.expiration_date', '>', now())
            ->count();

        $this->proformasPorExpirar = \DB::table('proformas')
            ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereNull('orders.id')
            ->where('proformas.is_expired', false)
            ->whereBetween('proformas.expiration_date', [now(), now()->addDays(3)])
            ->count();

        $this->lastCostUpdate = \DB::table('global_cost_settings')->max('created_at');
        $this->proformasConCostosDesactualizados = 0;
        if ($this->lastCostUpdate) {
            $this->proformasConCostosDesactualizados = \DB::table('proformas')
                ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
                ->whereNull('orders.id')
                ->where('proformas.is_expired', false)
                ->where('proformas.created_at', '<', $this->lastCostUpdate)
                ->count();
        }

        $this->proformasActivasAyer = \DB::table('proformas')
            ->leftJoin('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereNull('orders.id')
            ->where('proformas.is_expired', false)
            ->where('proformas.expiration_date', '>', now())
            ->where('proformas.created_at', '<', now()->subDay())
            ->count();
        $this->cambioProformas = $this->proformasActivas - $this->proformasActivasAyer;

        // KPIs de Órdenes
        $this->ordenesPendientes = \DB::table('orders')->where('status', 'pending')->count();
        $this->ordenesAprobadas = \DB::table('orders')->where('status', 'approved')->count();
        $this->ordenesEnProduccion = \DB::table('orders')->where('status', 'in_production')->count();
        $this->ordenesCompletadas = \DB::table('orders')->where('status', 'completed')->count();
        $this->ordenesCanceladas = \DB::table('orders')->where('status', 'cancelled')->count();

        $this->ordenesEnProduccionAyer = \DB::table('orders')
            ->where('status', 'in_production')
            ->where('updated_at', '<', now()->subDay())
            ->count();
        $this->cambioOrdenes = $this->ordenesEnProduccion - $this->ordenesEnProduccionAyer;

        // Rentabilidad estimada (MTD - Month to Date)
        $this->rentabilidadMes = \DB::table('proforma_items')
            ->join('proformas', 'proforma_items.proforma_id', '=', 'proformas.id')
            ->whereNotNull('proforma_items.configuration')
            ->whereMonth('proformas.created_at', now()->month)
            ->get()
            ->sum(function($item) {
                $config = json_decode($item->configuration, true);
                $price = $item->price;
                $materialCostsArray = $config['material_costs'] ?? [];
                $totalMaterialCost = is_array($materialCostsArray)
                    ? collect($materialCostsArray)->sum('total_cost')
                    : 0;
                $directCostAmount = $totalMaterialCost * (($config['directCost'] ?? 0) / 100);
                $indirectCostAmount = $totalMaterialCost * (($config['indirectCost'] ?? 0) / 100);
                $wasteAmount = $totalMaterialCost * (($config['wastePercentage'] ?? 0) / 100);
                $totalCost = $totalMaterialCost + $directCostAmount + $indirectCostAmount + $wasteAmount;
                return $price - $totalCost;
            });

        $this->rentabilidadMesPasado = \DB::table('proforma_items')
            ->join('proformas', 'proforma_items.proforma_id', '=', 'proformas.id')
            ->whereNotNull('proforma_items.configuration')
            ->whereMonth('proformas.created_at', now()->subMonth()->month)
            ->get()
            ->sum(function($item) {
                $config = json_decode($item->configuration, true);
                $price = $item->price;
                $materialCostsArray = $config['material_costs'] ?? [];
                $totalMaterialCost = is_array($materialCostsArray)
                    ? collect($materialCostsArray)->sum('total_cost')
                    : 0;
                $directCostAmount = $totalMaterialCost * (($config['directCost'] ?? 0) / 100);
                $indirectCostAmount = $totalMaterialCost * (($config['indirectCost'] ?? 0) / 100);
                $wasteAmount = $totalMaterialCost * (($config['wastePercentage'] ?? 0) / 100);
                $totalCost = $totalMaterialCost + $directCostAmount + $indirectCostAmount + $wasteAmount;
                return $price - $totalCost;
            });

        $this->cambioRentabilidad = $this->rentabilidadMes - $this->rentabilidadMesPasado;

        $this->costosIndirectos = \DB::table('global_cost_settings')
            ->whereNotNull('valid_until')
            ->where('valid_until', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        $this->diasRestantesCostos = $this->costosIndirectos
            ? now()->diffInDays($this->costosIndirectos->valid_until, false)
            : -999;

        // DATOS PARA GRÁFICOS - SOLO ÓRDENES COMPLETADAS
        $this->datosGrafico = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            // Solo proformas que llegaron a orden Y fueron completadas
            $proformasMes = \DB::table('proforma_items')
                ->join('proformas', 'proforma_items.proforma_id', '=', 'proformas.id')
                ->join('orders', 'proformas.id', '=', 'orders.proforma_id')
                ->where('orders.status', 'completed')
                ->whereNotNull('proforma_items.configuration')
                ->whereMonth('orders.updated_at', $mes->month)
                ->whereYear('orders.updated_at', $mes->year)
                ->get();

            $revenue = $proformasMes->sum(function($item) {
                return $item->price;
            });

            $costosDirectos = $proformasMes->sum(function($item) {
                $config = json_decode($item->configuration, true);
                $materialCostsArray = $config['material_costs'] ?? [];
                $totalMaterialCost = is_array($materialCostsArray)
                    ? collect($materialCostsArray)->sum('total_cost')
                    : 0;
                $directCostAmount = $totalMaterialCost * (($config['directCost'] ?? 0) / 100);
                $wasteAmount = $totalMaterialCost * (($config['wastePercentage'] ?? 0) / 100);
                return $totalMaterialCost + $directCostAmount + $wasteAmount;
            });

            $costosIndirectos = $proformasMes->sum(function($item) {
                $config = json_decode($item->configuration, true);
                $materialCostsArray = $config['material_costs'] ?? [];
                $totalMaterialCost = is_array($materialCostsArray)
                    ? collect($materialCostsArray)->sum('total_cost')
                    : 0;
                return $totalMaterialCost * (($config['indirectCost'] ?? 0) / 100);
            });

            $costosTotales = $costosDirectos + $costosIndirectos;
            $ganancia = $revenue - $costosTotales;

            $this->datosGrafico[] = [
                'mes' => $mes->format('M Y'),
                'revenue' => round($revenue, 2),
                'costosDirectos' => round($costosDirectos, 2),
                'costosIndirectos' => round($costosIndirectos, 2),
                'costosTotales' => round($costosTotales, 2),
                'ganancia' => round($ganancia, 2)
            ];
        }

        $this->totalOrdenes = $this->ordenesPendientes + $this->ordenesAprobadas + $this->ordenesEnProduccion + $this->ordenesCompletadas + $this->ordenesCanceladas;
        $this->porcentajeOrdenes = [
            'pendientes' => $this->totalOrdenes > 0 ? round(($this->ordenesPendientes / $this->totalOrdenes) * 100, 1) : 0,
            'aprobadas' => $this->totalOrdenes > 0 ? round(($this->ordenesAprobadas / $this->totalOrdenes) * 100, 1) : 0,
            'produccion' => $this->totalOrdenes > 0 ? round(($this->ordenesEnProduccion / $this->totalOrdenes) * 100, 1) : 0,
            'completadas' => $this->totalOrdenes > 0 ? round(($this->ordenesCompletadas / $this->totalOrdenes) * 100, 1) : 0,
            'canceladas' => $this->totalOrdenes > 0 ? round(($this->ordenesCanceladas / $this->totalOrdenes) * 100, 1) : 0,
        ];

        // ALERTAS DEL SISTEMA
        $this->alertas = [];
        
        // ========== ALERTAS DE PELIGRO (DANGER) ==========
        
        // 1. No hay costos indirectos configurados
        if ($this->diasRestantesCostos <= -100) {
            $this->alertas[] = [
                'tipo' => 'danger',
                'icono' => 'exclamation-circle',
                'titulo' => 'Sin costos indirectos',
                'mensaje' => "No hay costos indirectos configurados en el sistema.",
                'count' => 1,
                'url' => route('admin.cost-settings')
            ];
        }
        
        // 2. Costos indirectos vencidos
        if ($this->diasRestantesCostos < 0 && $this->diasRestantesCostos > -100) {
            $this->alertas[] = [
                'tipo' => 'danger',
                'icono' => 'exclamation-circle',
                'titulo' => 'Costos indirectos vencidos',
                'mensaje' => "Los costos indirectos han expirado. Configure nuevos costos.",
                'count' => 1,
                'url' => route('admin.cost-settings')
            ];
        }
        
        // 3. Productos sin costos configurados (no tienen registro en product_cost_settings o tienen valores en 0)
        $totalProductos = \DB::table('products')->count();
        $productosConCostos = \DB::table('product_cost_settings')
            ->where('is_active', true)
            ->where('direct_cost_percentage', '>', 0)
            ->where('waste_percentage', '>', 0)
            ->where('profit_margin_percentage', '>', 0)
            ->distinct('product_id')
            ->count('product_id');
        
        $productosSinCostos = $totalProductos - $productosConCostos;
        
        if ($productosSinCostos > 0) {
            $this->alertas[] = [
                'tipo' => 'danger',
                'icono' => 'exclamation-circle',
                'titulo' => 'Productos sin configuración de costos',
                'mensaje' => "{$productosSinCostos} producto(s) sin costos directos, desperdicio o margen de ganancia configurados.",
                'count' => $productosSinCostos,
                'url' => route('admin.product-cost-settings')
            ];
        }
        
        // ========== ALERTAS DE ADVERTENCIA (WARNING) ==========
        
        // 1. Proformas por expirar
        if ($this->proformasPorExpirar > 0) {
            $this->alertas[] = [
                'tipo' => 'warning',
                'icono' => 'clock',
                'titulo' => 'Proformas por expirar',
                'mensaje' => "{$this->proformasPorExpirar} proforma(s) expirarán en los próximos 3 días.",
                'count' => $this->proformasPorExpirar
            ];
        }
        
        // 2. Costos indirectos por expirar
        if ($this->diasRestantesCostos >= 0 && $this->diasRestantesCostos <= 7) {
            $this->alertas[] = [
                'tipo' => 'warning',
                'icono' => 'clock',
                'titulo' => 'Costos indirectos por expirar',
                'mensaje' => "Los costos indirectos vencen en {$this->diasRestantesCostos} días.",
                'count' => 1,
                'url' => route('admin.cost-settings')
            ];
        }
        
        // ========== ALERTAS INFORMATIVAS (INFO) ==========
        
        // 1. Costos indirectos actualizados hoy
        $ultimaActualizacionCostoIndirecto = \DB::table('global_cost_settings')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($ultimaActualizacionCostoIndirecto) {
            $this->alertas[] = [
                'tipo' => 'info',
                'icono' => 'check-circle',
                'titulo' => 'Costos indirectos actualizados hoy',
                'mensaje' => "Se registraron nuevos costos indirectos. Considere recalcular proformas activas.",
                'count' => 1,
                'id' => 'costos_indirectos_' . \Carbon\Carbon::parse($ultimaActualizacionCostoIndirecto->created_at)->timestamp
            ];
        }
        
        // 2. Proformas requieren recálculo por cambios en costos indirectos
        if ($this->proformasConCostosDesactualizados > 0 && !(\Carbon\Carbon::parse($this->lastCostUpdate ?? now())->isToday())) {
            $this->alertas[] = [
                'tipo' => 'info',
                'icono' => 'refresh',
                'titulo' => 'Proformas requieren recálculo',
                'mensaje' => "{$this->proformasConCostosDesactualizados} proforma(s) tienen costos desactualizados.",
                'count' => $this->proformasConCostosDesactualizados,
                'id' => 'proformas_desactualizadas_' . date('Y-m-d')
            ];
        }
        
        // 3. Costos de productos actualizados hoy
        $ultimaActualizacionProducto = \DB::table('product_cost_settings')
            ->whereDate('updated_at', today())
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if ($ultimaActualizacionProducto) {
            $productosActualizadosHoy = \DB::table('product_cost_settings')
                ->whereDate('updated_at', today())
                ->where('is_active', true)
                ->distinct('product_id')
                ->count('product_id');
            
            $this->alertas[] = [
                'tipo' => 'info',
                'icono' => 'check-circle',
                'titulo' => 'Costos de productos actualizados hoy',
                'mensaje' => "{$productosActualizadosHoy} producto(s) con costos actualizados hoy.",
                'count' => $productosActualizadosHoy,
                'id' => 'costos_productos_' . \Carbon\Carbon::parse($ultimaActualizacionProducto->updated_at)->timestamp
            ];
        }
        
        // 4. Cuenta bancaria agregada o modificada recientemente (últimos 7 días, solo owner)
        if ($this->userRole === 'owner') {
            $cuentaBancariaReciente = \DB::table('bank_accounts')
                ->where('user_id', auth()->id())
                ->where(function($query) {
                    $query->where('created_at', '>=', now()->subDays(7))
                          ->orWhere('updated_at', '>=', now()->subDays(7));
                })
                ->orderByRaw('GREATEST(COALESCE(updated_at, created_at), created_at) DESC')
                ->first();
            
            if ($cuentaBancariaReciente) {
                $fechaModificacion = $cuentaBancariaReciente->updated_at ?? $cuentaBancariaReciente->created_at;
                $mensaje = \Carbon\Carbon::parse($cuentaBancariaReciente->created_at)->isToday() 
                    ? "Se agregó una nueva cuenta bancaria hoy."
                    : "Se actualizó la información de cuenta bancaria recientemente.";
                
                $this->alertas[] = [
                    'tipo' => 'info',
                    'icono' => 'check-circle',
                    'titulo' => 'Cuenta bancaria actualizada',
                    'mensaje' => $mensaje,
                    'count' => 1,
                    'id' => 'cuenta_bancaria_' . \Carbon\Carbon::parse($fechaModificacion)->timestamp
                ];
            }
        }

        // Filtrar alertas ya vistas
        $this->alertas = collect($this->alertas)->filter(function($alerta) {
            return !in_array($alerta['id'] ?? null, $this->alertasVistas);
        })->values()->toArray();

        // ACTIVIDAD RECIENTE - Paginada
        $this->actualizarActividadReciente();
        
        // MÉTRICAS DE PROFORMAS - RESUMEN MENSUAL
        $this->proformasCreadasMes = \DB::table('proformas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $this->proformasExpiradasMes = \DB::table('proformas')
            ->whereMonth('expiration_date', now()->month)
            ->whereYear('expiration_date', now()->year)
            ->where('is_expired', true)
            ->count();

        $this->proformasAprobadasMes = \DB::table('proformas')
            ->join('orders', 'proformas.id', '=', 'orders.proforma_id')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->count();
        
        // DATOS PARA GRÁFICO DE PROFORMAS - ÚLTIMOS 6 MESES
        $this->datosGraficoProformas = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            
            $creadas = \DB::table('proformas')
                ->whereMonth('created_at', $mes->month)
                ->whereYear('created_at', $mes->year)
                ->count();
            
            $expiradas = \DB::table('proformas')
                ->whereMonth('expiration_date', $mes->month)
                ->whereYear('expiration_date', $mes->year)
                ->where('is_expired', true)
                ->count();
            
            $aprobadas = \DB::table('proformas')
                ->join('orders', 'proformas.id', '=', 'orders.proforma_id')
                ->whereMonth('orders.created_at', $mes->month)
                ->whereYear('orders.created_at', $mes->year)
                ->count();
            
            $this->datosGraficoProformas[] = [
                'mes' => $mes->format('M Y'),
                'creadas' => $creadas,
                'expiradas' => $expiradas,
                'aprobadas' => $aprobadas,
            ];
        }
        
        // ANÁLISIS DE RENTABILIDAD (Mes actual)
        $this->calcularAnalisisRentabilidad();
    }

    /**
     * Calcular análisis de rentabilidad de órdenes completadas
     */
    private function calcularAnalisisRentabilidad()
    {
        // Órdenes completadas del mes actual con snapshot de costos
        $ordenesMesActual = \DB::table('orders')
            ->join('proformas', 'orders.proforma_id', '=', 'proformas.id')
            ->join('proforma_items', 'proformas.id', '=', 'proforma_items.proforma_id')
            ->where('orders.status', 'completed')
            ->whereMonth('orders.updated_at', now()->month)
            ->whereYear('orders.updated_at', now()->year)
            ->whereNotNull('proforma_items.total_cost')
            ->whereNotNull('proforma_items.profit_amount')
            ->select(
                'orders.id as order_id',
                'orders.number as order_number',
                'proforma_items.price',
                'proforma_items.total_cost',
                'proforma_items.profit_amount',
                'proforma_items.profit_margin_percentage',
                'proforma_items.material_cost',
                'proforma_items.direct_cost',
                'proforma_items.indirect_cost',
                'proforma_items.waste_cost',
                'proforma_items.product_id'
            )
            ->get();
        
        if ($ordenesMesActual->isEmpty()) {
            $this->totalOrdenesAnalizadas = 0;
            $this->margenPromedioMes = 0;
            $this->margenMesPasado = 0;
            $this->tendenciaMargen = 0;
            $this->productosMasVendidos = [];
            $this->ordenMayorMargen = null;
            $this->ordenMenorMargen = null;
            $this->valorTotalOrdenes = 0;
            $this->gananciaTotal = 0;
            return;
        }
        
        // Agrupar por orden
        $ordenesProcesadas = $ordenesMesActual->groupBy('order_id')->map(function($items, $orderId) {
            $precioTotal = $items->sum('price');
            $costoTotal = $items->sum('total_cost');
            $gananciTotal = $items->sum('profit_amount');
            $margenPorcentaje = $precioTotal > 0 ? ($gananciTotal / $precioTotal) * 100 : 0;
            
            return [
                'order_number' => $items->first()->order_number,
                'precio' => $precioTotal,
                'costo' => $costoTotal,
                'ganancia' => $gananciTotal,
                'margen' => $margenPorcentaje,
            ];
        });
        
        // Métricas generales
        $this->totalOrdenesAnalizadas = $ordenesProcesadas->count();
        $this->margenPromedioMes = $ordenesProcesadas->avg('margen');
        $this->valorTotalOrdenes = $ordenesProcesadas->sum('precio');
        $this->gananciaTotal = $ordenesProcesadas->sum('ganancia');
        
        // Margen mes pasado para comparación
        $ordenesMesPasado = \DB::table('orders')
            ->join('proformas', 'orders.proforma_id', '=', 'proformas.id')
            ->join('proforma_items', 'proformas.id', '=', 'proforma_items.proforma_id')
            ->where('orders.status', 'completed')
            ->whereMonth('orders.updated_at', now()->subMonth()->month)
            ->whereYear('orders.updated_at', now()->subMonth()->year)
            ->whereNotNull('proforma_items.total_cost')
            ->whereNotNull('proforma_items.profit_amount')
            ->get();
        
        if ($ordenesMesPasado->isNotEmpty()) {
            $ordenesMesPasadoProcesadas = $ordenesMesPasado->groupBy('order_id')->map(function($items) {
                $precioTotal = $items->sum('price');
                $gananciTotal = $items->sum('profit_amount');
                return $precioTotal > 0 ? ($gananciTotal / $precioTotal) * 100 : 0;
            });
            $this->margenMesPasado = $ordenesMesPasadoProcesadas->avg();
        } else {
            $this->margenMesPasado = 0;
        }
        
        $this->tendenciaMargen = $this->margenPromedioMes - $this->margenMesPasado;
        
        // Productos más vendidos (por cantidad de órdenes y valor)
        $productosProcesados = $ordenesMesActual->groupBy('product_id')->map(function($items, $productId) {
            $product = \App\Models\Product::find($productId);
            $precioTotal = $items->sum('price');
            $cantidadOrdenes = $items->count();
            
            return [
                'nombre' => $product ? $product->name : 'Desconocido',
                'cantidad_ordenes' => $cantidadOrdenes,
                'ventas_totales' => $precioTotal,
            ];
        })->sortByDesc('cantidad_ordenes');
        
        // Calcular porcentajes de participación
        $totalOrdenesPorProducto = $productosProcesados->sum('cantidad_ordenes');
        $totalVentas = $productosProcesados->sum('ventas_totales');
        
        $this->productosMasVendidos = $productosProcesados->map(function($producto) use ($totalOrdenesPorProducto, $totalVentas) {
            return [
                'nombre' => $producto['nombre'],
                'cantidad_ordenes' => $producto['cantidad_ordenes'],
                'porcentaje_ordenes' => $totalOrdenesPorProducto > 0 ? ($producto['cantidad_ordenes'] / $totalOrdenesPorProducto) * 100 : 0,
                'ventas_totales' => $producto['ventas_totales'],
                'porcentaje_ventas' => $totalVentas > 0 ? ($producto['ventas_totales'] / $totalVentas) * 100 : 0,
            ];
        })->values()->toArray();
        
        // Órdenes destacadas
        $ordenadaPorMargen = $ordenesProcesadas->sortByDesc('margen');
        $this->ordenMayorMargen = $ordenadaPorMargen->first();
        $this->ordenMenorMargen = $ordenadaPorMargen->last();
    }


    public function actualizarActividadReciente($pagina = null)
    {
        if ($pagina !== null) {
            $this->paginaActividad = $pagina;
        }
        $actividadReciente = collect();

        // Proformas recientes (usando la fecha de la última configuración)
        $proformasRecientes = \DB::table('proformas')
            ->join('users', 'proformas.user_id', '=', 'users.id')
            ->leftJoin(\DB::raw('(SELECT proforma_id, MAX(created_at) as ultima_config, MAX(updated_at) as ultima_actualizacion FROM proforma_items WHERE configuration IS NOT NULL GROUP BY proforma_id) as ultima_configuracion'), 
                'proformas.id', '=', 'ultima_configuracion.proforma_id')
            ->select(
                'proformas.id as proforma_id',
                'users.name as usuario',
                'proformas.number as referencia',
                \DB::raw('COALESCE(ultima_configuracion.ultima_actualizacion, ultima_configuracion.ultima_config, proformas.created_at) as created_at'),
                \DB::raw("'proforma_creada' as tipo")
            )
            ->orderBy(\DB::raw('COALESCE(ultima_configuracion.ultima_actualizacion, ultima_configuracion.ultima_config, proformas.created_at)'), 'desc')
            ->take(10)
            ->get();

        foreach ($proformasRecientes as $proforma) {
            $configuraciones = \DB::table('proforma_items')
                ->join('products', 'proforma_items.product_id', '=', 'products.id')
                ->where('proforma_items.proforma_id', $proforma->proforma_id)
                ->whereNotNull('proforma_items.configuration')
                ->select(
                    'products.name as producto',
                    'proforma_items.created_at',
                    'proforma_items.updated_at',
                    'proforma_items.price',
                    'proforma_items.quantity',
                    'proforma_items.configuration'
                )
                ->orderBy('proforma_items.created_at', 'asc')
                ->get();
            
            $proforma->configuraciones = $configuraciones;
        }

        // Cambios de costos
        $cambiosCostos = \DB::table('global_cost_settings')
            ->select(
                \DB::raw("'Sistema' as usuario"),
                \DB::raw("CONCAT(indirect_cost_percentage, '%') as referencia"),
                'created_at',
                \DB::raw("'costo_cambiado' as tipo"),
                \DB::raw("NULL as configuraciones")
            )
            ->latest('created_at')
            ->take(10)
            ->get();

        // Órdenes de proformas
        $ordenesProforma = \DB::table('orders')
            ->join('proformas', 'orders.proforma_id', '=', 'proformas.id')
            ->join('users', 'proformas.user_id', '=', 'users.id')
            ->select(
                'users.name as usuario',
                'orders.id as orden_id',
                'orders.status as estado',
                'proformas.number as proforma_numero',
                'proformas.id as proforma_id',
                'orders.updated_at as created_at',
                \DB::raw("'proforma_a_orden' as tipo"),
                \DB::raw('NULL as configuraciones')
            )
            ->latest('orders.updated_at')
            ->take(10)
            ->get();

        // Combinar y ordenar toda la actividad
        $todas = $actividadReciente
            ->concat($proformasRecientes)
            ->concat($cambiosCostos)
            ->concat($ordenesProforma)
            ->sortByDesc('created_at');

        $this->totalActividadReciente = $todas->count();
        $this->actividadReciente = $todas->slice(($this->paginaActividad - 1) * $this->actividadPorPagina, $this->actividadPorPagina)->values();
    }

    public function siguientePaginaActividad()
    {
        if ($this->paginaActividad < ceil($this->totalActividadReciente / $this->actividadPorPagina)) {
            $this->paginaActividad++;
            $this->actualizarActividadReciente();
        }
    }

    public function anteriorPaginaActividad()
    {
        if ($this->paginaActividad > 1) {
            $this->paginaActividad--;
            $this->actualizarActividadReciente();
        }
    }

    public function marcarAlertaVista($alertaId)
    {
        if (!in_array($alertaId, $this->alertasVistas)) {
            $this->alertasVistas[] = $alertaId;
            session()->put('alertas_vistas', $this->alertasVistas);
        }
        
        // Filtrar la alerta de la lista
        $this->alertas = collect($this->alertas)->filter(function($alerta) use ($alertaId) {
            return ($alerta['id'] ?? null) !== $alertaId;
        })->values()->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('partials.sidebar');
    }
}