<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 px-3">
    <div class="container-fluid align-items-center">
        <button class="sidebar-toggler btn btn-hamburger me-3" aria-label="Abrir menú lateral" style="border: none; background: none; box-shadow: none; padding: 0.5rem;">
            <i class="bi bi-list" style="font-size: 1.7rem;"></i>
        </button>
        <div class="d-flex align-items-center gap-3 flex-grow-1">
            <span class="fw-bold text-danger d-none d-md-inline">Panel de Administración</span>
        </div>
        <div class="d-flex align-items-center gap-3 ms-auto">
            <a href="#" class="text-dark position-relative" title="Notificaciones">
                <i class="bi bi-bell" style="font-size: 1.4rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </a>
            <a href="#" class="text-dark" title="Mensajes"><i class="bi bi-envelope" style="font-size: 1.4rem;"></i></a>
            <a href="#" class="text-dark" title="Idioma"><i class="bi bi-translate" style="font-size: 1.4rem;"></i></a>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('assets/img/majose logo.png') }}" alt="Logo" style="width: 2.2rem; height: 2.2rem; border-radius: 50%; background: #fff; object-fit: cover;">
                    <span class="ms-2 d-none d-lg-inline">Hola, {{ Auth::user()->name ?? 'Usuario' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    @php
                        $user = Auth::user();
                        $isAdmin = $user && $user->role === 'admin';
                        $isWorker = $user && method_exists($user, 'isWorker') && $user->isWorker();
                        $isCustomer = $user && method_exists($user, 'isCustomer') && $user->isCustomer();
                    @endphp
                    
                    @if($isAdmin)
                        <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person-circle me-2"></i>Mi Perfil</a></li>
                    @elseif($isWorker)
                        <li><a class="dropdown-item" href="{{ route('worker.my-profile') }}"><i class="bi bi-person-circle me-2"></i>Perfil</a></li>
                    @elseif($isCustomer)
                        <li><a class="dropdown-item" href="{{ route('customer.my-profile') }}"><i class="bi bi-person-circle me-2"></i>Perfil</a></li>
                    @endif
                    
                    <li><a class="dropdown-item" href="{{ route('configuracion') ?? '#' }}"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a></li>
                </ul>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>