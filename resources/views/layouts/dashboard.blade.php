@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="welcome-panel mb-4 p-4 d-flex flex-column align-items-center justify-content-center text-center bg-white shadow rounded" style="border-left: 8px solid #f14b4b;">
        <img src="/assets/img/majose%20logo.png" alt="Logo MajoseSport" class="welcome-logo mb-3" style="width: 6rem; height: 6rem;">
        <h2 class="fw-bold mb-2" style="color:#fff;">¡Bienvenido, {{ Auth::user()->name ?? 'Usuario' }}!</h2>
        <p class="mb-3" style="color:#222; font-size:1.1rem;">Nos alegra tenerte de vuelta en el sistema de gestión.<br>Revisa tus estadísticas y gestiona tu tienda fácilmente.</p>
        <a href="#" class="btn btn-dark px-4 py-2 fw-bold">Manual</a>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('ventas.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#f14b4b; color:#fff;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-cash-stack" style="font-size:2rem;color:#f14b4b;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">$25,000</h4>
                            <small>Ventas Totales</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('categorias.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#fff; color:#f14b4b; border-left:4px solid #f14b4b;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-tags" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" style="color:#111;">150</h4>
                            <small style="color:#f14b4b;">Categorías</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('compras.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#111; color:#fff;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-bag" style="font-size:2rem;color:#111;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">75</h4>
                            <small>Compras</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('marcas.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#fff; color:#111; border-left:4px solid #111;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-megaphone" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">250</h4>
                            <small style="color:#111;">Marcas</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('presentaciones.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#f8f9fa; color:#111; border-left:4px solid #111;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-layers" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">6</h4>
                            <small style="color:#111;">Presentaciones</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('productos.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#fff; color:#f14b4b; border-left:4px solid #f14b4b;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-box-seam" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" style="color:#111;">40</h4>
                            <small style="color:#f14b4b;">Productos</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('entregas.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#4b77f1; color:#fff;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-truck" style="font-size:2rem;color:#4b77f1;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Entregas</h6>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('usuarios.index') }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0" style="background:#fff; color:#6f42c1; border-left:4px solid #6f42c1;">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3 bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                            <i class="bi bi-people" style="font-size:2rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">28</h4>
                            <small style="color:#6f42c1;">Clientes</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0" style="color:#f14b4b;">Ventas Anuales</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0" style="color:#111;">Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="productosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de barras - Ventas Anuales
    const ventasChart = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasChart, {
        type: 'bar',
        data: {
            labels: ['2019', '2020', '2021', '2022', '2023'],
            datasets: [{
                label: 'Ventas Anuales',
                data: [65000, 58000, 76000, 80000, 95000],
                backgroundColor: '#f14b4b',
                borderColor: '#111',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#eee' }, ticks: { color: '#111' } },
                x: { grid: { color: '#eee' }, ticks: { color: '#111' } }
            }
        }
    });

    // Gráfico de pastel - Productos Más Vendidos
    const productosChart = document.getElementById('productosChart').getContext('2d');
    new Chart(productosChart, {
        type: 'doughnut',
        data: {
            labels: ['Producto A', 'Producto B', 'Producto C', 'Producto D'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#f14b4b',
                    '#111',
                    '#fff',
                    '#222'
                ],
                borderColor: [
                    '#fff',
                    '#f14b4b',
                    '#111',
                    '#f14b4b'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, labels: { color: '#111' } }
            }
        }
    });
</script>
@endsection

@section('livewire')
    @livewire('servicios-index')
@endsection