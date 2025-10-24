@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/majose-elegant.css') }}">
<div class="container-fluid py-4">
    <!-- Panel de bienvenida elegante -->
    <div class="majose-welcome-panel majose-animate-in mb-5">
        <div class="majose-welcome-logo" style="background: #FFFFFF !important; border: 2px solid #FFFFFF;">
            <img src="/assets/img/majose%20logo.png" alt="Logo MajoseSport" style="width: 100%; height: 100%; object-fit: contain; background: transparent;">
        </div>
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem; font-weight: 800;">¡Bienvenido, {{ Auth::user()->name ?? 'Usuario' }}!</h1>
        <p class="mb-4" style="font-size: 1.2rem; opacity: 0.9;">Nos alegra tenerte de vuelta en el sistema de gestión.<br>Revisa tus estadísticas y gestiona tu tienda fácilmente.</p>
        <a href="#" class="majose-btn majose-btn-dark">Manual de Usuario</a>
    </div>
    <!-- Estadísticas principales - Grid balanceado -->
    <div class="majose-stats-grid" style="grid-template-columns: repeat(4, 1fr); gap: 24px; margin: 32px 0;">
        <a href="{{ route('ventas.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-1">
            <div class="majose-card majose-card-red majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-white me-4">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number text-white">$25,000</div>
                        <div class="majose-stat-label text-white-50">Ventas Totales</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('categorias.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-2">
            <div class="majose-card majose-card-white majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-red me-4">
                        <i class="bi bi-tags" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number">150</div>
                        <div class="majose-stat-label">Categorías</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('compras.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-3">
            <div class="majose-card majose-card-red majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-white me-4">
                        <i class="bi bi-bag" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number text-white">75</div>
                        <div class="majose-stat-label text-white-50">Compras</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('marcas.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-4">
            <div class="majose-card majose-card-white majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-black me-4">
                        <i class="bi bi-megaphone" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number">250</div>
                        <div class="majose-stat-label">Marcas</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    <!-- Estadísticas secundarias - Grid balanceado -->
    <div class="majose-stats-grid" style="grid-template-columns: repeat(4, 1fr); gap: 24px; margin: 32px 0;">
        <a href="{{ route('presentaciones.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-1">
            <div class="majose-card majose-card-white majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-black me-4">
                        <i class="bi bi-layers" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number">6</div>
                        <div class="majose-stat-label">Presentaciones</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('productos.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-2">
            <div class="majose-card majose-card-white majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-red me-4">
                        <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number">40</div>
                        <div class="majose-stat-label">Productos</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('entregas.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-3">
            <div class="majose-card majose-card-red majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-white me-4">
                        <i class="bi bi-truck" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number text-white">12</div>
                        <div class="majose-stat-label text-white-50">Entregas</div>
                        </div>
                    </div>
                </div>
            </a>
        
        <a href="{{ route('usuarios.index') }}" class="text-decoration-none majose-animate-in majose-animate-delay-4">
            <div class="majose-card majose-card-white majose-hover-lift">
                <div class="d-flex align-items-center p-4">
                    <div class="majose-icon majose-icon-black me-4">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                        <div class="majose-stat-number">28</div>
                        <div class="majose-stat-label">Clientes</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <!-- Gráficos elegantes -->
    <div class="row mt-5">
        <div class="col-md-8 mb-4">
            <div class="majose-card majose-hover-lift">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="card-title mb-0 majose-text-gradient">Ventas Anuales</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="majose-card majose-hover-lift">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="card-title mb-0 majose-text-black">Productos Más Vendidos</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="productosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/majose-animations.js') }}"></script>
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
                backgroundColor: '#E53935',
                borderColor: '#1A1A1A',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    display: true,
                    labels: {
                        color: '#1A1A1A',
                        font: {
                            weight: '600'
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { 
                        color: 'rgba(26, 26, 26, 0.1)',
                        drawBorder: false
                    }, 
                    ticks: { 
                        color: '#424242',
                        font: {
                            weight: '500'
                        }
                    } 
                },
                x: { 
                    grid: { 
                        color: 'rgba(26, 26, 26, 0.1)',
                        drawBorder: false
                    }, 
                    ticks: { 
                        color: '#424242',
                        font: {
                            weight: '500'
                        }
                    } 
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
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
                    '#E53935',
                    '#1A1A1A',
                    '#424242',
                    '#F5F5F5'
                ],
                borderColor: [
                    '#FFFFFF',
                    '#E53935',
                    '#1A1A1A',
                    '#E53935'
                ],
                borderWidth: 3,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    display: true, 
                    labels: { 
                        color: '#1A1A1A',
                        font: {
                            weight: '600'
                        },
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    } 
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            }
        }
    });
</script>
@endsection

@section('livewire')
    @livewire('servicios-index')
@endsection