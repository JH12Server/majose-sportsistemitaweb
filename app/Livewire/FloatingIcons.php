<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class FloatingIcons extends Component
{
    public $showCart = false;
    public $showNotifications = false;
    public $showProfile = false;
    public $cartItems = [];
    public $notifications = [];
    public $unreadNotifications = 0;

    protected $listeners = [
        'cart-updated' => 'loadCartItems',
        'addToCart' => 'addToCart',
    ];

    public function mount()
    {
        $this->loadCartItems();
        $this->loadNotifications();
    }

    public function loadCartItems()
    {
        $this->cartItems = Session::get('cart', []);
    }

    public function loadNotifications()
    {
        // Simular notificaciones - en producción vendrían de la base de datos
        $this->notifications = [
            [
                'id' => 1,
                'title' => 'Pedido #12345 actualizado',
                'message' => 'Tu pedido ha pasado a estado "En Producción"',
                'time' => '2 horas',
                'read' => false,
                'type' => 'order_update'
            ],
            [
                'id' => 2,
                'title' => 'Nuevo producto disponible',
                'message' => 'Hemos agregado nuevos diseños de camisetas',
                'time' => '1 día',
                'read' => true,
                'type' => 'product'
            ],
            [
                'id' => 3,
                'title' => 'Pedido #12340 entregado',
                'message' => 'Tu pedido ha sido entregado exitosamente',
                'time' => '3 días',
                'read' => true,
                'type' => 'delivery'
            ]
        ];

        $this->unreadNotifications = collect($this->notifications)->where('read', false)->count();
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
        $this->showNotifications = false;
        $this->showProfile = false;
    }

    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
        $this->showCart = false;
        $this->showProfile = false;
    }

    public function toggleProfile()
    {
        $this->showProfile = !$this->showProfile;
        $this->showCart = false;
        $this->showNotifications = false;
    }

    public function closeAll()
    {
        $this->showCart = false;
        $this->showNotifications = false;
        $this->showProfile = false;
    }

    public function markNotificationAsRead($notificationId)
    {
        $notification = collect($this->notifications)->firstWhere('id', $notificationId);
        if ($notification) {
            $notification['read'] = true;
            $this->unreadNotifications = collect($this->notifications)->where('read', false)->count();
        }
    }

    public function removeFromCart($cartKey)
    {
        $cart = Session::get('cart', []);
        unset($cart[$cartKey]);
        Session::put('cart', $cart);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }

    public function addToCart($productId, $customization = [])
    {
        try {
            $product = Product::find($productId);
            if (!$product) {
                $this->dispatch('show-error', 'Producto no encontrado');
                return;
            }

            if (property_exists($product, 'is_active') && !$product->is_active) {
                $this->dispatch('show-error', 'Este producto no está disponible actualmente');
                return;
            }

            if (!is_array($customization)) {
                $customization = [];
            }
            $this->addProductToCart($product, $customization);
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al agregar el producto al carrito');
            \Log::error('FloatingIcons addToCart error: ' . $e->getMessage());
        }
    }

    private function addProductToCart($product, $customization = [])
    {
        $cart = Session::get('cart', []);
        $cartKey = $this->generateCartKey($product->id, $customization);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += 1;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'product' => $product,
                'quantity' => 1,
                'unit_price' => $product->base_price,
                'customization' => $customization,
            ];
        }

        Session::put('cart', $cart);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
        $this->dispatch('show-success', 'Producto agregado al carrito');
    }

    private function generateCartKey($productId, $customization)
    {
        return $productId . '_' . md5(serialize($customization));
    }

    public function updateQuantity($cartKey, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartKey);
            return;
        }

        $cart = Session::get('cart', []);
        $cart[$cartKey]['quantity'] = $quantity;
        Session::put('cart', $cart);
        $this->loadCartItems();
        $this->dispatch('cart-updated');
    }

    public function getTotalItemsProperty()
    {
        if (!is_array($this->cartItems)) {
            return 0;
        }
        return array_sum(array_map(function($item){
            return is_array($item) && isset($item['quantity']) ? (int)$item['quantity'] : 0;
        }, $this->cartItems));
    }

    public function getTotalPriceProperty()
    {
        if (!is_array($this->cartItems)) {
            return 0;
        }
        $total = 0;
        foreach ($this->cartItems as $item) {
            $qty = is_array($item) && isset($item['quantity']) ? (int)$item['quantity'] : 0;
            $price = is_array($item) && isset($item['unit_price']) ? (float)$item['unit_price'] : 0;
            $total += $qty * $price;
        }
        return $total;
    }

    public function getFormattedTotalProperty()
    {
        return '$' . number_format($this->totalPrice, 2);
    }

    public function render()
    {
        return view('livewire.floating-icons');
    }
}
