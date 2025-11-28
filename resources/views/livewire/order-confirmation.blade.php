<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">¡Pedido Confirmado!</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        ← Volver al dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensaje de éxito -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">¡Gracias por tu compra!</h2>
            <p class="text-lg text-gray-600 mb-4">
                Tu pedido ha sido procesado exitosamente y está siendo preparado.
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 inline-block">
                <p class="text-blue-800 font-medium">
                    Número de pedido: <span class="font-bold">{{ $order->order_number }}</span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Detalles del pedido -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles del Pedido</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha del pedido:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Método de pago:</span>
                        <span class="font-medium">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-bold text-lg text-blue-600">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    
                    @if($order->estimated_delivery)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Entrega estimada:</span>
                            <span class="font-medium">{{ $order->estimated_delivery->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de envío -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h3>
                
                <div class="space-y-2">
                    <p class="font-medium text-gray-900">
                        {{ $order->shipping_first_name }} {{ $order->shipping_last_name }}
                    </p>
                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600">
                        {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}
                    </p>
                    <p class="text-gray-600">{{ $order->shipping_country }}</p>
                </div>
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Productos del Pedido</h3>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product->main_image)
                                <img 
                                    src="{{ $item->product->main_image_url ?? asset('images/placeholder.jpg') }}" 
                                    alt="{{ $item->product->name }}"
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
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $item->product->category }}</p>
                            
                            @if($item->size || $item->color || $item->customization_text)
                                <div class="mt-2 space-x-2">
                                    @if($item->size)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                            Talla: {{ $item->size }}
                                        </span>
                                    @endif
                                    @if($item->color)
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                            Color: {{ $item->color }}
                                        </span>
                                    @endif
                                    @if($item->customization_text)
                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">
                                            Texto: {{ $item->customization_text }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            <p class="font-medium text-gray-900">Cantidad: {{ $item->quantity }}</p>
                            <p class="text-sm text-gray-500">${{ number_format($item->unit_price, 2) }} c/u</p>
                            <p class="font-semibold text-blue-600">${{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Próximos pasos -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">¿Qué sigue ahora?</h3>
            
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-blue-900">Confirmación del pedido</p>
                        <p class="text-sm text-blue-700">Recibirás un email de confirmación con todos los detalles.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-blue-900">Revisión y producción</p>
                        <p class="text-sm text-blue-700">Nuestro equipo revisará tu pedido y comenzará la producción.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">3</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-blue-900">Envío</p>
                        <p class="text-sm text-blue-700">Te notificaremos cuando tu pedido esté listo para envío.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">4</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-medium text-blue-900">Entrega</p>
                        <p class="text-sm text-blue-700">Recibirás tu pedido en la dirección especificada.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a 
                href="{{ route('customer.orders') }}" 
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
            >
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Ver mis pedidos
            </a>
            
            <a 
                href="{{ route('customer.catalog') }}" 
                class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium"
            >
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Continuar comprando
            </a>
        </div>

        <!-- Información de contacto -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Tienes alguna pregunta?</h3>
            <p class="text-gray-600 mb-4">
                Nuestro equipo de atención al cliente está aquí para ayudarte.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a 
                    href="mailto:soporte@majosesport.com" 
                    class="inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    soporte@majosesport.com
                </a>
                
                <a 
                    href="tel:+573001234567" 
                    class="inline-flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    +57 300 123 4567
                </a>
            </div>
        </div>
    </div>
</div>
