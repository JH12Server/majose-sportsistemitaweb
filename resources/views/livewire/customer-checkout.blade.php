<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                    <span class="text-sm text-gray-500">{{ $this->totalItems }} productos</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.cart') }}" class="text-blue-600 hover:text-blue-800">
                        ← Volver al carrito
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form wire:submit.prevent="processPayment">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulario principal -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Información de facturación -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Información de Facturación</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input 
                                    wire:model="billingInfo.first_name" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.first_name') border-red-500 @enderror"
                                >
                                @error('billingInfo.first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                                <input 
                                    wire:model="billingInfo.last_name" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.last_name') border-red-500 @enderror"
                                >
                                @error('billingInfo.last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input 
                                    wire:model="billingInfo.email" 
                                    type="email" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.email') border-red-500 @enderror"
                                >
                                @error('billingInfo.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                <input 
                                    wire:model="billingInfo.phone" 
                                    type="tel" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.phone') border-red-500 @enderror"
                                >
                                @error('billingInfo.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <input 
                                    wire:model="billingInfo.address" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.address') border-red-500 @enderror"
                                >
                                @error('billingInfo.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                                <input 
                                    wire:model="billingInfo.city" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.city') border-red-500 @enderror"
                                >
                                @error('billingInfo.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                                <select 
                                    wire:model="billingInfo.state" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.state') border-red-500 @enderror"
                                >
                                    <option value="">Seleccionar</option>
                                    <option value="Antioquia">Antioquia</option>
                                    <option value="Atlántico">Atlántico</option>
                                    <option value="Bogotá">Bogotá D.C.</option>
                                    <option value="Bolívar">Bolívar</option>
                                    <option value="Boyacá">Boyacá</option>
                                    <option value="Caldas">Caldas</option>
                                    <option value="Caquetá">Caquetá</option>
                                    <option value="Cauca">Cauca</option>
                                    <option value="Cesar">Cesar</option>
                                    <option value="Córdoba">Córdoba</option>
                                    <option value="Cundinamarca">Cundinamarca</option>
                                    <option value="Huila">Huila</option>
                                    <option value="La Guajira">La Guajira</option>
                                    <option value="Magdalena">Magdalena</option>
                                    <option value="Meta">Meta</option>
                                    <option value="Nariño">Nariño</option>
                                    <option value="Norte de Santander">Norte de Santander</option>
                                    <option value="Quindío">Quindío</option>
                                    <option value="Risaralda">Risaralda</option>
                                    <option value="Santander">Santander</option>
                                    <option value="Sucre">Sucre</option>
                                    <option value="Tolima">Tolima</option>
                                    <option value="Valle del Cauca">Valle del Cauca</option>
                                </select>
                                @error('billingInfo.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                                <input 
                                    wire:model="billingInfo.postal_code" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('billingInfo.postal_code') border-red-500 @enderror"
                                >
                                @error('billingInfo.postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de envío -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900">Información de Envío</h2>
                            <label class="flex items-center">
                                <input 
                                    wire:model="sameAsBilling" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <span class="ml-2 text-sm text-gray-700">Igual que la facturación</span>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input 
                                    wire:model="shippingInfo.first_name" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.first_name') border-red-500 @enderror"
                                >
                                @error('shippingInfo.first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                                <input 
                                    wire:model="shippingInfo.last_name" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.last_name') border-red-500 @enderror"
                                >
                                @error('shippingInfo.last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <input 
                                    wire:model="shippingInfo.address" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.address') border-red-500 @enderror"
                                >
                                @error('shippingInfo.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad *</label>
                                <input 
                                    wire:model="shippingInfo.city" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.city') border-red-500 @enderror"
                                >
                                @error('shippingInfo.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                                <select 
                                    wire:model="shippingInfo.state" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.state') border-red-500 @enderror"
                                >
                                    <option value="">Seleccionar</option>
                                    <option value="Antioquia">Antioquia</option>
                                    <option value="Atlántico">Atlántico</option>
                                    <option value="Bogotá">Bogotá D.C.</option>
                                    <option value="Bolívar">Bolívar</option>
                                    <option value="Boyacá">Boyacá</option>
                                    <option value="Caldas">Caldas</option>
                                    <option value="Caquetá">Caquetá</option>
                                    <option value="Cauca">Cauca</option>
                                    <option value="Cesar">Cesar</option>
                                    <option value="Córdoba">Córdoba</option>
                                    <option value="Cundinamarca">Cundinamarca</option>
                                    <option value="Huila">Huila</option>
                                    <option value="La Guajira">La Guajira</option>
                                    <option value="Magdalena">Magdalena</option>
                                    <option value="Meta">Meta</option>
                                    <option value="Nariño">Nariño</option>
                                    <option value="Norte de Santander">Norte de Santander</option>
                                    <option value="Quindío">Quindío</option>
                                    <option value="Risaralda">Risaralda</option>
                                    <option value="Santander">Santander</option>
                                    <option value="Sucre">Sucre</option>
                                    <option value="Tolima">Tolima</option>
                                    <option value="Valle del Cauca">Valle del Cauca</option>
                                </select>
                                @error('shippingInfo.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                                <input 
                                    wire:model="shippingInfo.postal_code" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shippingInfo.postal_code') border-red-500 @enderror"
                                >
                                @error('shippingInfo.postal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Método de pago -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Método de Pago</h2>
                        
                        <div class="space-y-4">
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'card' ? 'border-blue-500 bg-blue-50' : '' }}">
                                <input 
                                    wire:model="paymentMethod" 
                                    type="radio" 
                                    value="card" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                >
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">Tarjeta de Crédito/Débito</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Pago seguro con Stripe</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50' : '' }}">
                                <input 
                                    wire:model="paymentMethod" 
                                    type="radio" 
                                    value="paypal" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                    href{{ url('/paypal/pay') }}
                                >
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.51-.541c-1.01-1.15-2.57-1.81-5.19-1.81H8.458c-.524 0-.968.382-1.05.9L5.288 19.9h4.588c.524 0 .968-.382 1.05-.9l1.12-7.106h2.19c4.298 0 7.664-1.747 8.647-6.797.03-.149.054-.294.077-.437z"/>
                                        </svg>
                                        <span class="font-medium text-gray-900">PayPal</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Pago rápido y seguro</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'cash' ? 'border-blue-500 bg-blue-50' : '' }}">
                                <input 
                                    wire:model="paymentMethod" 
                                    type="radio" 
                                    value="cash" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                >
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">Pago en Efectivo</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Paga al recibir tu pedido</p>
                                </div>
                            </label>
                        </div>

                        @if($paymentMethod === 'card')
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Información de la Tarjeta</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de tarjeta</label>
                                        <input 
                                            type="text" 
                                            placeholder="1234 5678 9012 3456" 
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de vencimiento</label>
                                            <input 
                                                type="text" 
                                                placeholder="MM/AA" 
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                            <input 
                                                type="text" 
                                                placeholder="123" 
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre en la tarjeta</label>
                                        <input 
                                            type="text" 
                                            placeholder="Juan Pérez" 
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        >
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">Pago 100% seguro con encriptación SSL</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Términos y condiciones -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <label class="flex items-start">
                            <input 
                                wire:model="termsAccepted" 
                                type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1 @error('termsAccepted') border-red-500 @enderror"
                            >
                            <span class="ml-3 text-sm text-gray-700">
                                Acepto los <a href="#" class="text-blue-600 hover:text-blue-800">términos y condiciones</a> 
                                y la <a href="#" class="text-blue-600 hover:text-blue-800">política de privacidad</a>
                            </span>
                        </label>
                        @error('termsAccepted') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>
                        
                        <!-- Productos -->
                        <div class="space-y-3 mb-6">
                            @foreach($cart as $cartKey => $item)
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item['product']->main_image)
                                            <img 
                                                src="{{ $item['product']->main_image_url ?? asset('images/placeholder.jpg') }}" 
                                                alt="{{ $item['product']->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $item['product']->name }}</h4>
                                        <p class="text-sm text-gray-500">Cantidad: {{ $item['quantity'] }}</p>
                                        @if(!empty($item['customization']))
                                            <div class="mt-1 space-x-1">
                                                @if($item['customization']['size'])
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                        {{ $item['customization']['size'] }}
                                                    </span>
                                                @endif
                                                @if($item['customization']['color'])
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                                        {{ $item['customization']['color'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        ${{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totales -->
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

                        <!-- Botón de pago -->
                        @if($paymentMethod !== 'paypal')
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-lg disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span wire:loading.remove wire:target="processPayment">
                                    Proceder al Pago
                                </span>
                                <span wire:loading wire:target="processPayment" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        @else
                            <div class="mt-4">
                                @if(empty($paypalClientId))
                                    <div class="p-3 mb-3 rounded border border-yellow-300 bg-yellow-50 text-yellow-800">
                                        PayPal no está configurado. Por favor añade <code>PAYPAL_CLIENT_ID</code> y <code>PAYPAL_SECRET</code> en tu archivo <code>.env</code> (modo sandbox) y recarga la página.
                                    </div>
                                @endif
                                <div id="paypal-buttons"></div>
                            </div>
                            <script>
                                (function(){
                                    const clientId = "{{ $paypalClientId ?? '' }}";
                                    if (!clientId) {
                                        console.warn('PayPal client id not configured');
                                        return;
                                    }
                                    const script = document.createElement('script');
                                    script.src = `https://www.paypal.com/sdk/js?client-id=${clientId}&currency=USD`;
                                    script.onload = () => {
                                        const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

                                        paypal.Buttons({
                                            createOrder: function(data, actions) {
                                                return fetch("{{ route('paypal.create') }}", {
                                                    method: 'post',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': token,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    body: JSON.stringify({})
                                                }).then(function(res){ return res.json(); }).then(function(data){
                                                    return data.id;
                                                });
                                            },
                                            onApprove: function(data, actions) {
                                                return fetch("{{ url('/paypal/capture-order') }}/" + data.orderID, {
                                                    method: 'post',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': token,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    body: JSON.stringify({})
                                                }).then(function(res){ return res.json(); }).then(function(json){
                                                    if (json.redirect) {
                                                        window.location = json.redirect;
                                                    } else {
                                                        alert('Pago completado, pero ocurrió un error procesando el pedido en el servidor.');
                                                    }
                                                });
                                            },
                                            onError: function(err){
                                                console.error('PayPal error', err);
                                                alert('Error con PayPal: ' + (err && err.message ? err.message : '')); 
                                            }
                                        }).render('#paypal-buttons');
                                    };
                                    document.body.appendChild(script);
                                })();
                            </script>
                        @endif

                        <!-- Información de seguridad -->
                        <div class="mt-4 pt-4 border-t">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Pago 100% seguro
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mt-2">
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Envío garantizado
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
