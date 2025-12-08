<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Carrito as CarritoModel;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Carrito extends Component
{
    use WithFileUploads;

    public $items = [];
    public $customizationFile;
    public $uploadingForItem = null;

    protected $rules = [
        'customizationFile' => 'nullable|image|max:4096',
    ];

    public function mount()
    {
        $this->loadCart();
    }

    private function loadCart()
    {
        if (Auth::check()) {
            $this->items = CarritoModel::where('user_id', Auth::id())
                ->with('product')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'nombre' => $item->product->name ?? 'Producto',
                        'image' => $item->product->main_image_url ?? asset('images/placeholder.jpg'),
                        'precio' => $item->unit_price,
                        'cantidad' => $item->quantity,
                        'customization' => [
                            'size' => $item->size,
                            'color' => $item->color,
                            'text' => $item->text,
                            'font' => $item->font,
                            'text_color' => $item->text_color,
                            'additional_specifications' => $item->additional_specifications,
                            'image' => $item->reference_image_url,
                        ],
                        'reference_file' => $item->reference_file,
                    ];
                })
                ->toArray();
        } else {
            // Fallback a sesión para usuarios no autenticados
            $this->items = session()->get('carrito', []);
        }
    }

    public function eliminar($id)
    {
        if (Auth::check()) {
            CarritoModel::find($id)->delete();
            $this->loadCart();
            $this->dispatch('show-success', 'Producto eliminado del carrito');
        } else {
            $this->items = array_filter($this->items, fn($item) => $item['id'] != $id);
            session(['carrito' => $this->items]);
        }
    }

    public function actualizarCantidad($id, $cantidad)
    {
        if (Auth::check()) {
            $item = CarritoModel::find($id);
            if ($item) {
                $item->quantity = max(1, (int)$cantidad);
                $item->save();
                $this->loadCart();
            }
        } else {
            foreach ($this->items as &$item) {
                if ($item['id'] == $id) {
                    $item['cantidad'] = max(1, (int)$cantidad);
                }
            }
            session(['carrito' => $this->items]);
        }
    }

    public function saveReferenceImage($itemId)
    {
        $this->validate();
        
        if (!$this->customizationFile) {
            $this->dispatch('show-error', 'Por favor selecciona una imagen');
            return;
        }

        try {
            $item = CarritoModel::find($itemId);
            if (!$item) {
                $this->dispatch('show-error', 'Producto no encontrado');
                return;
            }

            // Eliminar imagen anterior si existe
            if ($item->reference_file) {
                Storage::disk('public')->delete($item->reference_file);
            }

            // Guardar la nueva imagen
            $path = $this->customizationFile->store('customizations', 'public');
            $item->reference_file = $path;
            $item->save();

            $this->customizationFile = null;
            $this->uploadingForItem = null;
            $this->loadCart();

            $this->dispatch('show-success', 'Imagen de referencia guardada');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al guardar la imagen: ' . $e->getMessage());
        }
    }

    public function clearReferenceImage($itemId)
    {
        try {
            $item = CarritoModel::find($itemId);
            if ($item && $item->reference_file) {
                Storage::disk('public')->delete($item->reference_file);
                $item->reference_file = null;
                $item->save();
                $this->loadCart();
                $this->dispatch('show-success', 'Imagen eliminada');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al eliminar la imagen');
        }
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