<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MajoseSport Worker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/worker-dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/majose logo.png') }}">
    @livewireStyles
    <style>
        /* Estilos específicos para trabajadores */
        .worker-theme {
            --primary-color: #f59e0b;
            --secondary-color: #d97706;
            --success-color: #10b981;
            --error-color: #ef4444;
            --warning-color: #f59e0b;
        }
        
        .worker-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }
        
        .worker-header .text-gray-900 {
            color: white !important;
        }
        
        .worker-header .text-gray-500 {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .worker-header .text-gray-600 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .worker-floating-icons .bg-orange-600 {
            background-color: #f59e0b;
        }
        
        .worker-floating-icons .hover\:bg-orange-700:hover {
            background-color: #d97706;
        }
        
        /* Animaciones específicas para trabajadores */
        .worker-pulse {
            animation: worker-pulse 2s infinite;
        }
        
        @keyframes worker-pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        /* Estados de pedidos con colores específicos */
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-review {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-production {
            background-color: #fed7aa;
            color: #c2410c;
        }
        
        .status-ready {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-shipped {
            background-color: #e9d5ff;
            color: #7c3aed;
        }
        
        .status-delivered {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        /* Prioridades */
        .priority-normal {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .priority-urgent {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .priority-high {
            background-color: #fed7aa;
            color: #c2410c;
        }
        
        /* Responsive específico para trabajadores */
        @media (max-width: 768px) {
            .worker-floating-icons {
                bottom: 1rem;
                right: 1rem;
            }
            
            .worker-floating-icons .w-14 {
                width: 3rem;
                height: 3rem;
            }
            
            .worker-floating-icons .h-6 {
                height: 1.25rem;
                width: 1.25rem;
            }
        }
        
        /* Mejoras de accesibilidad para trabajadores */
        .worker-focus:focus {
            outline: 2px solid #f59e0b;
            outline-offset: 2px;
        }
        
        /* Loading states específicos */
        .worker-loading {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }
        
        .worker-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 2rem;
            height: 2rem;
            margin: -1rem 0 0 -1rem;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #f59e0b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>
</head>
<body class="bg-gray-50 worker-theme">
    <!-- Header -->
    <header class="worker-header shadow-sm border-b sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('worker.dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('assets/img/majose logo.png') }}" alt="MajoseSport" class="h-8 w-8">
                        <span class="text-xl font-bold">MajoseSport Worker</span>
                    </a>
                </div>
                
                <!-- Navegación principal -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('worker.dashboard') }}" class="hover:text-orange-200 transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('worker.orders') }}" class="hover:text-orange-200 transition-colors">
                        Pedidos
                    </a>
                    <a href="{{ route('worker.products') }}" class="hover:text-orange-200 transition-colors">
                        Productos
                    </a>
                </nav>
                
                <!-- Usuario -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm hidden sm:block">{{ Auth::user()->name }}</span>
                    <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full">
                        {{ Auth::user()->role ?? 'Trabajador' }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-orange-200 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="min-h-screen">
        @yield('content')
        @yield('livewire')
    </main>

    <!-- Iconos flotantes para trabajadores -->
    <livewire:worker-floating-icons />
    
    <!-- Sincronización en tiempo real -->
    <livewire:real-time-sync />
    
    <!-- Manejador de errores -->
    <livewire:error-handler />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
    @livewireScripts
    
    <!-- Notificaciones toast -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <script>
        // Sistema de notificaciones para trabajadores
        window.addEventListener('show-success', event => {
            showToast(event.detail.message || 'Operación exitosa', 'success');
        });
        
        window.addEventListener('show-error', event => {
            showToast(event.detail.message || 'Error en la operación', 'error');
        });
        
        window.addEventListener('show-info', event => {
            showToast(event.detail.message || 'Información', 'info');
        });
        
        window.addEventListener('show-warning', event => {
            showToast(event.detail.message || 'Advertencia', 'warning');
        });
        
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
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
        
        // Auto-refresh para notificaciones en tiempo real
        setInterval(() => {
            // Disparar evento para refrescar notificaciones
            Livewire.dispatch('refresh-notifications');
        }, 30000); // Cada 30 segundos
        
        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Lazy loading para imágenes
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('opacity-0');
                        img.classList.add('opacity-100');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
        
        // Manejo de eventos de notificaciones específicos para trabajadores
        window.addEventListener('customer-notification', event => {
            // Aquí se manejaría la notificación al cliente
            console.log('Notificación enviada al cliente:', event.detail);
        });
        
        // Manejo de eventos de pedidos
        window.addEventListener('show-order', event => {
            // Aquí se manejaría la navegación a un pedido específico
            console.log('Mostrar pedido:', event.detail);
        });
    </script>
</body>
</html>
