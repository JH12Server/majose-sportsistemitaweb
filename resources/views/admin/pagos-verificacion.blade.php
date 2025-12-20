@extends('layouts.app')

@section('title', 'Verificación de Pagos')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-0 fw-bold" style="color: #e53935">
                        <i class="bi bi-credit-card"></i> Verificación de Pagos
                    </h1>
                    <p class="text-muted mb-0">Verifica el estado de pago de los productos comprados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 4px solid #28a745;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #dc3545;">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('pagos.search') }}" class="d-flex gap-2">
                        @csrf
                        <input type="text" class="form-control" name="search" placeholder="Buscar cliente, producto, orden...">
                        <select class="form-select" name="status" style="max-width: 150px;">
                            <option value="all">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="review">En Revisión</option>
                            <option value="production">En Producción</option>
                            <option value="ready">Listo</option>
                            <option value="shipped">Enviado</option>
                            <option value="paid">Pagado</option>
                            <option value="delivered">Entregado</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                        <button type="submit" class="btn btn-danger" style="background-color: #e53935; white-space: nowrap;">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Pagos -->
    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #e53935;"></i>
            <p class="text-muted mt-3 mb-0">No hay órdenes para mostrar</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($orders as $order)
                @foreach($order->items as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                            <!-- Header con estado -->
                            <div class="card-header" style="background: linear-gradient(135deg, {{ $order->status === 'paid' || $order->status === 'delivered' ? '#28a745' : ($order->status === 'pending' ? '#ffc107' : '#dc3545') }} 0%, {{ $order->status === 'paid' || $order->status === 'delivered' ? '#20c997' : ($order->status === 'pending' ? '#ff9800' : '#c82333') }} 100%); border: none;">
                                <div class="d-flex align-items-center justify-content-between text-white">
                                    <small class="fw-bold">Orden #{{ $order->order_number }}</small>
                                    <span class="badge bg-white fw-bold" style="color: {{ $order->status === 'paid' || $order->status === 'delivered' ? '#28a745' : ($order->status === 'pending' ? '#ffc107' : '#dc3545') }}">
                                        @switch($order->status)
                                            @case('paid')
                                            @case('delivered')
                                                PAGADO
                                                @break
                                            @case('pending')
                                            @case('review')
                                                PENDIENTE
                                                @break
                                            @case('production')
                                            @case('ready')
                                            @case('shipped')
                                                EN PROCESO
                                                @break
                                            @case('cancelled')
                                                CANCELADO
                                                @break
                                            @default
                                                {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Producto -->
                                <div class="mb-3 pb-3 border-bottom">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-box" style="color: #e53935;"></i> Producto Comprado
                                    </h6>
                                    <p class="mb-1 fw-semibold">{{ $item->product->name ?? 'Producto sin nombre' }}</p>
                                    <small class="text-muted">Cantidad: {{ $item->quantity }}</small><br>
                                    <small class="text-muted">Categoría: {{ $item->product->category ?? 'N/A' }}</small>
                                </div>

                                <!-- Información de pago -->
                                <div class="mb-3 pb-3 border-bottom">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-info-circle" style="color: #e53935;"></i> Información
                                    </h6>
                                    <div class="row g-2 small">
                                        <div class="col-6">
                                            <p class="text-muted mb-1">Cliente</p>
                                            <p class="fw-semibold mb-0">{{ $order->user->name ?? 'No especificado' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted mb-1">Monto</p>
                                            <p class="fw-semibold mb-0">${{ number_format($item->total_price, 2) }}</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-muted mb-1">Fecha</p>
                                            <p class="fw-semibold mb-0">{{ $order->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-2">
                                        <i class="bi bi-clipboard-check" style="color: #e53935;"></i> Estado
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $statusConfig = [
                                                'paid' => ['emoji' => '✓', 'texto' => 'Pagado', 'bg' => 'rgba(40, 167, 69, 0.1)', 'color' => '#28a745'],
                                                'delivered' => ['emoji' => '✓', 'texto' => 'Entregado', 'bg' => 'rgba(40, 167, 69, 0.1)', 'color' => '#28a745'],
                                                'pending' => ['emoji' => '⏱', 'texto' => 'Pendiente', 'bg' => 'rgba(255, 193, 7, 0.1)', 'color' => '#ffc107'],
                                                'review' => ['emoji' => '👁️', 'texto' => 'En Revisión', 'bg' => 'rgba(255, 193, 7, 0.1)', 'color' => '#ffc107'],
                                                'production' => ['emoji' => '⚙️', 'texto' => 'En Producción', 'bg' => 'rgba(0, 123, 255, 0.1)', 'color' => '#007bff'],
                                                'ready' => ['emoji' => '📦', 'texto' => 'Listo', 'bg' => 'rgba(0, 123, 255, 0.1)', 'color' => '#007bff'],
                                                'shipped' => ['emoji' => '🚚', 'texto' => 'Enviado', 'bg' => 'rgba(0, 123, 255, 0.1)', 'color' => '#007bff'],
                                                'cancelled' => ['emoji' => '✗', 'texto' => 'Cancelado', 'bg' => 'rgba(220, 53, 69, 0.1)', 'color' => '#dc3545'],
                                            ];
                                            $config = $statusConfig[$order->status] ?? ['emoji' => '?', 'texto' => ucfirst($order->status), 'bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6c757d'];
                                        @endphp
                                        <div class="badge fw-bold p-2" style="border-radius: 6px; background-color: {{ $config['bg'] }}; color: {{ $config['color'] }}">
                                            {{ $config['emoji'] }} {{ $config['texto'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light border-top">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pagos.edit', $order->id) }}" class="btn btn-sm btn-outline-primary w-100" style="color: #e53935; border-color: #e53935;">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form method="POST" action="{{ route('pagos.destroy', $order->id) }}" style="width: 100%;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este registro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
