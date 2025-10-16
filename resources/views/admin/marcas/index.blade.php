@extends('layouts.app')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5><i class="bi bi-tags" style="color:#e53935;"></i> Lista de Marcas</h5>
        <a href="{{ route('marcas.create') }}" class="btn btn-danger">+ Nueva Marca</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" style="background:#fff;">
            <thead>
                <tr>
                    <th><i class="bi bi-tag"></i> Nombre</th>
                    <th><i class="bi bi-card-text"></i> Descripción</th>
                    <th><i class="bi bi-record-circle"></i> Estado</th>
                    <th><i class="bi bi-gear"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($marcas as $marca)
                    <tr>
                        <td><i class="bi bi-tag" style="color:#e53935;"></i> {{ $marca->nombre }}</td>
                        <td>{{ $marca->descripcion ?? '-' }}</td>
                        <td>
                            @if($marca->estado)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('marcas.edit', $marca->id) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Eliminar marca?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay marcas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $marcas->links() }}
    </div>
</div>
@endsection 