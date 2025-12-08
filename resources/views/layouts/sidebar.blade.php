<div id="sidebar" class="active">
    <div class="sidebar-wrapper active ps ps--active-y">
        <div class="sidebar-header d-flex align-items-center gap-2 py-3 px-3">
            <img src="{{ asset('assets/img/majose logo.png') }}" alt="Logo" style="width: 2.5rem; height: 2.5rem;">
            <span class="logo_name fw-bold" style="color: #e53935">MajoseSport</span>
        </div>
        <div class="sidebar-section-title px-4 mt-2 mb-1 text-uppercase text-muted small">Menú</div>
        <ul class="menu" id="sidebar-menu">
            <li><a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-house-door"></i><span class="link_name">Dashboard</span></a></li>
            <li><a href="{{ route('categorias.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-folder"></i><span class="link_name">Categorías</span></a></li>
            <li><a href="{{ route('marcas.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-tags"></i><span class="link_name">Marcas</span></a></li>
            <li><a href="{{ route('presentaciones.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-layers"></i><span class="link_name">Presentaciones</span></a></li>
            <li><a href="{{ url('/admin/billboard') }}" class="d-flex align-items-center gap-2 {{ request()->is('admin/billboard') ? 'active' : '' }}"><i class="bi bi-megaphone"></i><span class="link_name">Billboard</span><span class="badge bg-danger ms-2">New</span></a></li>
            <li><a href="{{ route('productos.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-box"></i><span class="link_name">Productos</span></a></li>
            <li><a href="{{ route('compras.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-bag"></i><span class="link_name">Compras</span></a></li>
            <li><a href="{{ route('ventas.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-bar-chart"></i><span class="link_name">Ventas</span></a></li>
            <li><a href="{{ route('caja.gastos') }}" class="d-flex align-items-center gap-2"><i class="bi bi-cash-stack"></i><span class="link_name">Caja / Gastos</span><span class="badge bg-danger ms-2">New</span></a></li>
            <li><a href="#" class="d-flex align-items-center gap-2"><i class="bi bi-grid"></i><span class="link_name">Mesas</span><span class="badge bg-danger ms-2">New</span></a></li>
            <li><a href="{{ route('clientes.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-people"></i><span class="link_name">Clientes</span></a></li>
            <li><a href="{{ route('entregas.index') }}" class="d-flex align-items-center gap-2"><i class="bi bi-truck"></i><span class="link_name">Entregas</span></a></li>
            <li><a href="{{ route('admin.reports') }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('admin.reports') ? 'active' : '' }}"><i class="bi bi-clipboard-data"></i><span class="link_name">Informes</span><span class="badge bg-danger ms-2">New</span></a></li>
            <li><a href="#" class="d-flex align-items-center gap-2"><i class="bi bi-building"></i><span class="link_name">Empresa</span><span class="badge bg-danger ms-2">New</span></a></li>
        </ul>
        <div class="sidebar-section-title px-4 mt-3 mb-1 text-uppercase text-muted small">ADMIN</div>
        <ul class="menu">
            <li><a href="{{ route('admin.profile') }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('admin.profile') ? 'active' : '' }}"><i class="bi bi-person-circle"></i><span class="link_name">Mi Perfil</span></a></li>
            <li><a href="{{ route('admin.users') }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="bi bi-people"></i><span class="link_name">Usuarios</span></a></li>
            <li><a href="{{ route('usuarios.index', ['type' => 'all']) }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i><span class="link_name">Gestión Usuarios</span></a></li>
            <li><a href="{{ route('roles.index') }}" class="d-flex align-items-center gap-2 {{ request()->routeIs('roles.*') ? 'active' : '' }}"><i class="bi bi-shield"></i><span class="link_name">Roles</span></a></li>
        </ul>
        <div class="sidebar-section-title px-4 mt-3 mb-1 text-uppercase text-muted small">MANUAL USER</div>
        <ul class="menu">
            <li><a href="#" class="d-flex align-items-center gap-2"><i class="bi bi-journal-bookmark"></i><span class="link_name">Manual</span><span class="badge bg-danger ms-2">New</span></a></li>
        </ul>
        <div class="text-center mb-3 small text-muted">
            <div class="fw-bold" style="color: #e53935">Jm Develop</div>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <a href="#" class="text-dark"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-dark"><i class="bi bi-youtube"></i></a>
                <a href="#" class="text-dark"><i class="bi bi-github"></i></a>
                <a href="#" class="text-dark"><i class="bi bi-tiktok"></i></a>
            </div>
            <div class="mt-2">V3.3.9 2025 &copy; | Jm Develop</div>
        </div>
    </div>
</div>
