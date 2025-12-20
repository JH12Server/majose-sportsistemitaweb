@extends('layouts.app')

@section('title', 'Editar Pago')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ route('pagos.verificacion') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <div>
                    <h1 class="h3 mb-0 fw-bold" style="color: #e53935">
                        <i class="bi bi-pencil"></i> Editar Pago
                    </h1>
                    <p class="text-muted mb-0">Orden #{{ $order->order_number }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Formulario -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('pagos.update', $order->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Estado del pago -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">
                                <i class="bi bi-shield-check" style="color: #e53935;"></i> Estado del Pedido
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">Selecciona un estado</option>
                                <optgroup label="Estados de Pago">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pagado</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </optgroup>
                                <optgroup label="Estados de Producción">
                                    <option value="review" {{ $order->status === 'review' ? 'selected' : '' }}>En Revisión</option>
                                    <option value="production" {{ $order->status === 'production' ? 'selected' : '' }}>En Producción</option>
                                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Listo para Entrega</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                                </optgroup>
                            </select>
                            @error('status')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas del cliente -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">
                                <i class="bi bi-chat-left-text" style="color: #e53935;"></i> Notas del Cliente
                            </label>
                            @if($order->customer_notes)
                                @php
                                    // Intentar decodificar JSON si es necesario
                                    $notasDecodificadas = json_decode($order->customer_notes, true);
                                    
                                    // Mapeo de traducciones
                                    $traducciones = [
                                        'billing' => 'Facturación',
                                        'shipping' => 'Envío',
                                        'first_name' => 'Nombre',
                                        'last_name' => 'Apellido',
                                        'email' => 'Correo',
                                        'phone' => 'Teléfono',
                                        'address' => 'Dirección',
                                        'city' => 'Ciudad',
                                        'state' => 'Provincia',
                                        'postal_code' => 'Código Postal',
                                        'country' => 'País',
                                        'payment_method' => 'Método de Pago',
                                        'cash' => 'Efectivo',
                                        'card' => 'Tarjeta',
                                        'paypal' => 'PayPal',
                                        'transfer' => 'Transferencia'
                                    ];
                                @endphp

                                @if(is_array($notasDecodificadas))
                                    <div class="p-3 mb-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #e53935;">
                                        @foreach($notasDecodificadas as $clave => $valor)
                                            @if(is_array($valor))
                                                <div class="mb-4">
                                                    <p class="fw-bold mb-3 p-2" style="color: #e53935; background-color: white; border-radius: 6px;">
                                                        {{ $traducciones[$clave] ?? ucfirst(str_replace('_', ' ', $clave)) }}
                                                    </p>
                                                    <div style="background-color: white; padding: 16px; border-radius: 6px;">
                                                        @foreach($valor as $k => $v)
                                                            <div class="mb-3 pb-3 border-bottom">
                                                                <small class="text-muted d-block mb-1" style="font-size: 0.85rem;">
                                                                    {{ $traducciones[$k] ?? ucfirst(str_replace('_', ' ', $k)) }}
                                                                </small>
                                                                <span class="fw-semibold" style="font-size: 0.95rem;">{{ $v }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <p class="text-muted small mb-1">{{ $traducciones[$clave] ?? ucfirst(str_replace('_', ' ', $clave)) }}</p>
                                                    <p class="fw-semibold mb-0">{{ $traducciones[$valor] ?? $valor }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>{{ $order->customer_notes }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-secondary" role="alert">
                                    <i class="bi bi-inbox me-2"></i> No hay notas del cliente
                                </div>
                            @endif
                        </div>

                        <!-- Notas internas -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-2">
                                <i class="bi bi-info-circle" style="color: #e53935;"></i> Notas Internas
                            </label>
                            <textarea name="internal_notes" class="form-control" rows="3" placeholder="Notas internas (solo visible para el equipo)">{{ $order->internal_notes }}</textarea>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger" style="background-color: #e53935;">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('pagos.verificacion') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel de información -->
        <div class="col-lg-4">
            <!-- Información de la orden -->
            <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 2px solid #e53935;">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-receipt" style="color: #e53935;"></i> Información de la Orden
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Número de orden</p>
                        <p class="fw-semibold mb-0">#{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Cliente</p>
                        <p class="fw-semibold mb-0">{{ $order->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Email</p>
                        <p class="fw-semibold mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Monto total</p>
                        <p class="fw-semibold mb-0" style="color: #e53935; font-size: 1.2rem;">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Fecha de orden</p>
                        <p class="fw-semibold mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

                <!-- Productos de la orden -->
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 2px solid #e53935;">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-box" style="color: #e53935;"></i> Productos
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="mb-3 pb-3 border-bottom">
                            <p class="fw-semibold mb-1">{{ $item->product->name ?? 'Producto sin nombre' }}</p>
                            <div class="row g-2 small text-muted">
                                <div class="col-6">
                                    <p class="mb-0">Cantidad: <strong>{{ $item->quantity }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-0">Precio: <strong>${{ number_format($item->unit_price, 2) }}</strong></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
