<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-2xl font-bold text-gray-900">Mi Carrito</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        {{ $this->totalItems }} {{ $this->totalItems === 1 ? 'producto' : 'productos' }}
                    </span>
                    @if(count($cart) > 0)
                        <button wire:click="clearCart" class="text-sm text-red-600 hover:text-red-800">
                            Vaciar carrito
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de productos -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Productos en tu carrito</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cart as $cartKey => $item)
                                <div class="p-6 hover:bg-gray-50 transition-all">
                                    <div class="flex items-start space-x-5">
                                        <!-- Imagen del producto (del catálogo) -->
                                        <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                            @if($item['product']->main_image ?? false)
                                                <img src="{{ $item['product']->main_image_url ?? asset('images/placeholder.jpg') }}" 
                                                     alt="{{ $item['product']->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Información + personalización -->
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $item['product']->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $item['product']->category ?? 'Ropa' }}</p>

                                            <!-- Opciones de personalización -->
                                            @if(!empty($item['customization']))
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @if($item['customization']['size'] ?? false)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Talla: {{ $item['customization']['size'] }}
                                                        </span>
                                                    @endif
                                                    @if($item['customization']['color'] ?? false)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Color: {{ $item['customization']['color'] }}
                                                        </span>
                                                    @endif
                                                    @if($item['customization']['text'] ?? false)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            Texto: {{ $item['customization']['text'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- IMAGEN DE REFERENCIA - SIMPLE Y FUNCIONAL -->
                                            @php
                                                $savedImageUrl = null;
                                                $savedImageData = null; // base64 fallback
                                                if (!empty($item['customization']['image'])) {
                                                    $savedImageUrl = $item['customization']['image'];
                                                } elseif (!empty($item['reference_file'])) {
                                                    $savedImageUrl = \Illuminate\Support\Facades\Storage::url($item['reference_file']);
                                                } elseif (!empty($item['customization']['file'])) {
                                                    $p = $item['customization']['file'];
                                                    if (str_starts_with($p, 'customizations/')) {
                                                        $savedImageUrl = \Illuminate\Support\Facades\Storage::url($p);
                                                    } else {
                                                        $savedImageUrl = \Illuminate\Support\Facades\Storage::url('customizations/' . $p);
                                                    }
                                                }

                                                // Fallback: if we have a reference file path, try to embed it as data URI
                                                try {
                                                    $relative = $item['reference_file'] ?? ($item['customization']['file'] ?? null);
                                                    if ($relative) {
                                                        $full = storage_path('app/public/' . ltrim($relative, '/'));
                                                        if (file_exists($full)) {
                                                            $type = mime_content_type($full) ?: 'image/png';
                                                            $data = base64_encode(file_get_contents($full));
                                                            $savedImageData = 'data:' . $type . ';base64,' . $data;
                                                        }
                                                    }
                                                } catch (\Throwable $e) {
                                                    // ignore errors building data URI
                                                    $savedImageData = null;
                                                }
                                            @endphp

                                            <div class="mt-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen de referencia</label>
                                                
                                                @if($savedImageUrl || $savedImageData)
                                                    <!-- MOSTRAR IMAGEN GUARDADA (usar Storage::url para mayor compatibilidad) -->
                                                    <div class="text-center mb-3">
                                                        <img src="{{ $savedImageData ?? $savedImageUrl }}" class="mx-auto h-32 rounded border" alt="Imagen guardada">
                                                        <p class="text-xs text-green-600 mt-2">✓ Imagen guardada</p>
                                                    </div>
                                                @endif

                                                <!-- INPUT - ACTIVAR AL HACER CLIC -->
                                                <input 
                                                    id="ref-file-{{ $cartKey }}" 
                                                    type="file" 
                                                    accept="image/*" 
                                                    wire:model="customizationFileForCart"
                                                    wire:click="selectItemForImageUpload('{{ $cartKey }}')"
                                                    class="w-full border rounded-md px-3 py-2"
                                                >
                                                @error('customizationFileForCart') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                                
                                                <!-- PREVIEW Y BOTÓN GUARDAR -->
                                                @if($currentEditingCartKey === $cartKey && $customizationFileForCart)
                                                    <div class="mt-3 text-center">
                                                        <img src="{{ $customizationFileForCart->temporaryUrl() }}" class="mx-auto h-32 rounded border" alt="Preview">
                                                        <button wire:click="saveCartReferenceImage('{{ $cartKey }}')" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 text-sm">
                                                            Guardar Imagen
                                                        </button>
                                                    </div>
                                                @elseif(!$savedImageUrl && $currentEditingCartKey !== $cartKey)
                                                    <p class="text-xs text-gray-600 mt-2">Selecciona una imagen</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Cantidad, precio y eliminar -->
                                        <div class="flex flex-col items-end space-y-4">
                                            <div class="flex items-center border-2 border-gray-300 rounded-xl">
                                                <button wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] - 1 }})"
                                                        class="p-3 hover:bg-gray-100 transition rounded-l-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                                </button>
                                                <span class="px-5 py-2 font-bold text-lg">{{ $item['quantity'] }}</span>
                                                <button wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] + 1 }})"
                                                        class="p-3 hover:bg-gray-100 transition rounded-r-xl">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                </button>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-gray-900">
                                                    ${{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ${{ number_format($item['unit_price'], 2) }} c/u
                                                </div>
                                            </div>

                                            <button wire:click="removeFromCart('{{ $cartKey }}')"
                                                    class="p-3 text-red-600 hover:bg-red-50 rounded-xl transition">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Resumen del pedido</h2>
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-lg">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-bold">{{ $this->formattedTotal }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Envío</span>
                                <span class="text-green-600 font-bold">Gratis</span>
                            </div>
                            <div class="border-t-2 border-gray-300 pt-5">
                                <div class="flex justify-between text-2xl font-bold">
                                    <span>Total</span>
                                    <span class="text-blue-600">{{ $this->formattedTotal }}</span>
                                </div>
                            </div>
                        </div>

                        <button wire:click="proceedToCheckout"
                                class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition shadow-lg transform hover:scale-105">
                            Proceder al Checkout
                        </button>

                        <div class="mt-6 text-center">
                            <a href="{{ route('customer.catalog') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                ← Continuar comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Carrito vacío -->
            <div class="text-center py-20">
                <svg class="mx-auto h-32 w-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7.48 13h9.04l3.48-8H5.5m0 0L7.48 13m0 0l-2.5 5m2.5-5l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
                <h3 class="mt-8 text-2xl font-bold text-gray-900">Tu carrito está vacío</h3>
                <p class="mt-3 text-gray-600">¡Empieza a personalizar tus camisetas ahora!</p>
                <a href="{{ route('customer.catalog') }}" class="mt-8 inline-flex items-center px-8 py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg">
                    Explorar catálogo
                </a>
            </div>
        @endif
    </div>

    <!-- Modal para ampliar imagen -->
    <script>
        function openPreviewModal(src) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-[9999] flex items-center justify-center p-4 cursor-pointer';
            modal.innerHTML = `
                <div class="relative max-w-4xl w-full">
                    <img src="${src}" class="w-full max-h-screen object-contain rounded-2xl shadow-2xl">
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="absolute top-4 right-4 bg-white/90 hover:bg-white rounded-full p-4 shadow-2xl transition-all hover:scale-110">
                        <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            modal.addEventListener('click', (e) => e.target === modal && modal.remove());
        }
    </script>
</div>