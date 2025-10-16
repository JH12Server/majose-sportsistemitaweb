@extends('layouts.app')
@section('title', 'Ventas')
@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center mb-3">
        <h4><i class="bi bi-cart-plus"></i> Ventas Totales</h4>
    </div>
    <form method="GET" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label>Fecha de inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="{{ $fecha_inicio }}">
        </div>
        <div class="col-md-3">
            <label>Fecha de fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="{{ $fecha_fin }}">
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-4 text-end">
            <div class="card p-2 mb-2">
                <div class="fw-bold text-center">Monto Total de Ventas</div>
                <div class="fs-4 text-center">$ {{ number_format($monto_total, 2) }}</div>
                <div class="small text-center">Desde <b>{{ \Carbon\Carbon::parse($fecha_inicio)->format('d-m-Y') }}</b> Hasta <b>{{ \Carbon\Carbon::parse($fecha_fin)->format('d-m-Y') }}</b></div>
            </div>
        </div>
    </form>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('ventas.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i></a>
    </div>
    <div class="card p-3 mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto ms-auto">
                <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
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
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>Fecha y hora</th>
                    <th>Vendedor</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th class="text-center"><i class="bi bi-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>#{{ $venta->id }}</td>
                    <td>{{ $venta->user->name ?? '-' }}</td>
                    <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $venta->user->name ?? '-' }}</td>
                    <td>${{ number_format($venta->monto, 2) }}</td>
                    <td>
                        @if($venta->estado === 'activo' || $venta->estado === 'completado')
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar venta?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No data available in table</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Showing {{ $ventas->firstItem() ?? 0 }} to {{ $ventas->lastItem() ?? 0 }} of {{ $ventas->total() }} entries
            </div>
            <div>
                {{ $ventas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 