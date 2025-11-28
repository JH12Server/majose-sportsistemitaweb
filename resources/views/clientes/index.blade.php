@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-people"></i> Clientes</h4>
        <a href="{{ route('usuarios.create', ['role' => 'user']) }}" class="btn btn-primary"><i class="bi bi-plus"></i> Nuevo Cliente</a>
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
                    <th>Nombres y Apellidos</th>
                    <th>Dirección</th>
                    <th>Cédula</th>
                    <th>Tipo de persona</th>
                    <th>Estado</th>
                    <th class="text-center"><i class="bi bi-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->direccion ?? '-' }}</td>
                    <td>{{ $user->cedula ?? '-' }}</td>
                    <td>{{ $user->tipo_persona ?? 'Natural' }}</td>
                    <td>
                        @if($user->activo ?? true)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('usuarios.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cliente?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay clientes registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                Mostrando {{ $users->firstItem() ?? 0 }} a {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} clientes
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection