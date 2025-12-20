<div class="position-relative" wire:poll.5s="loadNotifications" wire:init="loadNotifications">
    <!-- Bell Icon Button -->
    <button 
        wire:click="togglePanel" 
        class="btn btn-link text-dark p-2 position-relative"
        style="border: none; background: none; cursor: pointer;"
        title="Notificaciones"
    >
        <i class="bi bi-bell" style="font-size: 1.4rem;"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Panel -->
    @if($showPanel)
        <div 
            class="position-fixed bg-white shadow-lg rounded-3"
            style="width: 450px; max-height: 600px; overflow-y: auto; right: 20px; top: 70px; z-index: 1050; border: 1px solid #e0e0e0; box-shadow: 0 10px 40px rgba(0,0,0,0.1);"
        >
            <!-- Panel Header -->
            <div class="bg-light px-4 py-3 border-bottom d-flex justify-content-between align-items-center sticky-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="mb-0 fw-bold">Notificaciones</h5>
                <div class="d-flex gap-2">
                    @if($unreadCount > 0)
                        <button 
                            wire:click="markAllAsRead"
                            class="btn btn-sm p-0 text-white"
                            style="background: none; border: none; cursor: pointer;"
                            title="Marcar todas como leídas"
                        >
                            <i class="bi bi-check-all"></i>
                        </button>
                    @endif
                    <button 
                        wire:click="togglePanel"
                        class="btn btn-sm p-0 text-white"
                        style="background: none; border: none; cursor: pointer;"
                        title="Cerrar"
                    >
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            @if(count($notifications) > 0)
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div 
                            class="px-4 py-3 border-bottom {{ !$notification['is_read'] ? 'bg-light' : '' }}"
                            style="cursor: pointer; transition: background-color 0.2s;"
                        >
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1" wire:click="markAsRead({{ $notification['id'] }})" style="cursor: pointer;">
                                    <h6 class="mb-1 text-dark fw-bold text-truncate">{{ $notification['title'] }}</h6>
                                    <p class="mb-1 text-secondary small text-truncate">{{ $notification['message'] }}</p>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </small>
                                </div>
                                <button 
                                    wire:click="deleteNotification({{ $notification['id'] }})"
                                    class="btn btn-sm btn-link text-danger p-0"
                                    title="Eliminar notificación"
                                    style="flex-shrink: 0;"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Order Link if available -->
                            @if($notification['order_id'])
                                <a 
                                    href="{{ route('customer.orders') }}"
                                    class="btn btn-sm btn-outline-primary mt-2 w-100 text-decoration-none"
                                >
                                    Ver Pedido
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Clear All Button -->
                @if(count($notifications) > 0)
                    <div class="px-4 py-3 border-top text-center" style="background: #f8f9fa;">
                        <button 
                            wire:click="deleteAllNotifications"
                            class="btn btn-sm btn-link text-danger"
                        >
                            Limpiar todas
                        </button>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                    <p class="mb-0">No tienes notificaciones</p>
                </div>
            @endif
        </div>
    @endif
</div>
