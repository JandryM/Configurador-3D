<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Livewire\Traits\WithCustomPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderMaterialCalculator;

class OrdersTable extends Component
{
    use WithCustomPagination;

    protected $listeners = ['pageChanged' => 'actualizarOrdenes', 'perPageChanged' => 'handlePerPageChanged'];

    public $orders = [];
    public $showOrderModal = false;
    public $selectedOrder = null;
    public $showEstimatedTimeModal = false;
    public $estimatedDays = 7; // Valor por defecto: 1 semana
    public $pendingProductionOrderId = null;
    public $customDate = false;
    public $customEstimatedDate = null;
    public $showInsufficientStockModal = false;
    public $insufficientMaterials = [];
    public $allOrders = [];
    
    // Modales de confirmación
    public $showApproveConfirmModal = false;
    public $showPaidConfirmModal = false;
    public $showCancelConfirmModal = false;
    public $showCompleteConfirmModal = false;
    public $showRejectProofConfirmModal = false;
    public $showNoBankAccountModal = false;
    public $pendingActionOrderId = null;
    public $pendingActionOrder = null;
    
    // Filtros
    public $search = '';
    public $statusFilter = '';

    public function mount()
    {
        $this->perPage = 5;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->allOrders = DB::table('orders')
            ->join('proformas', 'orders.proforma_id', '=', 'proformas.id')
            ->leftJoin('users', 'proformas.user_id', '=', 'users.id')
            ->select(
                'orders.*',
                'proformas.user_id',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderByDesc('orders.created_at')
            ->get()
            ->map(function ($order) {
                // Obtener solo los ítems activos de la proforma asociada a la orden
                $items = DB::table('proforma_items')
                    ->where('proforma_id', $order->proforma_id)
                    ->where('is_active', true)
                    ->get();
                $amount = $items->sum('price');
                $quantity = $items->sum('quantity');
                $firstItem = $items->first();
                $config = $firstItem ? (json_decode($firstItem->configuration, true) ?? []) : [];
                $product = $firstItem ? Product::find($firstItem->product_id) : null;
                $user = User::find($order->user_id);

                // Preparar detalle de productos para la vista
                $itemsDetail = [];
                foreach ($items as $item) {
                    $itemProduct = Product::find($item->product_id);
                    $itemConfig = json_decode($item->configuration, true) ?? [];
                    $itemsDetail[] = [
                        'product' => $itemProduct,
                        'product_name' => $itemProduct ? $itemProduct->name : 'Producto eliminado',
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price,
                        'price' => $item->price,
                        'configuration' => $itemConfig,
                    ];
                }
                return [
                    'id' => $order->id,
                    'number' => $order->number,
                    'proforma_id' => $order->proforma_id,
                    'status' => $order->status,
                    'payment_proof' => $order->payment_proof,
                    'client' => $user ? $user->name : ($order->user_name ?? 'Usuario eliminado'),
                    'email' => $user ? $user->email : ($order->user_email ?? '-'),
                    'product_name' => $product ? $product->name : 'Producto eliminado',
                    'amount' => $amount,
                    'quantity' => $quantity,
                    'created_at' => $order->created_at,
                    'product_created_at' => $order->product_created_at,
                    'estimated_finish_at' => $order->estimated_finish_at,
                    'product' => $product,
                    'user' => $user,
                    'configuration' => $config,
                    'items' => $itemsDetail,
                ];
            });

        $this->total = $this->allOrders->count();
        $this->actualizarOrdenes();
    }

    public function actualizarOrdenes()
    {
        // Aplicar filtros
        $filteredOrders = $this->allOrders;
        
        // Filtro de búsqueda
        if ($this->search !== '') {
            $search = strtolower($this->search);
            $filteredOrders = $filteredOrders->filter(function ($order) use ($search) {
                return str_contains(strtolower($order['number']), $search) ||
                       str_contains(strtolower($order['client']), $search);
            });
        }
        
        // Filtro por estado
        if ($this->statusFilter !== '') {
            $filteredOrders = $filteredOrders->filter(function ($order) {
                return $order['status'] === $this->statusFilter;
            });
        }
        
        // Actualizar total con filtros aplicados
        $this->total = $filteredOrders->count();
        
        $this->orders = $filteredOrders
            ->skip(($this->page - 1) * $this->perPage)
            ->take($this->perPage)
            ->values()
            ->toArray();
    }

    public function handlePerPageChanged()
    {
        $this->resetPage();
        $this->actualizarOrdenes();
    }

    public function showOrder($orderId)
    {
        // Buscar en allOrders para tener todos los datos incluyendo items
        $order = $this->allOrders->firstWhere('id', $orderId);
        
        if ($order) {
            $this->selectedOrder = is_array($order) ? $order : (array) $order;
            $this->showOrderModal = true;
        }
    }

    public function closeModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
    }
    
    public function goToProforma($proformaId)
    {
        // Guardar el ID de la proforma en sesión
        session()->put('open_proforma_id', $proformaId);
        
        // Redirigir a la vista de proformas
        return redirect()->route('admin.proformas.index');
    }

    public function updateOrderStatusQuick($orderId, $newStatus)
    {
        // Verificar permisos: solo admin y owner pueden modificar estados
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para modificar el estado de las órdenes.');
            return false;
        }

        // Actualizar en base de datos
        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);

        // Si se aprueba, establecer fecha de inicio de producción
        if ($newStatus === 'in_production' && !DB::table('orders')->where('id', $orderId)->value('product_created_at')) {
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['product_created_at' => now()]);
        }

        // Actualizar solo en la colección local sin recargar todo
        $this->allOrders = $this->allOrders->map(function($order) use ($orderId, $newStatus) {
            if ($order['id'] == $orderId) {
                $order['status'] = $newStatus;
            }
            return $order;
        });

        // Replicar en orders filtrados
        $this->actualizarOrdenes();

        return true;
    }

    public function approveOrder($orderId)
    {
        // Abrir modal de confirmación
        $this->pendingActionOrderId = $orderId;
        $this->pendingActionOrder = $this->allOrders->firstWhere('id', $orderId);
        $this->showApproveConfirmModal = true;
    }

    public function confirmApproveOrder()
    {
        // Verificar si existe al menos una cuenta bancaria
        $bankAccountExists = DB::table('bank_accounts')
            ->exists();

        if (!$bankAccountExists) {
            $this->closeApproveConfirmModal();
            $this->showNoBankAccountModal = true;
            return;
        }

        $success = $this->updateOrderStatusQuick($this->pendingActionOrderId, 'approved');

        if ($success) {
            // Buscar la orden recién aprobada
            $order = $this->allOrders->firstWhere('id', $this->pendingActionOrderId);
            if ($order && $order['user']) {
                // Enviar notificación al cliente con los datos bancarios
                $client = $order['user'];
                try {
                    $client->notify(new \App\Notifications\SendBankAccountDataToClient($order, $client));
                } catch (\Exception $e) {
                    \Log::error('No se pudo enviar la notificación de datos bancarios al cliente: ' . $e->getMessage());
                }
            }

            session()->flash('message', 'Orden aprobada exitosamente. Notificación enviada al cliente.');
        }

        // Cerrar modal
        $this->closeApproveConfirmModal();
    }

    public function closeApproveConfirmModal()
    {
        $this->showApproveConfirmModal = false;
        $this->pendingActionOrderId = null;
        $this->pendingActionOrder = null;
    }

    public function closeNoBankAccountModal()
    {
        $this->showNoBankAccountModal = false;
    }

    public function markAsPaid($orderId)
    {
        // Abrir modal de confirmación
        $this->pendingActionOrderId = $orderId;
        $this->pendingActionOrder = $this->allOrders->firstWhere('id', $orderId);
        $this->showPaidConfirmModal = true;
    }

    public function confirmMarkAsPaid()
    {
        $success = $this->updateOrderStatusQuick($this->pendingActionOrderId, 'paid');

        if ($success) {
            // Enviar notificación al cliente sobre pago verificado
            $order = DB::table('orders')->where('id', $this->pendingActionOrderId)->first();
            if ($order && $order->proforma_id) {
                $proforma = DB::table('proformas')->where('id', $order->proforma_id)->first();
                if ($proforma && $proforma->user_id) {
                    $user = User::find($proforma->user_id);
                    if ($user) {
                        // Calcular el monto total de los items activos
                        $amount = DB::table('proforma_items')
                            ->where('proforma_id', $proforma->id)
                            ->where('is_active', true)
                            ->sum('price');
                        
                        // Crear objeto con datos para la notificación
                        $orderData = (object)[
                            'number' => $order->number,
                            'amount' => $amount,
                        ];
                        
                        $user->notify(new \App\Notifications\PaymentVerified($orderData));
                    }
                }
            }
            
            session()->flash('message', 'Pago verificado exitosamente. La orden está lista para iniciar producción.');
        }

        // Cerrar modal
        $this->closePaidConfirmModal();
    }

    public function closePaidConfirmModal()
    {
        $this->showPaidConfirmModal = false;
        $this->pendingActionOrderId = null;
        $this->pendingActionOrder = null;
    }

    public function rejectPaymentProof($orderId)
    {
        if (!$this->canModifyOrders()) {
            session()->flash('error', 'No tienes permisos para rechazar comprobantes.');
            return;
        }

        // Obtener la orden
        $order = DB::table('orders')->where('id', $orderId)->first();

        if (!$order) {
            session()->flash('error', 'Orden no encontrada.');
            return;
        }

        if ($order->status !== 'approved') {
            session()->flash('error', 'Solo puedes rechazar comprobantes de órdenes aprobadas.');
            return;
        }

        if (!$order->payment_proof) {
            session()->flash('error', 'No hay comprobante para rechazar.');
            return;
        }

        // Eliminar archivo del storage
        if (Storage::disk('public')->exists($order->payment_proof)) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Actualizar base de datos
        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'payment_proof' => null,
                'updated_at' => now()
            ]);

        // Obtener usuario para enviar notificación
        $proforma = DB::table('proformas')->where('id', $order->proforma_id)->first();
        if ($proforma && $proforma->user_id) {
            $user = User::find($proforma->user_id);
            if ($user) {
                // Calcular el monto total de los items activos de la proforma
                $amount = DB::table('proforma_items')
                    ->where('proforma_id', $proforma->id)
                    ->where('is_active', true)
                    ->sum('price');
                
                // Crear objeto con datos necesarios para la notificación
                $orderData = (object)[
                    'number' => $order->number,
                    'amount' => $amount,
                    'proforma_id' => $order->proforma_id,
                ];
                
                $user->notify(new \App\Notifications\PaymentProofRejected($orderData));
            }
        }

        // Cerrar modal de confirmación de pago
        $this->closePaidConfirmModal();

        session()->flash('message', 'Comprobante rechazado y eliminado. Se ha notificado al cliente.');
        
        // Recargar órdenes
        $this->loadOrders();
    }

    public function startProduction($orderId)
    {
        // Verificar disponibilidad de materiales antes de abrir modal de tiempo estimado
        $calculator = new OrderMaterialCalculator();
        $result = $calculator->calculateOrderMaterials($orderId);

        if (!$result['canProduce']) {
            // Mostrar modal de materiales insuficientes
            $this->insufficientMaterials = $result['insufficient'];
            $this->pendingProductionOrderId = $orderId;
            $this->showInsufficientStockModal = true;
            return;
        }

        // Si hay suficiente stock, abrir modal para ingresar tiempo estimado
        $this->pendingProductionOrderId = $orderId;
        $this->estimatedDays = 7; // Por defecto 1 semana
        $this->customDate = false;
        $this->customEstimatedDate = null;
        $this->showEstimatedTimeModal = true;
    }

    public function closeInsufficientStockModal()
    {
        $this->showInsufficientStockModal = false;
        $this->insufficientMaterials = [];
        $this->pendingProductionOrderId = null;
    }

    public function toggleCustomDate()
    {
        $this->customDate = !$this->customDate;
        if ($this->customDate) {
            // Establecer fecha personalizada inicial basada en los días seleccionados
            $this->customEstimatedDate = now()->addDays($this->estimatedDays)->format('Y-m-d');
        } else {
            $this->customEstimatedDate = null;
        }
    }

    public function confirmStartProduction()
    {
        // Determinar la fecha estimada de finalización
        if ($this->customDate) {
            // Validar fecha personalizada
            if (!$this->customEstimatedDate) {
                session()->flash('error', 'Debes seleccionar una fecha estimada de finalización.');
                return;
            }

            $estimatedFinishDate = \Carbon\Carbon::parse($this->customEstimatedDate);

            // Validar que la fecha no sea en el pasado
            if ($estimatedFinishDate->lt(now()->startOfDay())) {
                session()->flash('error', 'La fecha estimada no puede ser en el pasado.');
                return;
            }
        } else {
            // Validar días seleccionados
            if (!$this->estimatedDays || $this->estimatedDays < 1) {
                session()->flash('error', 'Debes seleccionar un periodo válido.');
                return;
            }

            $estimatedFinishDate = now()->addDays($this->estimatedDays);
        }

        // Deducir materiales del inventario
        $calculator = new OrderMaterialCalculator();
        $deductionResult = $calculator->deductOrderMaterials($this->pendingProductionOrderId, auth()->id());

        if (!$deductionResult['success']) {
            session()->flash('error', $deductionResult['error'] ?? 'Error al deducir materiales del inventario.');
            
            if (isset($deductionResult['insufficient'])) {
                $this->insufficientMaterials = $deductionResult['insufficient'];
                $this->showEstimatedTimeModal = false;
                $this->showInsufficientStockModal = true;
            }
            
            return;
        }

        // Actualizar estado a "en producción"
        DB::table('orders')
            ->where('id', $this->pendingProductionOrderId)
            ->update([
                'status' => 'in_production',
                'product_created_at' => now(),
                'estimated_finish_at' => $estimatedFinishDate,
                'updated_at' => now()
            ]);

        // Enviar notificación al cliente sobre inicio de producción
        $order = DB::table('orders')->where('id', $this->pendingProductionOrderId)->first();
        if ($order && $order->proforma_id) {
            $proforma = DB::table('proformas')->where('id', $order->proforma_id)->first();
            if ($proforma && $proforma->user_id) {
                $user = User::find($proforma->user_id);
                if ($user) {
                    // Calcular el monto total
                    $amount = DB::table('proforma_items')
                        ->where('proforma_id', $proforma->id)
                        ->where('is_active', true)
                        ->sum('price');
                    
                    // Crear objeto con datos para la notificación
                    $orderData = (object)[
                        'number' => $order->number,
                        'amount' => $amount,
                        'estimated_finish_at' => $estimatedFinishDate,
                    ];
                    
                    $user->notify(new \App\Notifications\OrderInProduction($orderData));
                }
            }
        }

        $this->loadOrders();

        if ($this->selectedOrder && $this->selectedOrder['id'] == $this->pendingProductionOrderId) {
            $this->selectedOrder = $this->allOrders->firstWhere('id', $this->pendingProductionOrderId);
        }

        // Cerrar modal y limpiar datos
        $this->showEstimatedTimeModal = false;
        $this->estimatedDays = 7;
        $this->customDate = false;
        $this->customEstimatedDate = null;
        $this->pendingProductionOrderId = null;

        session()->flash('message', 'Producción iniciada exitosamente. Materiales deducidos del inventario. Fecha estimada de finalización: ' . $estimatedFinishDate->format('d/m/Y'));
    }

    public function cancelEstimatedTimeModal()
    {
        $this->showEstimatedTimeModal = false;
        $this->estimatedDays = 7;
        $this->customDate = false;
        $this->customEstimatedDate = null;
        $this->pendingProductionOrderId = null;
    }

    public function completeOrder($orderId)
    {
        // Abrir modal de confirmación
        $this->pendingActionOrderId = $orderId;
        $this->pendingActionOrder = $this->allOrders->firstWhere('id', $orderId);
        $this->showCompleteConfirmModal = true;
    }

    public function confirmCompleteOrder()
    {
        $success = $this->updateOrderStatusQuick($this->pendingActionOrderId, 'completed');
        
        if ($success) {
            // Enviar notificación al cliente sobre orden completada
            $order = DB::table('orders')->where('id', $this->pendingActionOrderId)->first();
            if ($order && $order->proforma_id) {
                $proforma = DB::table('proformas')->where('id', $order->proforma_id)->first();
                if ($proforma && $proforma->user_id) {
                    $user = User::find($proforma->user_id);
                    if ($user) {
                        // Calcular el monto total
                        $amount = DB::table('proforma_items')
                            ->where('proforma_id', $proforma->id)
                            ->where('is_active', true)
                            ->sum('price');
                        
                        // Crear objeto con datos para la notificación
                        $orderData = (object)[
                            'number' => $order->number,
                            'amount' => $amount,
                        ];
                        
                        $user->notify(new \App\Notifications\OrderCompleted($orderData));
                    }
                }
            }
        }
        
        session()->flash('message', 'Orden marcada como completada exitosamente.');
        $this->closeCompleteConfirmModal();
    }

    public function closeCompleteConfirmModal()
    {
        $this->showCompleteConfirmModal = false;
        $this->pendingActionOrderId = null;
        $this->pendingActionOrder = null;
    }

    public function openRejectProofModal()
    {
        // Usar el pendingActionOrder que ya está cargado del modal de "Marcar como pagada"
        if ($this->pendingActionOrder) {
            // Cerrar el modal de confirmación de pago
            $this->showPaidConfirmModal = false;
            // Abrir el modal de rechazo
            $this->showRejectProofConfirmModal = true;
        }
    }

    public function closeRejectProofConfirmModal()
    {
        $this->showRejectProofConfirmModal = false;
        // Reabrir el modal de confirmación de pago
        $this->showPaidConfirmModal = true;
    }

    public function confirmRejectPaymentProof()
    {
        if ($this->pendingActionOrder) {
            $this->rejectPaymentProof($this->pendingActionOrder['id']);
            $this->closeRejectProofConfirmModal();
            // Cerrar también el modal de pago después de rechazar
            $this->showPaidConfirmModal = false;
        }
    }

    public function cancelOrder($orderId)
    {
        // Abrir modal de confirmación
        $this->pendingActionOrderId = $orderId;
        $this->pendingActionOrder = $this->allOrders->firstWhere('id', $orderId);
        $this->showCancelConfirmModal = true;
    }

    public function confirmCancelOrder()
    {
        $success = $this->updateOrderStatusQuick($this->pendingActionOrderId, 'cancelled');
        
        if ($success) {
            // Enviar notificación al cliente sobre orden cancelada
            $order = DB::table('orders')->where('id', $this->pendingActionOrderId)->first();
            if ($order && $order->proforma_id) {
                $proforma = DB::table('proformas')->where('id', $order->proforma_id)->first();
                if ($proforma && $proforma->user_id) {
                    $user = User::find($proforma->user_id);
                    if ($user) {
                        // Calcular el monto total
                        $amount = DB::table('proforma_items')
                            ->where('proforma_id', $proforma->id)
                            ->where('is_active', true)
                            ->sum('price');
                        
                        // Crear objeto con datos para la notificación
                        $orderData = (object)[
                            'number' => $order->number,
                            'amount' => $amount,
                        ];
                        
                        $user->notify(new \App\Notifications\OrderCancelled($orderData));
                    }
                }
            }
        }
        
        session()->flash('message', 'Orden cancelada exitosamente.');
        $this->closeCancelConfirmModal();
    }

    public function closeCancelConfirmModal()
    {
        $this->showCancelConfirmModal = false;
        $this->pendingActionOrderId = null;
        $this->pendingActionOrder = null;
    }

    public function canModifyOrders()
    {
        return in_array(auth()->user()->role, ['admin', 'owner']);
    }

    public function setEstimatedFinishDate($orderId, $date)
    {
        // Verificar permisos: solo admin y owner pueden modificar fechas
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para modificar las fechas estimadas.');
            return;
        }

        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'estimated_finish_at' => $date,
                'updated_at' => now()
            ]);

        $this->loadOrders();

        if ($this->selectedOrder && $this->selectedOrder['id'] == $orderId) {
            $this->selectedOrder = collect($this->orders)->firstWhere('id', $orderId);
        }

        session()->flash('message', 'Fecha estimada actualizada exitosamente.');
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
        $this->actualizarOrdenes();
    }
    
    public function updatedStatusFilter()
    {
        $this->resetPage();
        $this->actualizarOrdenes();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->actualizarOrdenes();
    }

    public function render()
    {
        // Ganancia de órdenes completadas (real)
        $gananciaCompletadas = $this->allOrders->where('status', 'completed')->sum('amount');
        
        // Ganancia estimada total de órdenes pagadas (incluye paid, in_production y completed)
        $gananciaEstimada = $this->allOrders->filter(fn($o) => in_array($o['status'], ['paid', 'in_production', 'completed']))->sum('amount');
        
        $cantidadProductos = $this->allOrders->sum('quantity');
        $ordenesTerminadas = $this->allOrders->where('status', 'completed')->count();
        $comprobantesPendientes = $this->allOrders->filter(fn($o) => $o['status'] === 'approved' && !empty($o['payment_proof']))->count();

        return view('livewire.admin.order.orders-table', [
            'canModify' => $this->canModifyOrders(),
            'gananciaCompletadas' => $gananciaCompletadas,
            'gananciaEstimada' => $gananciaEstimada,
            'cantidadProductos' => $cantidadProductos,
            'ordenesTerminadas' => $ordenesTerminadas,
            'comprobantesPendientes' => $comprobantesPendientes,
        ])->layout('partials.sidebar');
    }
}
