<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CustomerCart extends Component
{
    use WithFileUploads;

    public $cart = [];
    public $showCustomizationModal = false;
    public $selectedProduct = null;
    public $currentEditingCartKey = null; // Cual item estamos editando
    public $customizationFileForCart = null; // Archivo del item actual
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

    protected $rules = [
        'customizationFileForCart' => 'nullable|image|max:4096',
    ];

    public function mount()
    {
        $this->cart = Session::get('cart', []);
        // DEBUG: log current cart contents to investigate missing image URL
        try {
            \Log::debug('CustomerCart mounted. Session cart contents:', ['cart' => $this->cart]);
        } catch (\Throwable $e) {
            // ignore logging errors
        }
    }

    public function addToCart($productId)
    {
        try {
            $product = Product::find($productId);
            
            if (!$product) {
                $this->dispatch('show-error', 'Producto no encontrado');
                return;
            }

            if (!$product->is_active) {
                $this->dispatch('show-error', 'Este producto no está disponible actualmente');
                return;
            }

            if ($product->allows_customization) {
                $this->selectedProduct = $product;
                $this->resetCustomization();
                $this->showCustomizationModal = true;
            } else {
                $this->addProductToCart($product);
            }
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al agregar el producto al carrito');
            Log::error('Error adding to cart: ' . $e->getMessage());
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
        try {
            if (!isset($this->cart[$cartKey])) {
                $this->dispatch('show-error', 'Producto no encontrado en el carrito');
                return;
            }

            if ($quantity <= 0) {
                $this->removeFromCart($cartKey);
                return;
            }

            // Validar cantidad máxima (ejemplo: máximo 10 por producto)
            if ($quantity > 10) {
                $this->dispatch('show-error', 'La cantidad máxima por producto es 10');
                return;
            }

            $this->cart[$cartKey]['quantity'] = $quantity;
            Session::put('cart', $this->cart);
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al actualizar la cantidad');
            Log::error('Error updating quantity: ' . $e->getMessage());
        }
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        Session::put('cart', $this->cart);
        $this->dispatch('cart-updated');
        $this->dispatch('show-success', 'Producto eliminado del carrito');
    }

    public function selectItemForImageUpload($cartKey)
    {
        $this->currentEditingCartKey = $cartKey;
    }

    public function saveCartReferenceImage($cartKey)
    {
        // Validar que el archivo esté presente
        if (!$this->customizationFileForCart) {
            $this->dispatch('show-error', 'Por favor selecciona una imagen');
            return;
        }

        try {
            // Validar que el producto existe en el carrito
            if (!isset($this->cart[$cartKey])) {
                $this->dispatch('show-error', 'Producto no encontrado en el carrito');
                return;
            }

            // Validar el archivo
            $file = $this->customizationFileForCart;
            if (!$file->isValid()) {
                $this->dispatch('show-error', 'Archivo inválido');
                return;
            }

            // Asegurar que el directorio customization existe en el item
            if (!isset($this->cart[$cartKey]['customization']) || !is_array($this->cart[$cartKey]['customization'])) {
                $this->cart[$cartKey]['customization'] = [];
            }

            // Asegurar que el directorio de almacenamiento existe
            $storageDir = storage_path('app/public/customizations');
            if (!file_exists($storageDir)) {
                @mkdir($storageDir, 0755, true);
            }

            // Guardar archivo usando copy + unlink (funciona mejor en Windows que move)
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $tempPath = $file->getRealPath();
            $destPath = $storageDir . DIRECTORY_SEPARATOR . $fileName;
            
            if (!copy($tempPath, $destPath)) {
                throw new \Exception('No se pudo copiar el archivo al destino');
            }
            
            // Limpiar el archivo temporal
            @unlink($tempPath);
            
            $path = 'customizations/' . $fileName;
            $fullPath = storage_path('app/public/' . $path);

            if (!file_exists($fullPath)) {
                throw new \Exception('El archivo no se guardó correctamente después de la copia');
            }

            // Para compatibilidad en entornos Windows donde el enlace simbólico
            // `public/storage` puede no apuntar a `storage/app/public`, también
            // copiamos una versión directamente en `public/storage/customizations`
            // de forma que las URLs tipo `/storage/customizations/...` funcionen.
            try {
                $publicDir = public_path('storage/customizations');
                if (!file_exists($publicDir)) {
                    @mkdir($publicDir, 0755, true);
                }
                @copy($fullPath, $publicDir . DIRECTORY_SEPARATOR . $fileName);
            } catch (\Throwable $e) {
                // no crítico; si falla, la imagen seguirá existiendo en storage/app/public
                Log::warning('No se pudo sincronizar la imagen al public/storage: ' . $e->getMessage());
            }

            // Actualizar el carrito
            $this->cart[$cartKey]['reference_file'] = $path;
            if (!isset($this->cart[$cartKey]['customization'])) {
                $this->cart[$cartKey]['customization'] = [];
            }
            $this->cart[$cartKey]['customization']['image'] = asset('storage/' . $path);

            // Guardar en sesión
            Session::put('cart', $this->cart);

            // Limpiar el archivo temporal y la edición actual
            $this->customizationFileForCart = null;
            $this->currentEditingCartKey = null;

            // Recargar desde sesión
            $this->cart = Session::get('cart', []);

            $this->dispatch('cart-updated');
            $this->dispatch('show-success', 'Imagen guardada correctamente');

            Log::info('Reference image saved in cart', ['cartKey' => $cartKey, 'fileName' => $fileName]);

        } catch (\Exception $e) {
            $this->dispatch('show-error', 'Error al guardar: ' . $e->getMessage());
            Log::error('Error saving cart reference image: ' . $e->getMessage());
        }
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

    public function proceedToCheckout()
    {
        try {
            if (empty($this->cart)) {
                $this->dispatch('show-error', 'El carrito está vacío');
                return;
            }

            // Verificar disponibilidad de productos antes de proceder
            foreach ($this->cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product || !$product->is_active) {
                    $this->dispatch('show-error', "El producto '{$item['product']->name}' ya no está disponible");
                    return;
                }
            }

            // Redirigir al flujo de checkout
            return redirect()->route('customer.checkout');
        } catch (\Exception $e) {
            Log::error('Error al proceder al checkout: ' . $e->getMessage());
            $this->dispatch('show-error', 'Error al proceder al checkout. Por favor intenta nuevamente.');
        }
    }

    public function render()
    {
        return view('livewire.customer-cart');
    }
}