<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;

class OrdersTable extends Component
{
    public $orders = [];
    public $showOrderModal = false;
    public $selectedOrder = null;
    public $showEstimatedTimeModal = false;
    public $estimatedDays = 7; // Valor por defecto: 1 semana
    public $pendingProductionOrderId = null;
    public $customDate = false;
    public $customEstimatedDate = null;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = DB::table('orders')
            ->join('proformas', 'orders.proforma_id', '=', 'proformas.id')
            ->leftJoin('users', 'proformas.user_id', '=', 'users.id')
            ->select(
                'orders.*',
                'proformas.product_id',
                'proformas.price',
                'proformas.configuration',
                'proformas.user_id',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderByDesc('orders.created_at')
            ->get()
            ->map(function ($order) {
                $config = json_decode($order->configuration, true) ?? [];
                $product = Product::find($order->product_id);
                $user = User::find($order->user_id);

                return [
                    'id' => $order->id,
                    'number' => $order->number,
                    'proforma_id' => $order->proforma_id,
                    'status' => $order->status,
                    'client' => $user ? $user->name : ($order->user_name ?? 'Usuario eliminado'),
                    'email' => $user ? $user->email : ($order->user_email ?? '-'),
                    'product_name' => $product ? $product->name : 'Producto eliminado',
                    'amount' => $order->price,
                    'quantity' => $config['quantity'] ?? 1,
                    'created_at' => $order->created_at,
                    'product_created_at' => $order->product_created_at,
                    'estimated_finish_at' => $order->estimated_finish_at,
                    'product' => $product,
                    'user' => $user,
                    'configuration' => $config,
                ];
            })
            ->toArray();
    }

    public function showOrder($orderId)
    {
        $this->selectedOrder = collect($this->orders)->firstWhere('id', $orderId);
        $this->showOrderModal = true;
    }

    public function closeModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        // Verificar permisos: solo admin y owner pueden modificar estados
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            session()->flash('error', 'No tienes permisos para modificar el estado de las órdenes.');
            return;
        }

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

        $this->loadOrders();
        
        if ($this->selectedOrder && $this->selectedOrder['id'] == $orderId) {
            $this->selectedOrder = collect($this->orders)->firstWhere('id', $orderId);
        }

        session()->flash('message', 'Estado de la orden actualizado exitosamente.');
    }

    public function approveOrder($orderId)
    {
        $this->updateOrderStatus($orderId, 'approved');

        // Buscar la orden recién aprobada
        $order = collect($this->orders)->firstWhere('id', $orderId);
        if ($order && $order['user']) {
            // Enviar notificación al cliente con los datos bancarios
            $client = $order['user'];
            try {
                $client->notify(new \App\Notifications\SendBankAccountDataToClient($order, $client));
            } catch (\Exception $e) {
                \Log::error('No se pudo enviar la notificación de datos bancarios al cliente: ' . $e->getMessage());
            }
        }
    }

    public function startProduction($orderId)
    {
        // Abrir modal para ingresar tiempo estimado
        $this->pendingProductionOrderId = $orderId;
        $this->estimatedDays = 7; // Por defecto 1 semana
        $this->customDate = false;
        $this->customEstimatedDate = null;
        $this->showEstimatedTimeModal = true;
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

        // Actualizar estado a "en producción"
        DB::table('orders')
            ->where('id', $this->pendingProductionOrderId)
            ->update([
                'status' => 'in_production',
                'product_created_at' => now(),
                'estimated_finish_at' => $estimatedFinishDate,
                'updated_at' => now()
            ]);

        $this->loadOrders();
        
        if ($this->selectedOrder && $this->selectedOrder['id'] == $this->pendingProductionOrderId) {
            $this->selectedOrder = collect($this->orders)->firstWhere('id', $this->pendingProductionOrderId);
        }

        // Cerrar modal y limpiar datos
        $this->showEstimatedTimeModal = false;
        $this->estimatedDays = 7;
        $this->customDate = false;
        $this->customEstimatedDate = null;
        $this->pendingProductionOrderId = null;

        session()->flash('message', 'Producción iniciada exitosamente. Fecha estimada de finalización: ' . $estimatedFinishDate->format('d/m/Y'));
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
        $this->updateOrderStatus($orderId, 'completed');
    }

    public function cancelOrder($orderId)
    {
        $this->updateOrderStatus($orderId, 'cancelled');
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

    public function render()
    {
        return view('livewire.admin.order.orders-table', [
            'canModify' => $this->canModifyOrders()
        ])->layout('partials.sidebar');
    }
}
