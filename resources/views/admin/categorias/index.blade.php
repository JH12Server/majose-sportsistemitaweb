@extends('layouts.app')
@section('title', 'Categorías')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex align-items-center gap-2">
            <h2 class="mb-0" style="color:#111;font-weight:bold;">
                <i class="bi bi-folder" style="font-size:2rem;color:#111;"></i> Categorias
            </h2>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <span class="fw-bold" style="color:#111;"><i class="bi bi-table"></i> Lista de Categorías</span>
            <a href="{{ route('categorias.create') }}" class="btn btn-danger"><i class="bi bi-plus"></i> Nueva Categoría</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion ?? '-' }}</td>
                            <td>
                                @if($categoria->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta categoría?')"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No hay categorías registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $categorias->links() }}
        </div>
    </div>
</div>
@endsection