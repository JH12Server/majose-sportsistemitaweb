<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class Carrito extends Component
{
    public $items = [];

    public function mount()
    {
        $this->items = session()->get('carrito', []);
    }

    public function eliminar($id)
    {
        $this->items = array_filter($this->items, fn($item) => $item['id'] != $id);
        session(['carrito' => $this->items]);
    }

    public function actualizarCantidad($id, $cantidad)
    {
        foreach ($this->items as &$item) {
            if ($item['id'] == $id) {
                $item['cantidad'] = max(1, (int)$cantidad);
            }
        }
        session(['carrito' => $this->items]);
    }

    public function checkout()
    {
        if (empty($this->items)) {
            session()->flash('status', 'El carrito está vacío.');
            return;
        }
        return redirect()->route('checkout');
    }

    public function render()
    {
        $total = collect($this->items)->sum(fn($item) => $item['precio'] * $item['cantidad']);
        return view('livewire.carrito', ['items' => $this->items, 'total' => $total]);
    }
} 