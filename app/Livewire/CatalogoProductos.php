<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;

class CatalogoProductos extends Component
{
    public $search = '';
    public $categoria = '';

    public function agregarAlCarrito($productoId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $producto = Servicio::findOrFail($productoId);
        $carrito = session()->get('carrito', []);
        $existe = false;
        foreach ($carrito as &$item) {
            if ($item['id'] == $producto->id) {
                $item['cantidad']++;
                $existe = true;
                break;
            }
        }
        if (!$existe) {
            $carrito[] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => 1,
            ];
        }
        session(['carrito' => $carrito]);
        session()->flash('status', 'Producto agregado al carrito.');
        $this->dispatch('carritoActualizado');
    }

    public function render()
    {
        $productos = Servicio::where('estado', 1)
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->when($this->categoria, fn($q) => $q->where('categoria', $this->categoria))
            ->paginate(12);
        $categorias = Servicio::distinct()->pluck('categoria');
        return view('livewire.catalogo-productos', compact('productos', 'categorias'));
    }
} 