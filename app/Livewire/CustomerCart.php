<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CustomerCart extends Component
{
    public $cart = [];
    public $showCustomizationModal = false;
    public $selectedProduct = null;
    public $customization = [
        'size' => '',
        'color' => '',
        'text' => '',
        'font' => '',
        'text_color' => '',
        'design' => '',
        'additional_specifications' => '',
    ];

    protected $listeners = ['addToCart' => 'addToCart'];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            $this->dispatch('show-error', 'Producto no encontrado');
            return;
        }

        if ($product->allows_customization) {
            $this->selectedProduct = $product;
            $this->resetCustomization();
            $this->showCustomizationModal = true;
        } else {
            $this->addProductToCart($product);
        }
    }

    public function addProductToCart($product, $customization = [])
    {
        $cartKey = $this->generateCartKey($product->id, $customization);
        
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity'] += 1;
        } else {
            $this->cart[$cartKey] = [
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => 1,
                'unit_price' => $product->base_price,
                'customization' => $customization,
            ];
        }

        Session::put('cart', $this->cart);
        $this->dispatch('cart-updated');
        $this->dispatch('show-success', 'Producto agregado al carrito');
    }

    public function confirmCustomization()
    {
        if (!$this->selectedProduct) {
            return;
        }

        $customization = [
            'size' => $this->customization['size'],
            'color' => $this->customization['color'],
            'text' => $this->customization['text'],
            'font' => $this->customization['font'],
            'text_color' => $this->customization['text_color'],
            'design' => $this->customization['design'],
            'additional_specifications' => $this->customization['additional_specifications'],
        ];

        $this->addProductToCart($this->selectedProduct, $customization);
        $this->closeCustomizationModal();
    }

    public function closeCustomizationModal()
    {
        $this->showCustomizationModal = false;
        $this->selectedProduct = null;
        $this->resetCustomization();
    }

    public function resetCustomization()
    {
        $this->customization = [
            'size' => '',
            'color' => '',
            'text' => '',
            'font' => '',
            'text_color' => '',
            'design' => '',
            'additional_specifications' => '',
        ];
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartKey);
            return;
        }

        $this->cart[$cartKey]['quantity'] = $quantity;
        Session::put('cart', $this->cart);
        $this->dispatch('cart-updated');
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        Session::put('cart', $this->cart);
        $this->dispatch('cart-updated');
        $this->dispatch('show-success', 'Producto eliminado del carrito');
    }

    public function clearCart()
    {
        $this->cart = [];
        Session::forget('cart');
        $this->dispatch('cart-updated');
        $this->dispatch('show-success', 'Carrito vaciado');
    }

    private function generateCartKey($productId, $customization)
    {
        return $productId . '_' . md5(serialize($customization));
    }

    public function getTotalItemsProperty()
    {
        return array_sum(array_column($this->cart, 'quantity'));
    }

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }
        return $total;
    }

    public function getFormattedTotalProperty()
    {
        return '$' . number_format($this->totalPrice, 2);
    }

    public function render()
    {
        return view('livewire.customer-cart');
    }
}