<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <span class="text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}</span>
                    <!-- Iconos flotantes (carrito, perfil, notificaciones) -->
    <livewire:floating-icons />
</div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.catalog') }}" class="text-blue-600 hover:text-blue-800">
                        Explorar catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total de Pedidos</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pedidos Pendientes</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">En Producción</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['in_production'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Gastado</p>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Pedidos Recientes -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Pedidos Recientes</h2>
                            <a href="{{ route('customer.orders') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Ver todos
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentOrders && $recentOrders->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentOrders as $order)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">Pedido #{{ $order->order_number }}</p>
                                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'review') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'production') bg-orange-100 text-orange-800
                                                @elseif($order->status === 'ready') bg-green-100 text-green-800
                                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                @elseif($order->status === 'delivered') bg-gray-100 text-gray-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $order->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos recientes</h3>
                                <p class="mt-1 text-sm text-gray-500">Comienza explorando nuestro catálogo.</p>
                                <div class="mt-4">
                                    <a href="{{ route('customer.catalog') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                        Explorar productos
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('customer.catalog') }}" class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="font-medium">Explorar Catálogo</span>
                        </a>
                        
                        <a href="{{ route('customer.cart') }}" class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            <span class="font-medium">Ver Carrito</span>
                        </a>
                        
                        <a href="{{ route('customer.orders') }}" class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium">Mis Pedidos</span>
                        </a>
                        
                        <a href="{{ route('customer.my-profile') }}" class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium">Mi Perfil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Destacados -->
        @if($featuredProducts && $featuredProducts->count() > 0)
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Productos Destacados</h2>
                            <a href="{{ route('customer.catalog') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                Ver todos
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($featuredProducts->take(8) as $product)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                        @if($product->main_image_url)
                                            <img 
                                                src="{{ $product->main_image_url }}" onerror="this.onerror=null;this.src='/images/placeholder.jpg'" 
                                                alt="{{ $product->name }}"
                                                class="w-full h-48 object-cover hover:scale-105 transition-transform duration-200"
                                            >
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center text-gray-400">
                                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $product->category }}</p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-lg font-bold text-blue-600">${{ number_format($product->base_price, 2) }}</span>
                                            @if($product->allows_customization)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Personalizable
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-3 flex space-x-2">
                                            <button 
                                                wire:click="showProductDetail({{ $product->id }})"
                                                class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors"
                                            >
                                                Ver detalles
                                            </button>
                                            <button 
                                                wire:click="addToCart({{ $product->id }})"
                                                class="flex-1 px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors"
                                            >
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>