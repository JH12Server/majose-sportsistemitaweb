@extends('layouts.app')
@section('title', 'Entregas')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-truck"></i> Entregas</h4>
        <a href="{{ route('entregas.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Nueva Entrega</a>
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
                    <th>Tipo de producto</th>
                    <th>Cédula</th>
                    <th>Tipo de persona</th>
                    <th>Estado</th>
                    <th class="text-center"><i class="bi bi-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($entregas as $entrega)
                <tr>
                    <td>{{ $entrega->nombre }}</td>
                    <td>{{ $entrega->direccion }}</td>
                    <td>{{ $entrega->tipo_producto }}</td>
                    <td>{{ $entrega->cedula }}</td>
                    <td>{{ $entrega->tipo_persona }}</td>
                    <td>
                        @if($entrega->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('entregas.edit', $entrega) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('entregas.destroy', $entrega) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar entrega?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No hay entregas registradas.</td>
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