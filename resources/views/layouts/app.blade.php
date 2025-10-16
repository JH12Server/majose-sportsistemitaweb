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
        @include('layouts.sidebar')
        <div class="main-content">
            @include('layouts.navbar')
            @yield('content')
            @yield('livewire')
        </div>
    @endif

    @yield('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
