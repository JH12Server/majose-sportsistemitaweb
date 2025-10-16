@extends('layouts.app')
@section('title', 'Productos')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lista de Productos</h4>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
    </div>
    <form method="GET" action="" class="mb-3 d-flex justify-content-end">
        <input type="text" name="search" class="form-control w-auto me-2" placeholder="Buscar producto..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-secondary">Buscar</button>
    </form>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Presentación</th>
                <th>Categorías</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productos as $producto)
            <tr>
                <td>{{ $producto->id }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->descripcion ?? '-' }}</td>
                <td>{{ $producto->presentacion ?? 'Unidad' }}</td>
                <td>{{ $producto->category->nombre ?? '-' }}</td>
                <td>
                    @if($producto->estado)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-danger">Inactivo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No hay productos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $productos->links() }}
    </div>
</div>
@endsection 