<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkerDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $dateFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedOrder = null;
    public $showOrderModal = false;
    public $showStatusModal = false;
    public $newStatus = '';
    public $statusNotes = '';
    public $workerStats = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'order-updated' => 'loadWorkerStats',
        'notification-received' => 'handleNotification'
    ];

    public function mount()
    {
        $this->loadWorkerStats();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function showOrderDetails($orderId)
    {
        $this->selectedOrder = Order::with(['items.product', 'user', 'files'])
            ->find($orderId);
        $this->showOrderModal = true;
    }

    public function closeOrderModal()
    {
        $this->showOrderModal = false;
        $this->selectedOrder = null;
    }

    public function showStatusChangeModal($orderId)
    {
        $this->selectedOrder = Order::find($orderId);
        $this->newStatus = $this->selectedOrder->status;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedOrder = null;
        $this->newStatus = '';
        $this->statusNotes = '';
    }

    public function updateOrderStatus()
    {
        try {
            if (!$this->selectedOrder) {
                $this->dispatch('show-error', 'Pedido no encontrado');
                return;
            }

            $oldStatus = $this->selectedOrder->status;
            
            // Validar transición de estado
            if (!$this->isValidStatusTransition($oldStatus, $this->newStatus)) {
                $this->dispatch('show-error', 'Transición de estado no válida');
                return;
            }

            DB::beginTransaction();

            // Actualizar el estado del pedido
            $this->selectedOrder->update([
                'status' => $this->newStatus,
                'status_updated_at' => now(),
                'status_updated_by' => Auth::id(),
                'status_notes' => $this->statusNotes,
            ]);

            // Si se marca como entregado, registrar fecha de entrega
            if ($this->newStatus === 'delivered') {
                $this->selectedOrder->update([
                    'delivered_at' => now(),
                ]);
            }

            // Registrar en logs
            Log::info('Estado de pedido actualizado', [
                'order_id' => $this->selectedOrder->id,
                'order_number' => $this->selectedOrder->order_number,
                'old_status' => $oldStatus,
                'new_status' => $this->newStatus,
                'worker_id' => Auth::id(),
                'worker_name' => Auth::user()->name,
                'notes' => $this->statusNotes,
            ]);

            DB::commit();

            // Notificar al cliente
            $this->notifyCustomer($this->selectedOrder, $oldStatus, $this->newStatus);

            $this->dispatch('show-success', 'Estado del pedido actualizado correctamente');
            $this->closeStatusModal();
            $this->loadWorkerStats();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error', 'Error al actualizar el estado del pedido');
            Log::error('Error updating order status: ' . $e->getMessage());
        }
    }

    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['review', 'cancelled'],
            'review' => ['production', 'cancelled'],
            'production' => ['ready', 'cancelled'],
            'ready' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [], // Estado final
            'cancelled' => [], // Estado final
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    private function notifyCustomer($order, $oldStatus, $newStatus)
    {
        // Disparar evento para sincronización en tiempo real
        $this->dispatch('order-status-updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'worker_id' => Auth::id(),
            'worker_name' => Auth::user()->name,
            'customer_id' => $order->user_id,
            'customer_name' => $order->user->name,
        ]);

        // Disparar evento para notificar al cliente
        $this->dispatch('customer-notification', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'message' => $this->getStatusMessage($newStatus),
            'status' => $newStatus,
        ]);
    }

    private function getStatusMessage($status)
    {
        $statusMessages = [
            'review' => 'Tu pedido está siendo revisado',
            'production' => 'Tu pedido está en producción',
            'ready' => 'Tu pedido está listo para envío',
            'shipped' => 'Tu pedido ha sido enviado',
            'delivered' => 'Tu pedido ha sido entregado',
            'cancelled' => 'Tu pedido ha sido cancelado',
        ];

        return $statusMessages[$status] ?? 'El estado de tu pedido ha sido actualizado';
    }

    public function loadWorkerStats()
    {
        $workerId = Auth::id();
        
        $this->workerStats = [
            'total_orders' => Order::count(),
            'my_orders' => Order::where('status_updated_by', $workerId)->count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'review'])->count(),
            'production_orders' => Order::where('status', 'production')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'urgent_orders' => Order::where('priority', 'urgent')
                ->whereIn('status', ['pending', 'review', 'production'])
                ->count(),
            'completed_today' => Order::where('status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
        ];
    }

    public function handleNotification($data)
    {
        $this->dispatch('show-info', $data['message'] ?? 'Nueva notificación recibida');
        $this->loadWorkerStats();
    }

    public function getStatusesProperty()
    {
        return [
            'pending' => 'Pendiente',
            'review' => 'En Revisión',
            'production' => 'En Producción',
            'ready' => 'Listo para Envío',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];
    }

    public function getPrioritiesProperty()
    {
        return [
            'normal' => 'Normal',
            'urgent' => 'Urgente',
            'high' => 'Alta',
        ];
    }

    public function getStatusColorProperty()
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'review' => 'bg-blue-100 text-blue-800',
            'production' => 'bg-orange-100 text-orange-800',
            'ready' => 'bg-green-100 text-green-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
    }

    public function getPriorityColorProperty()
    {
        return [
            'normal' => 'bg-gray-100 text-gray-800',
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
        ];
    }

    public function render()
    {
        $query = Order::with(['items.product', 'user']);

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter) {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
            }
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        $orders = $query->paginate(15);

        return view('livewire.worker-dashboard', [
            'orders' => $orders,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
            'statusColors' => $this->statusColor,
            'priorityColors' => $this->priorityColor,
        ]);
    }
}