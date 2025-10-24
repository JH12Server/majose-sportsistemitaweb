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
                        <button 
                            wire:click="clearCart" 
                            class="text-sm text-red-600 hover:text-red-800"
                        >
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
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Productos en tu carrito</h2>
                            
                            <div class="space-y-4">
                                @foreach($cart as $cartKey => $item)
                                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                        <!-- Imagen del producto -->
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($item['product']->main_image)
                                                <img 
                                                    src="{{ asset('storage/' . $item['product']->main_image) }}" 
                                                    alt="{{ $item['product']->name }}"
                                                    class="w-full h-full object-cover"
                                                >
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Información del producto -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-gray-900 truncate">
                                                {{ $item['product']->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $item['product']->category }}
                                            </p>
                                            
                                            <!-- Personalización -->
                                            @if(!empty($item['customization']))
                                                <div class="mt-2 space-y-1">
                                                    @if($item['customization']['size'])
                                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                            Talla: {{ $item['customization']['size'] }}
                                                        </span>
                                                    @endif
                                                    @if($item['customization']['color'])
                                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                                            Color: {{ $item['customization']['color'] }}
                                                        </span>
                                                    @endif
                                                    @if($item['customization']['text'])
                                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                                                            Texto: {{ $item['customization']['text'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Cantidad y precio -->
                                        <div class="flex items-center space-x-4">
                                            <!-- Selector de cantidad -->
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button 
                                                    wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] - 1 }})"
                                                    class="p-2 hover:bg-gray-100 transition-colors"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="px-3 py-2 text-sm font-medium">{{ $item['quantity'] }}</span>
                                                <button 
                                                    wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] + 1 }})"
                                                    class="p-2 hover:bg-gray-100 transition-colors"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Precio total del item -->
                                            <div class="text-right">
                                                <div class="font-semibold text-gray-900">
                                                    ${{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ${{ number_format($item['unit_price'], 2) }} c/u
                                                </div>
                                            </div>

                                            <!-- Botón eliminar -->
                                            <button 
                                                wire:click="removeFromCart('{{ $cartKey }}')"
                                                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $this->totalItems }} productos)</span>
                                <span class="font-medium">{{ $this->formattedTotal }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-medium text-green-600">Gratis</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total</span>
                                    <span>{{ $this->formattedTotal }}</span>
                                </div>
                            </div>
                        </div>

                        <button 
                            wire:click="proceedToCheckout"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-lg"
                        >
                            Proceder al Checkout
                        </button>

                        <div class="mt-4 text-center">
                            <a href="{{ route('customer.catalog') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                ← Continuar comprando
                            </a>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Envío seguro y garantizado
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mt-2">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Pago 100% seguro
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Carrito vacío -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tu carrito está vacío</h3>
                <p class="mt-2 text-gray-500">Agrega algunos productos para comenzar tu pedido.</p>
                <div class="mt-6">
                    <a 
                        href="{{ route('customer.catalog') }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Explorar productos
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de personalización -->
    @if($showCustomizationModal && $selectedProduct)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Personalizar: {{ $selectedProduct->name }}
                        </h3>
                        <button wire:click="closeCustomizationModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Talla -->
                        @if($selectedProduct->available_sizes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Talla</label>
                                <select wire:model="customization.size" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Seleccionar talla</option>
                                    @foreach($selectedProduct->available_sizes as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Color -->
                        @if($selectedProduct->available_colors)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                <select wire:model="customization.color" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Seleccionar color</option>
                                    @foreach($selectedProduct->available_colors as $color)
                                        <option value="{{ $color }}">{{ $color }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Texto personalizado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Texto para bordar</label>
                            <input 
                                wire:model="customization.text" 
                                type="text" 
                                placeholder="Ingresa el texto que deseas bordar"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Fuente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de fuente</label>
                            <select wire:model="customization.font" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar fuente</option>
                                <option value="arial">Arial</option>
                                <option value="times">Times New Roman</option>
                                <option value="helvetica">Helvetica</option>
                                <option value="courier">Courier</option>
                            </select>
                        </div>

                        <!-- Color del texto -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color del texto</label>
                            <select wire:model="customization.text_color" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar color</option>
                                <option value="negro">Negro</option>
                                <option value="blanco">Blanco</option>
                                <option value="rojo">Rojo</option>
                                <option value="azul">Azul</option>
                                <option value="verde">Verde</option>
                                <option value="amarillo">Amarillo</option>
                            </select>
                        </div>

                        <!-- Especificaciones adicionales -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Especificaciones adicionales</label>
                            <textarea 
                                wire:model="customization.additional_specifications" 
                                rows="3"
                                placeholder="Cualquier detalle adicional que quieras especificar..."
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button 
                            wire:click="closeCustomizationModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            wire:click="confirmCustomization"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>