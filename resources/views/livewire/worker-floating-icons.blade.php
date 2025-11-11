<div class="fixed bottom-6 right-6 z-50">
    <!-- Iconos flotantes -->
    <div class="flex flex-col space-y-4">
        <!-- Notificaciones -->
        <div class="relative">
            <button 
                wire:click="toggleNotifications"
                class="w-14 h-14 bg-orange-600 text-white rounded-full shadow-lg hover:bg-orange-700 transition-all duration-200 flex items-center justify-center group hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4.828 17l2.586-2.586a2 2 0 012.828 0L12 17H4.828z"></path>
                </svg>
                @if($unreadNotifications > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold animate-pulse">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </span>
                @endif
            </button>
            
            <!-- Tooltip -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                Notificaciones de trabajo
                <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>
        </div>

        <!-- Perfil -->
        <div class="relative">
            <button 
                wire:click="toggleProfile"
                class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center group hover:scale-110"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </button>
            
            <!-- Tooltip -->
            <div class="absolute right-16 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white text-sm px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                Mi Perfil de Trabajador
                <div class="absolute right-0 top-1/2 transform translate-x-1 -translate-y-1/2 w-0 h-0 border-l-4 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>
        </div>
    </div>

    <!-- Panel de Notificaciones -->
    @if($showNotifications)
        <div class="absolute bottom-0 right-20 w-80 bg-white rounded-lg shadow-xl border border-gray-200 transform transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Notificaciones de Trabajo</h3>
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
                                class="p-4 hover:bg-gray-50 cursor-pointer transition-colors {{ !$notification['read'] ? 'bg-orange-50' : '' }}"
                            >
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($notification['type'] === 'urgent_order')
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification['type'] === 'ready_to_ship')
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification['type'] === 'delivery_reminder')
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification['type'] === 'assigned_order')
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $notification['message'] }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification['time'] }}</p>
                                        @if($notification['order_id'])
                                            <button 
                                                wire:click="goToOrder({{ $notification['order_id'] }})"
                                                class="mt-2 text-xs text-blue-600 hover:text-blue-800 font-medium"
                                            >
                                                Ver pedido →
                                            </button>
                                        @endif
                                    </div>
                                    @if(!$notification['read'])
                                        <div class="flex-shrink-0">
                                            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
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
                        <p class="mt-1 text-sm text-gray-500">Te notificaremos cuando tengas nuevas tareas.</p>
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
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-900">{{ Auth::user()->name }}</h4>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-sm text-blue-600 font-medium">{{ Auth::user()->role ?? 'Trabajador' }}</p>
                    </div>
                </div>
                
                <!-- Estadísticas del trabajador -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h5 class="text-sm font-medium text-gray-900 mb-3">Estadísticas de Hoy</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $workerStats['production_orders'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">En Producción</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $workerStats['completed_today'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Completados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $workerStats['urgent_orders'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Urgentes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $workerStats['pending_orders'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Pendientes</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a 
                        href="{{ route('worker.my-profile') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Editar Perfil</span>
                    </a>
                    
                    <a 
                        href="{{ route('worker.orders') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="font-medium">Mis Pedidos</span>
                    </a>
                    
                    <a 
                        href="{{ route('worker.products') }}" 
                        class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="font-medium">Productos</span>
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

<!-- Overlay para cerrar paneles -->
@if($showNotifications || $showProfile)
    <div 
        wire:click="closeAll"
        class="fixed inset-0 bg-black bg-opacity-25 z-40"
    ></div>
@endif
