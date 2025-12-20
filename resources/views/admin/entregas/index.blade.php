@extends('layouts.app')
@section('title', 'Entregas')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-truck"></i> Entregas</h4>
    </div>
    <div class="card p-3 mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto ms-auto">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Dirección</th>
                    <th>Descripción del Producto</th>
                    <th>Estado</th>
                    <th>Método de Pago</th>
                    <th class="text-center"><i class="bi bi-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($entregas as $entrega)
                    @php
                        $customerNotes = json_decode($entrega->customer_notes, true) ?? [];
                        $shippingAddress = $customerNotes['shipping']['address'] ?? 'N/A';
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
                <tr>
                    <td>{{ $entrega->user->name ?? 'N/A' }}</td>
                    <td>{{ $shippingAddress }}</td>
                    <td>
                        @forelse($entrega->items as $item)
                            <small class="d-block">{{ $item->product->name ?? 'Producto sin nombre' }}</small>
                        @empty
                            <small class="text-muted">Sin productos</small>
                        @endforelse
                    </td>
                    <td>
                        <span class="badge bg-{{ $entrega->status === 'delivered' ? 'success' : ($entrega->status === 'pending' ? 'warning' : ($entrega->status === 'cancelled' ? 'danger' : ($entrega->status === 'paid' ? 'info' : 'primary'))) }}">
                            {{ $entrega->status_label ?? ucfirst(str_replace('_', ' ', $entrega->status)) }}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">{{ $pmSpanish }}</small>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('entregas.edit', $entrega) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('entregas.destroy', $entrega) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar pedido?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay entregas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Mostrando {{ $entregas->firstItem() ?? 0 }} a {{ $entregas->lastItem() ?? 0 }} de {{ $entregas->total() }} entregas
            </div>
            <div>
                {{ $entregas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection