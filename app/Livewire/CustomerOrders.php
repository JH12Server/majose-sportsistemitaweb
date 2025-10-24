<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CustomerOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
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

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'review'])
            ->first();

        if (!$order) {
            $this->dispatch('show-error', 'No se puede cancelar este pedido');
            return;
        }

        $order->update(['status' => 'cancelled']);
        $this->dispatch('show-success', 'Pedido cancelado correctamente');
    }

    public function render()
    {
        $query = Order::with(['items.product', 'files'])
            ->where('user_id', Auth::id());

        // Aplicar filtros de búsqueda
        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        $orders = $query->paginate(10);

        $statuses = [
            'pending' => 'Pendiente',
            'review' => 'En Revisión',
            'production' => 'En Producción',
            'ready' => 'Listo para Entrega',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
        ];

        return view('livewire.customer-orders', [
            'orders' => $orders,
            'statuses' => $statuses,
        ]);
    }
}