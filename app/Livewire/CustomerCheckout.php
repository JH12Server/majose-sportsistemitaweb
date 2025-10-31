<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCheckout extends Component
{
    public $cart = [];
    public $billingInfo = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
        'country' => 'Colombia',
    ];
    
    public $shippingInfo = [
        'first_name' => '',
        'last_name' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
        'country' => 'Colombia',
    ];
    
    public $paymentMethod = 'card';
    public $sameAsBilling = true;
    public $termsAccepted = false;
    public $isProcessing = false;
    public $stripePublicKey;

    protected $rules = [
        'billingInfo.first_name' => 'required|string|max:255',
        'billingInfo.last_name' => 'required|string|max:255',
        'billingInfo.email' => 'required|email|max:255',
        'billingInfo.phone' => 'required|string|max:20',
        'billingInfo.address' => 'required|string|max:500',
        'billingInfo.city' => 'required|string|max:255',
        'billingInfo.state' => 'required|string|max:255',
        'billingInfo.postal_code' => 'required|string|max:20',
        'billingInfo.country' => 'required|string|max:255',
        'shippingInfo.first_name' => 'required|string|max:255',
        'shippingInfo.last_name' => 'required|string|max:255',
        'shippingInfo.address' => 'required|string|max:500',
        'shippingInfo.city' => 'required|string|max:255',
        'shippingInfo.state' => 'required|string|max:255',
        'shippingInfo.postal_code' => 'required|string|max:20',
        'shippingInfo.country' => 'required|string|max:255',
        'paymentMethod' => 'required|string|in:card,paypal',
        'termsAccepted' => 'required|accepted',
    ];

    protected $messages = [
        'billingInfo.first_name.required' => 'El nombre es obligatorio.',
        'billingInfo.last_name.required' => 'El apellido es obligatorio.',
        'billingInfo.email.required' => 'El email es obligatorio.',
        'billingInfo.email.email' => 'El email debe ser válido.',
        'billingInfo.phone.required' => 'El teléfono es obligatorio.',
        'billingInfo.address.required' => 'La dirección es obligatoria.',
        'billingInfo.city.required' => 'La ciudad es obligatoria.',
        'billingInfo.state.required' => 'El departamento es obligatorio.',
        'billingInfo.postal_code.required' => 'El código postal es obligatorio.',
        'shippingInfo.first_name.required' => 'El nombre de envío es obligatorio.',
        'shippingInfo.last_name.required' => 'El apellido de envío es obligatorio.',
        'shippingInfo.address.required' => 'La dirección de envío es obligatoria.',
        'shippingInfo.city.required' => 'La ciudad de envío es obligatoria.',
        'shippingInfo.state.required' => 'El departamento de envío es obligatorio.',
        'shippingInfo.postal_code.required' => 'El código postal de envío es obligatorio.',
        'termsAccepted.accepted' => 'Debes aceptar los términos y condiciones.',
    ];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        
        if (empty($this->cart)) {
            return redirect()->route('customer.cart');
        }

        // Cargar información del usuario si está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $this->billingInfo['first_name'] = $user->name;
            $this->billingInfo['email'] = $user->email;
        }

        // Configurar Stripe (en producción usar variables de entorno)
        $this->stripePublicKey = config('services.stripe.key', 'pk_test_...');
    }

    public function updatedSameAsBilling()
    {
        if ($this->sameAsBilling) {
            $this->shippingInfo = $this->billingInfo;
        }
    }

    public function updatedBillingInfo()
    {
        if ($this->sameAsBilling) {
            $this->shippingInfo = $this->billingInfo;
        }
    }

    public function processPayment()
    {
        try {
            $this->validate();

            if (empty($this->cart)) {
                $this->dispatch('show-error', 'El carrito está vacío');
                return;
            }

            // Validar que todos los productos estén disponibles
            foreach ($this->cart as $cartKey => $item) {
                $product = Product::find($item['product_id']);
                if (!$product || !$product->is_active) {
                    $this->dispatch('show-error', "El producto '{$item['product']->name}' ya no está disponible");
                    return;
                }
            }

            $this->isProcessing = true;

            DB::beginTransaction();

            // Crear el pedido
            $order = $this->createOrder();

            // Crear los items del pedido
            $this->createOrderItems($order);

            // Procesar el pago (simulado)
            $this->processStripePayment($order);

            // Limpiar el carrito
            Session::forget('cart');
            $this->cart = [];

            DB::commit();

            $this->dispatch('show-success', '¡Pago procesado exitosamente!');

            // Redirigir a la página de confirmación
            return redirect()->route('customer.order-confirmation', $order->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isProcessing = false;
            $this->dispatch('show-error', 'Por favor, corrige los errores en el formulario');
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->isProcessing = false;
            $this->dispatch('show-error', 'Error al procesar el pago. Por favor, intenta nuevamente.');
            
            // Log del error para debugging
            \Log::error('Error en checkout: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'cart' => $this->cart,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function createOrder()
    {
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        
        return Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_amount' => $this->getTotalPrice(),
            'billing_first_name' => $this->billingInfo['first_name'],
            'billing_last_name' => $this->billingInfo['last_name'],
            'billing_email' => $this->billingInfo['email'],
            'billing_phone' => $this->billingInfo['phone'],
            'billing_address' => $this->billingInfo['address'],
            'billing_city' => $this->billingInfo['city'],
            'billing_state' => $this->billingInfo['state'],
            'billing_postal_code' => $this->billingInfo['postal_code'],
            'billing_country' => $this->billingInfo['country'],
            'shipping_first_name' => $this->shippingInfo['first_name'],
            'shipping_last_name' => $this->shippingInfo['last_name'],
            'shipping_address' => $this->shippingInfo['address'],
            'shipping_city' => $this->shippingInfo['city'],
            'shipping_state' => $this->shippingInfo['state'],
            'shipping_postal_code' => $this->shippingInfo['postal_code'],
            'shipping_country' => $this->shippingInfo['country'],
            'payment_method' => $this->paymentMethod,
            'estimated_delivery' => now()->addDays(7),
        ]);
    }

    private function createOrderItems($order)
    {
        foreach ($this->cart as $cartKey => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'size' => $item['customization']['size'] ?? null,
                'color' => $item['customization']['color'] ?? null,
                'customization_text' => $item['customization']['text'] ?? null,
                'font' => $item['customization']['font'] ?? null,
                'text_color' => $item['customization']['text_color'] ?? null,
                'additional_specifications' => $item['customization']['additional_specifications'] ?? null,
            ]);
        }
    }

    private function processStripePayment($order)
    {
        // Aquí se integraría con Stripe
        // Por ahora simulamos un pago exitoso
        $order->update([
            'status' => 'review',
            'payment_status' => 'paid',
            'payment_id' => 'stripe_' . Str::random(20),
        ]);
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
        return view('livewire.customer-checkout');
    }
}
