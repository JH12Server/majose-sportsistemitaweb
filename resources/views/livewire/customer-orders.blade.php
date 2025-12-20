<div class="min-h-screen bg-gray-50">
    <!-- Success Message for Cash Payment -->
    @if(session('success_message'))
        <div class="bg-green-50 border-b border-green-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success_message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <h1 class="text-xl font-bold text-gray-900">Mis Pedidos</h1>
                <a href="{{ route('customer.catalog') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                    ← Continuar comprando
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        
        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm p-3 mb-3 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">BUSCAR</label>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Número de pedido..." 
                        class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ESTADO</label>
                    <select wire:model.live="status" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Todos los estados</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ordenar -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">ORDENAR</label>
                    <select wire:model.live="sortBy" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="created_at">Más recientes</option>
                        <option value="total_amount">Mayor precio</option>
                        <option value="status">Estado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de pedidos -->
        @if($orders->count() > 0)
            <div class="space-y-2">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow" x-data="{ expandProducts: false, expandInfo: false }">
                        <!-- Header del pedido -->
                        <div class="p-3 border-b border-gray-200">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline gap-2 flex-wrap">
                                        <h3 class="text-sm font-bold text-gray-900">
                                            #{{ $order->order_number }}
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ $order->items->sum('quantity') }} producto(s) • ${{ number_format($order->total_amount, 2) }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold whitespace-nowrap flex-shrink-0 status-badge" data-status="{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Detalles del pedido -->
                        <div class="p-3">
                            <!-- Productos -->
                            <div>
                                <button 
                                    @click="expandProducts = !expandProducts"
                                    class="flex items-center gap-1 w-full text-left py-1 px-1 hover:bg-gray-50 rounded transition-colors mb-2"
                                >
                                    <svg class="h-4 w-4 text-gray-500 transition-transform" :class="{ 'rotate-180': expandProducts }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-gray-700">📦 PRODUCTOS ({{ $order->items->count() }})</span>
                                </button>
                                
                                <div x-show="expandProducts" class="space-y-1">
                                    @foreach($order->items as $item)
                                        <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200">
                                            <div class="flex gap-2">
                                                @if($item->product->main_image)
                                                    <img 
                                                        src="{{ $item->product->main_image_url ?? asset('images/placeholder.jpg') }}" 
                                                        alt="{{ $item->product->name }}"
                                                        class="w-8 h-8 object-cover rounded flex-shrink-0"
                                                    >
                                                @else
                                                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 line-clamp-1">{{ $item->product->name }}</p>
                                                    <p class="text-gray-600">{{ $item->quantity }}x ${{ number_format($item->unit_price, 2) }}</p>
                                                    @if($item->size || $item->color || $item->customization_text)
                                                        <div class="flex gap-1 mt-0.5 flex-wrap">
                                                            @if($item->size)
                                                                <span class="bg-blue-50 text-blue-700 px-1 py-0 rounded text-xs">T:{{ $item->size }}</span>
                                                            @endif
                                                            @if($item->color)
                                                                <span class="bg-green-50 text-green-700 px-1 py-0 rounded text-xs">C:{{ $item->color }}</span>
                                                            @endif
                                                            @if($item->customization_text)
                                                                <span class="bg-purple-50 text-purple-700 px-1 py-0 rounded text-xs line-clamp-1">{{ $item->customization_text }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Información del pedido -->
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <button 
                                    @click="expandInfo = !expandInfo"
                                    class="flex items-center gap-1 w-full text-left py-1 px-1 hover:bg-gray-50 rounded transition-colors mb-2"
                                >
                                    <svg class="h-4 w-4 text-gray-500 transition-transform" :class="{ 'rotate-180': expandInfo }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-gray-700">ℹ️ INFORMACIÓN</span>
                                </button>

                                <div x-show="expandInfo" class="space-y-1">
                                    @if($order->estimated_delivery)
                                        <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200 flex justify-between items-center">
                                            <span class="text-gray-600">📅 Entrega est:</span>
                                            <span class="font-semibold text-gray-900">{{ $order->estimated_delivery->format('d/m/Y') }}</span>
                                        </div>
                                    @endif

                                    @if($order->customer_notes)
                                        @php
                                            $formattedNotes = \App\Helpers\OrderNotesFormatter::formatNotes($order->customer_notes);
                                            $isJson = \App\Helpers\OrderNotesFormatter::isJsonNotes($order->customer_notes);
                                        @endphp
                                        
                                        @if($isJson && is_array($formattedNotes))
                                            @foreach($formattedNotes as $section => $content)
                                                @if(is_array($content))
                                                    <div class="bg-gray-50 p-2 rounded border border-gray-200">
                                                        <h6 class="text-xs font-bold text-gray-700 mb-1">{{ $section }}</h6>
                                                        <div class="space-y-0.5">
                                                            @foreach($content as $label => $value)
                                                                <div class="flex justify-between gap-1">
                                                                    <span class="text-gray-600 text-xs">{{ $label }}:</span>
                                                                    <span class="font-semibold text-gray-900 text-xs text-right line-clamp-1">{{ $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200 flex justify-between items-center">
                                                        <span class="text-gray-600">{{ $section }}:</span>
                                                        <span class="font-semibold text-gray-900">{{ $content }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200">
                                                <p class="text-gray-600 mb-1 font-semibold">Notas:</p>
                                                <p class="text-gray-900 line-clamp-2">{{ $order->customer_notes }}</p>
                                            </div>
                                        @endif
                                    @endif

                                    @if($order->files->count() > 0)
                                        <div class="text-xs bg-gray-50 p-2 rounded border border-gray-200">
                                            <p class="text-gray-600 mb-1 font-semibold">📎 Archivos:</p>
                                            <div class="space-y-0.5">
                                                @foreach($order->files as $file)
                                                    <a href="{{ Storage::disk('public')->url($file->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 line-clamp-1">
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
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
                        <div class="px-3 py-2 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-2 flex-wrap text-xs">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">
                                Ver detalles
                            </button>
                            <div class="flex gap-2">
                                @if($order->status === 'delivered')
                                    <button class="text-green-600 hover:text-green-800 font-semibold hover:underline">
                                        ↻ Volver a pedir
                                    </button>
                                @endif
                                @if(in_array($order->status, ['pending', 'review']))
                                    <button 
                                        wire:click="cancelOrder({{ $order->id }})"
                                        wire:confirm="¿Cancelar este pedido?"
                                        class="px-2 py-1 font-bold text-red-600 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition-colors"
                                    >
                                        ✕ Cancelar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if($orders->hasPages())
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-base font-semibold text-gray-900 mb-1">No tienes pedidos aún</h3>
                <p class="text-sm text-gray-600 mb-4">Comienza explorando nuestro catálogo de productos.</p>
                <a 
                    href="{{ route('customer.catalog') }}" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Explorar productos
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .status-badge[data-status="pending"] {
        @apply bg-yellow-100 text-yellow-800;
    }
    
    .status-badge[data-status="review"] {
        @apply bg-blue-100 text-blue-800;
    }
    
    .status-badge[data-status="production"] {
        @apply bg-orange-100 text-orange-800;
    }
    
    .status-badge[data-status="ready"] {
        @apply bg-emerald-100 text-emerald-800;
    }
    
    .status-badge[data-status="shipped"] {
        @apply bg-purple-100 text-purple-800;
    }
    
    .status-badge[data-status="delivered"] {
        @apply bg-green-100 text-green-800;
    }
    
    .status-badge[data-status="cancelled"] {
        @apply bg-red-100 text-red-800;
    }
    
    .status-badge[data-status="paid"] {
        @apply bg-indigo-100 text-indigo-800;
    }
    
    .status-badge:not([data-status]) {
        @apply bg-gray-200 text-gray-800;
    }
</style>
