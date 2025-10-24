<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <h1 class="text-2xl font-bold text-gray-900">Mis Pedidos</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.catalog') }}" class="text-blue-600 hover:text-blue-800">
                        ← Continuar comprando
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar pedido</label>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Número de pedido..." 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select wire:model.live="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los estados</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ordenar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                    <select wire:model.live="sortBy" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="created_at">Fecha de pedido</option>
                        <option value="total_amount">Total</option>
                        <option value="status">Estado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de pedidos -->
        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <!-- Header del pedido -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Pedido #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Realizado el {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->items->sum('quantity') }} productos
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                        </div>

                        <!-- Detalles del pedido -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Productos -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Productos</h4>
                                    <div class="space-y-3">
                                        @foreach($order->items as $item)
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                                    @if($item->product->main_image)
                                                        <img 
                                                            src="{{ asset('storage/' . $item->product->main_image) }}" 
                                                            alt="{{ $item->product->name }}"
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
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $item->product->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        Cantidad: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                                                    </p>
                                                    @if($item->customization_text || $item->size || $item->color)
                                                        <div class="mt-1 space-x-1">
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
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Información adicional -->
<div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Información del pedido</h4>
                                    <div class="space-y-3">
                                        @if($order->estimated_delivery)
                                            <div class="flex items-center text-sm">
                                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-gray-600">Entrega estimada:</span>
                                                <span class="ml-2 font-medium">{{ $order->estimated_delivery->format('d/m/Y') }}</span>
                                            </div>
                                        @endif

                                        @if($order->customer_notes)
                                            <div class="text-sm">
                                                <span class="text-gray-600">Notas:</span>
                                                <p class="mt-1 text-gray-900">{{ $order->customer_notes }}</p>
                                            </div>
                                        @endif

                                        @if($order->files->count() > 0)
                                            <div class="text-sm">
                                                <span class="text-gray-600">Archivos adjuntos:</span>
                                                <div class="mt-1 space-y-1">
                                                    @foreach($order->files as $file)
                                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                            </svg>
                                                            {{ $file->file_name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Ver detalles completos
                                    </button>
                                    @if($order->status === 'delivered')
                                        <button class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            Volver a pedir
                                        </button>
                                    @endif
</div>

                                @if(in_array($order->status, ['pending', 'review']))
                                    <button 
                                        wire:click="cancelOrder({{ $order->id }})"
                                        wire:confirm="¿Estás seguro de que quieres cancelar este pedido?"
                                        class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors"
                                    >
                                        Cancelar pedido
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes pedidos aún</h3>
                <p class="mt-2 text-gray-500">Comienza explorando nuestro catálogo de productos.</p>
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
</div>