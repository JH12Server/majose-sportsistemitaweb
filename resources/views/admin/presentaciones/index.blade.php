@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-layers"></i> Lista de Presentaciones</h5>
        <a href="{{ route('presentaciones.create') }}" class="btn btn-danger">+ Nueva Presentación</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="bi bi-tag"></i> Nombre</th>
                    <th><i class="bi bi-card-text"></i> Descripción</th>
                    <th><i class="bi bi-record-circle"></i> Estado</th>
                    <th><i class="bi bi-gear"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presentaciones as $presentacion)
                    <tr>
                        <td>{{ $presentacion->nombre }}</td>
                        <td>{{ $presentacion->descripcion ?? '-' }}</td>
                        <td>
                            @if($presentacion->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('presentaciones.edit', $presentacion->id) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('presentaciones.destroy', $presentacion->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar presentación?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay presentaciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $presentaciones->links() }}
    </div>
</div>
@endsection 