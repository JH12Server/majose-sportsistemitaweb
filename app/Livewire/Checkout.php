<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    public $items = [];
    public $total = 0;

    public function mount()
    {
        $this->items = session()->get('carrito', []);
        $this->total = collect($this->items)->sum(fn($item) => $item['precio'] * $item['cantidad']);
    }

    public function procesarCompra()
    {
        if (empty($this->items)) {
            session()->flash('status', 'El carrito está vacío.');
            return;
        }
        DB::transaction(function () {
            $venta = Sale::create([
                'user_id' => Auth::id(),
                'total' => $this->total,
                'status' => 'completado',
            ]);
            foreach ($this->items as $item) {
                SaleDetail::create([
                    'sale_id' => $venta->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['cantidad'],
                    'price' => $item['precio'],
                ]);
            }
        });
        session()->forget('carrito');
        session()->flash('status', '¡Compra realizada con éxito!');
        return redirect()->route('catalogo');
    }

    public function render()
    {
        return view('livewire.checkout', ['items' => $this->items, 'total' => $this->total]);
    }
} 