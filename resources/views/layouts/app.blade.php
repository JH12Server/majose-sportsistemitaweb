<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MajoseSport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/majose logo.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        .bg-login {
            background-color: #f14b4b !important;
            min-height: 100vh;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
            transition: margin-left var(--transition-speed);
        }
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
            }
            .sidebar {
                position: fixed;
                z-index: 1050;
                left: -100%;
                transition: left 0.3s;
            }
            .sidebar.active {
                left: 0;
            }
        }
    </style>
</head>
<body class="@yield('body-class')">
    @hasSection('simple')
        @yield('content')
    @else
        @auth
            @include('layouts.sidebar')
            <div class="main-content">
                @include('layouts.navbar')
                @yield('content')
                @yield('livewire')
            </div>
        @endauth
    @endif

    @yield('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
    @livewireScripts
    <!-- Pusher + Echo (required for real-time notifications) -->
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
    <script>
        try {
            window.Pusher = Pusher;
            window.Echo = new window.Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                wsHost: '{{ env('PUSHER_HOST', "ws.pusherapp.com") }}',
                wsPort: parseInt('{{ env('PUSHER_PORT', 443) }}'),
                forceTLS: {{ env('PUSHER_SCHEME', 'https') === 'https' ? 'true' : 'false' }},
                encrypted: true,
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            @auth
                const userId = {{ Auth::id() }};
                // listen on private channel for this user
                window.Echo.private('user.' + userId)
                    .listen('.order.created', (e) => { Livewire.emit('notification-created', e); })
                    .listen('.order.paid', (e) => { Livewire.emit('notification-created', e); })
                    .listen('.order.status_changed', (e) => { Livewire.emit('notification-created', e); });

                // admin channel
                @if(Auth::user()->isAdmin())
                    window.Echo.private('admin')
                        .listen('.order.created', (e) => { Livewire.emit('notification-created', e); })
                        .listen('.order.paid', (e) => { Livewire.emit('notification-created', e); })
                        .listen('.order.status_changed', (e) => { Livewire.emit('notification-created', e); });
                @endif
            @endauth
        } catch (err) {
            console.debug('Echo init error', err);
        }
    </script>
</body>
</html>
