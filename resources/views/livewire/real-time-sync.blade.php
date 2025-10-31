<div>
    <!-- Indicador de conexión -->
    <div class="fixed top-4 left-4 z-40">
        <div class="flex items-center space-x-2 bg-white rounded-lg shadow-lg px-3 py-2">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full {{ $isConnected ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></div>
                <span class="text-xs font-medium {{ $isConnected ? 'text-green-600' : 'text-red-600' }}">
                    {{ $isConnected ? 'Conectado' : 'Desconectado' }}
                </span>
            </div>
            
            @if(!$isConnected)
                <button 
                    wire:click="reconnect"
                    class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                >
                    Reconectar
                </button>
            @endif
        </div>
    </div>

    <!-- Scripts para sincronización en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar conexión cada 30 segundos
            setInterval(() => {
                @this.call('checkConnection');
            }, {{ $syncInterval }});

            // Manejar eventos de notificaciones de trabajadores
            window.addEventListener('worker-notification', event => {
                const data = event.detail;
                
                // Mostrar notificación toast
                showToast(data.message, 'info');
                
                // Si es urgente, hacer sonido de notificación
                if (data.urgent) {
                    playNotificationSound();
                }
                
                // Actualizar contadores si es necesario
                if (data.type === 'new_order' || data.type === 'order_updated') {
                    // Disparar evento para actualizar estadísticas
                    Livewire.dispatch('refresh-stats');
                }
            });

            // Manejar eventos de notificaciones de clientes
            window.addEventListener('customer-notification', event => {
                const data = event.detail;
                console.log('Notificación al cliente:', data);
                
                // Aquí se implementaría la notificación real al cliente
                // Por ejemplo, usando WebSockets o Pusher
            });

            // Manejar eventos de verificación de conexión
            window.addEventListener('connection-checked', event => {
                console.log('Conexión verificada:', new Date().toLocaleTimeString());
            });

            // Detectar cuando el usuario sale de la página
            window.addEventListener('beforeunload', function() {
                @this.call('disconnect');
            });

            // Detectar cuando el usuario regresa a la página
            window.addEventListener('focus', function() {
                @this.call('reconnect');
            });

            // Función para reproducir sonido de notificación
            function playNotificationSound() {
                try {
                    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT');
                    audio.play().catch(e => console.log('No se pudo reproducir el sonido:', e));
                } catch (e) {
                    console.log('Error reproduciendo sonido:', e);
                }
            }

            // Función para mostrar notificaciones toast
            function showToast(message, type = 'info') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    info: 'bg-blue-500',
                    warning: 'bg-yellow-500'
                };
                
                const icons = {
                    success: 'M5 13l4 4L19 7',
                    error: 'M6 18L18 6M6 6l12 12',
                    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z'
                };
                
                toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform transition-all duration-300 translate-x-full`;
                toast.innerHTML = `
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${icons[type]}"></path>
                    </svg>
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(toast);
                
                // Animar entrada
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);
                
                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }, 5000);
            }

            // Simular eventos de prueba (solo para desarrollo)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                // Descomentar para pruebas
                // setTimeout(() => {
                //     window.dispatchEvent(new CustomEvent('worker-notification', {
                //         detail: {
                //             type: 'new_order',
                //             title: 'Nuevo pedido',
                //             message: 'Pedido #ORD-12345678 requiere atención',
                //             order_id: 1,
                //             priority: 'urgent',
                //             urgent: true
                //         }
                //     }));
                // }, 5000);
            }
        });
    </script>
</div>
