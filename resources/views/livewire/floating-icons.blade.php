<div>
    <div class="fixed bottom-6 right-6 z-50">
    @php
        $isWorker = auth()->check() && method_exists(auth()->user(), 'isWorker') && auth()->user()->isWorker();
    @endphp
    <!-- Iconos flotantes -->
    <div class="flex flex-col space-y-4">
        @if(Auth::check())
            <!-- Pedidos (acceso rápido para clientes) -->
            <div class="relative">
                <a href="{{ route('customer.orders') }}"
                    class="w-14 h-14 text-white rounded-full shadow-lg transition-all duration-200 flex items-center justify-center group hover:scale-110"
                    style="background-color: #4f46e5; color: #ffffff; opacity: 1; box-shadow: 0 6px 18px rgba(79,70,229,0.18); z-index:60;"
                    title="Mis Pedidos"
                >
                    <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 7a2 2 0 012-2h3.28a2 2 0 001.94-1.4l.72-2.16A1 1 0 0112 1h0a1 1 0 01.82.44l.72 1.08A2 2 0 0016.46 4H20a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm7-3l-.5 1.5A1 1 0 0110.5 6H13a1 1 0 00.82-.44L14.5 4H10zM7 10h10v2H7v-2zm0 4h7v2H7v-2z" />
                    </svg>
                
                    <!-- Tooltip -->
                </a>
                <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Mis Pedidos
                    <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
                </div>
            </div>
        @endif
        <!-- Carrito / Órdenes (para trabajadores) -->
        <div class="relative">
            <button 
                wire:click="toggleCart"
                class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center group hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                </svg>
                @if(!$isWorker && $this->totalItems > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
                        {{ $this->totalItems > 99 ? '99+' : $this->totalItems }}
                    </span>
                @endif
            </button>
            
                        <!-- Tooltip -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-black/60 backdrop-blur-md text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                {{ $isWorker ? 'Órdenes y Entregas' : 'Carrito de compras' }}
                <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900/80 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>

        </div>

        <!-- Notificaciones -->
        <div class="relative">
            <button 
                wire:click="toggleNotifications"
                class="w-14 h-14 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 transition-all duration-200 flex items-center justify-center group hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4.828 17l2.586-2.586a2 2 0 012.828 0L12 17H4.828z"></path>
                </svg>
                @if($unreadNotifications > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </span>
                @endif
            </button>
            
            <!-- Tooltip -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                Notificaciones
                <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>
        </div>

        <!-- Perfil -->
        <div class="relative">
            <button 
                wire:click="toggleProfile"
                class="w-14 h-14 bg-purple-600 text-white rounded-full shadow-lg hover:bg-purple-700 transition-all duration-200 flex items-center justify-center group hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </button>
            
            <!-- Tooltip -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                Mi Perfil
                <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>
        </div>
    </div>

    <!-- Panel del Carrito -->
    @if($showCart)
        <div class="absolute bottom-0 right-20 w-80 bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $isWorker ? 'Órdenes y Entregas' : 'Carrito de Compras' }}</h3>
                    <button wire:click="closeAll" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="max-h-80 overflow-y-auto">
                @if($isWorker)
                    <div class="p-4 space-y-3">
                        <a href="{{ route('worker.orders') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Ver Órdenes</p>
                                    <p class="text-sm text-gray-500">Accede a la lista de pedidos</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-400">→</div>
                        </a>

                        <a href="{{ route('worker.orders') }}?status=shipped" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v13H3zM16 21l-4-4-4 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Entregas</p>
                                    <p class="text-sm text-gray-500">Pedidos enviados / por entregar</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-400">→</div>
                        </a>
                    </div>
                @else
                    @if(count($cartItems) > 0)
                        <div class="p-4 space-y-3">
                            @foreach($cartItems as $cartKey => $item)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
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
                                        <p class="text-sm text-gray-500">${{ number_format($item['unit_price'], 2) }}</p>
                                        @if(!empty($item['customization']['file']))
                                            @php
                                                $file = $item['customization']['file'];
                                                $file = str_replace('\\', '/', $file);
                                                $url = null;
                                                if (!empty($file) && (str_starts_with($file, 'http') || str_starts_with($file, '//'))) {
                                                    $url = $file;
                                                } elseif (!empty($file) && file_exists(public_path('storage/' . ltrim($file, '/')))) {
                                                    $url = '/storage/' . ltrim($file, '/');
                                                } elseif (!empty($file) && file_exists(storage_path('app/public/' . ltrim($file, '/')))) {
                                                    $url = '/product-image/' . basename($file);
                                                }
                                            @endphp
                                            @if($exists)
                                                <div class="mt-1">
                                                    <img src="{{ $url }}" alt="Referencia" class="h-12 w-12 object-cover rounded cursor-pointer" onclick="openPreviewModal('{{ $url }}')">
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] - 1 }})"
                                            class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors"
                                        >
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span class="text-sm font-medium w-8 text-center">{{ $item['quantity'] }}</span>
                                        <button 
                                            wire:click="updateQuantity('{{ $cartKey }}', {{ $item['quantity'] + 1 }})"
                                            class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors"
                                        >
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <button 
                                        wire:click="removeFromCart('{{ $cartKey }}')"
                                        class="text-red-500 hover:text-red-700 transition-colors"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="p-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-lg font-bold text-blue-600">{{ $this->formattedTotal }}</span>
                            </div>
                            <div class="space-y-2">
                                <a 
                                    href="{{ route('customer.cart') }}" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center block font-medium"
                                >
                                    Ver carrito completo
                                </a>
                                <a 
                                    href="{{ route('customer.checkout') }}" 
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-center block font-medium"
                                >
                                    Proceder al pago
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Carrito vacío</h3>
                            <p class="mt-1 text-sm text-gray-500">Agrega algunos productos para comenzar.</p>
                            <div class="mt-4">
                                <a 
                                    href="{{ route('customer.catalog') }}" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200"
                                >
                                    Explorar productos
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <!-- Panel de Notificaciones -->
    @if($showNotifications)
        <div class="absolute bottom-0 right-20 w-80 bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Notificaciones</h3>
                    <button wire:click="closeAll" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="max-h-80 overflow-y-auto">
                @if(count($notifications) > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div 
                                wire:click="markNotificationAsRead({{ $notification['id'] }})"
                                class="p-4 hover:bg-gray-50 cursor-pointer transition-colors {{ !$notification['read'] ? 'bg-blue-50' : '' }}"
                            >
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($notification['type'] === 'order_update')
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification['type'] === 'product')
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $notification['message'] }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification['time'] }}</p>
                                    </div>
                                    @if(!$notification['read'])
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4.828 17l2.586-2.586a2 2 0 012.828 0L12 17H4.828z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                        <p class="mt-1 text-sm text-gray-500">Te notificaremos cuando tengas actualizaciones.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Panel de Perfil -->
    @if($showProfile)
        <div class="absolute bottom-0 right-20 w-80 bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Mi Perfil</h3>
                    <button wire:click="closeAll" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-4">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900">{{ Auth::user()->name }}</h4>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @php
                        $isWorker = Auth::check() && method_exists(Auth::user(), 'isWorker') && Auth::user()->isWorker();
                    @endphp
                    <a 
                        href="{{ $isWorker ? route('worker.profile') : route('customer.my-profile') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Editar Perfil</span>
                    </a>
                    
                    <a 
                        href="{{ $isWorker ? route('worker.orders') : route('customer.orders') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-medium">Mis Pedidos</span>
                    </a>
                    
                    <a 
                        href="{{ $isWorker ? route('worker.orders') : route('customer.cart') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                        <span class="font-medium">Mi Carrito</span>
                    </a>
                    
                    <div class="border-t pt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button 
                                type="submit"
                                class="flex items-center w-full p-3 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                            >
                                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="font-medium">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@include('partials.image-preview-modal')

<!-- Overlay para cerrar paneles -->
@if($showCart || $showNotifications || $showProfile)
    <div 
        wire:click="closeAll"
        class="fixed inset-0 bg-gray-300 bg-opacity-40"
    ></div>
@endif
    </div>

