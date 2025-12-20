@extends('layouts.app')
@section('title', 'Editar Pedido')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @php
                $customerNotes = json_decode($entrega->customer_notes, true) ?? [];
                $billingInfo = $customerNotes['billing'] ?? [];
                $shippingInfo = $customerNotes['shipping'] ?? [];
                $paymentMethod = $customerNotes['payment_method'] ?? null;
                $pmSpanish = 'N/A';
                if ($paymentMethod) {
                    switch ($paymentMethod) {
                        case 'cash': $pmSpanish = 'Efectivo'; break;
                        case 'credit_card': $pmSpanish = 'Tarjeta'; break;
                        case 'paypal': $pmSpanish = 'PayPal'; break;
                        default: $pmSpanish = ucfirst(str_replace('_', ' ', $paymentMethod)); break;
                    }
                }
            @endphp
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Editar Pedido #{{ $entrega->order_number }}</h5>
                </div>
                <div class="card-body">
                    <!-- Información del cliente y envío -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Información del Cliente</h6>
                            <p><strong>Nombre:</strong> {{ $entrega->user->name ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $entrega->user->email ?? 'N/A' }}</p>
                            <p><strong>Número de Pedido:</strong> {{ $entrega->order_number }}</p>
                            <p><strong>Total:</strong> ${{ number_format($entrega->total_amount, 2) }}</p>
                            <p><strong>Fecha:</strong> {{ $entrega->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Información de Envío</h6>
                            <p><strong>Nombre:</strong> {{ $shippingInfo['first_name'] ?? '' }} {{ $shippingInfo['last_name'] ?? '' }}</p>
                            <p><strong>Dirección:</strong> {{ $shippingInfo['address'] ?? 'N/A' }}</p>
                            <p><strong>Ciudad:</strong> {{ $shippingInfo['city'] ?? '' }}</p>
                            <p><strong>Provincia:</strong> {{ $shippingInfo['state'] ?? '' }}</p>
                            <p><strong>Código Postal:</strong> {{ $shippingInfo['postal_code'] ?? '' }}</p>
                            <p><strong>País:</strong> {{ $shippingInfo['country'] ?? '' }}</p>
                            <p><strong>Método de Pago:</strong> <span class="badge bg-info">{{ $pmSpanish }}</span></p>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-3">Productos del Pedido</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($entrega->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Producto sin nombre' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>${{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Sin productos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Formulario de edición de estado -->
                    <form method="POST" action="{{ route('entregas.update', $entrega->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Estado del Pedido</label>
                            <select class="form-select form-select-lg" id="status" name="status" required>
                                <option value="">Seleccionar estado...</option>
                                <option value="pending" {{ $entrega->status === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="review" {{ $entrega->status === 'review' ? 'selected' : '' }}>En Revisión</option>
                                <option value="production" {{ $entrega->status === 'production' ? 'selected' : '' }}>En Producción</option>
                                <option value="ready" {{ $entrega->status === 'ready' ? 'selected' : '' }}>Listo para Entrega</option>
                                <option value="shipped" {{ $entrega->status === 'shipped' ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ $entrega->status === 'delivered' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelled" {{ $entrega->status === 'cancelled' ? 'selected' : '' }}>Cancelado / Devuelto</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('entregas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection