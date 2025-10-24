<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class WorkerOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $priority = '';
    public $assignedWorker = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'assignedWorker' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedPriority()
    {
        $this->resetPage();
    }

    public function updatedAssignedWorker()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
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

    public function clearFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->priority = '';
        $this->assignedWorker = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updateOrderStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->dispatch('show-error', 'Pedido no encontrado');
            return;
        }

        // Verificar permisos según el rol
        $user = Auth::user();
        if (!$this->canUpdateStatus($user, $order, $status)) {
            $this->dispatch('show-error', 'No tienes permisos para realizar esta acción');
            return;
        }

        $order->update(['status' => $status]);
        
        // Crear notificación para el cliente
        $order->notifications()->create([
            'user_id' => $order->user_id,
            'type' => 'status_update',
            'title' => 'Estado del pedido actualizado',
            'message' => "Tu pedido #{$order->order_number} ha cambiado a: {$order->status_label}",
            'data' => ['order_id' => $order->id, 'status' => $status]
        ]);

        $this->dispatch('show-success', 'Estado del pedido actualizado correctamente');
    }

    public function assignOrder($orderId, $workerId)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->dispatch('show-error', 'Pedido no encontrado');
            return;
        }

        $order->update(['assigned_worker_id' => $workerId]);
        $this->dispatch('show-success', 'Pedido asignado correctamente');
    }

    public function addInternalNote($orderId, $note)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            $this->dispatch('show-error', 'Pedido no encontrado');
            return;
        }

        $order->update(['internal_notes' => $note]);
        $this->dispatch('show-success', 'Nota interna agregada');
    }

    private function canUpdateStatus($user, $order, $status)
    {
        // Los administradores pueden cambiar cualquier estado
        if ($user->role === 'admin') {
            return true;
        }

        // Los supervisores pueden cambiar la mayoría de estados
        if ($user->role === 'supervisor') {
            return true;
        }

        // Los diseñadores solo pueden cambiar de 'pending' a 'review' y viceversa
        if ($user->role === 'designer') {
            return in_array($status, ['pending', 'review']);
        }

        // Los bordadores solo pueden cambiar de 'review' a 'production' y viceversa
        if ($user->role === 'embroiderer') {
            return in_array($status, ['review', 'production']);
        }

        // Los encargados de entrega pueden cambiar de 'production' a 'ready', 'shipped' y 'delivered'
        if ($user->role === 'delivery_manager') {
            return in_array($status, ['production', 'ready', 'shipped', 'delivered']);
        }

        return false;
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Order::with(['user', 'assignedWorker', 'items.product']);

        // Aplicar filtros según el rol del usuario
        if ($user->role === 'designer') {
            $query->whereIn('status', ['pending', 'review']);
        } elseif ($user->role === 'embroiderer') {
            $query->whereIn('status', ['review', 'production']);
        } elseif ($user->role === 'delivery_manager') {
            $query->whereIn('status', ['production', 'ready', 'shipped', 'delivered']);
        } elseif ($user->role !== 'admin' && $user->role !== 'supervisor') {
            $query->where('assigned_worker_id', $user->id);
        }

        // Aplicar filtros de búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        if ($this->assignedWorker) {
            $query->where('assigned_worker_id', $this->assignedWorker);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        $orders = $query->paginate(15);

        // Obtener opciones para filtros
        $workers = User::where('is_active', true)
            ->whereIn('role', ['designer', 'embroiderer', 'delivery_manager'])
            ->get();

        $statuses = [
            'pending' => 'Pendiente',
            'review' => 'En Revisión',
            'production' => 'En Producción',
            'ready' => 'Listo para Entrega',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];

        $priorities = [
            'low' => 'Baja',
            'normal' => 'Normal',
            'high' => 'Alta',
            'urgent' => 'Urgente',
        ];

        return view('livewire.worker-orders', [
            'orders' => $orders,
            'workers' => $workers,
            'statuses' => $statuses,
            'priorities' => $priorities,
        ]);
    }
}