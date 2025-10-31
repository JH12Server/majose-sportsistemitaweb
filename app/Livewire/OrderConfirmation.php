<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrderConfirmation extends Component
{
    public $order;
    public $orderId;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->order = Order::with(['items.product'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$this->order) {
            abort(404, 'Pedido no encontrado');
        }
    }

    public function render()
    {
        return view('livewire.order-confirmation');
    }
}
