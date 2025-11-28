@extends('layouts.app')
@section('title', ucfirst($type ?? 'Roles'))
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0"><i class="bi bi-person-badge"></i>
                @if(($type ?? 'all') === 'system') Roles del Sistema
                @elseif(($type ?? 'all') === 'custom') Roles Personalizados
                @else Roles
                @endif
            </h4>
            <nav class="mt-2">
                <a href="{{ route('roles.index', array_merge(request()->all(), ['type' => 'all'])) }}" class="btn btn-sm btn-link {{ ($type ?? 'all') === 'all' ? 'text-decoration-underline' : '' }}">Todos</a>
                <a href="{{ route('roles.index', array_merge(request()->all(), ['type' => 'system'])) }}" class="btn btn-sm btn-link {{ ($type ?? '') === 'system' ? 'text-decoration-underline' : '' }}">Sistema</a>
                <a href="{{ route('roles.index', array_merge(request()->all(), ['type' => 'custom'])) }}" class="btn btn-sm btn-link {{ ($type ?? '') === 'custom' ? 'text-decoration-underline' : '' }}">Personalizados</a>
            </nav>
        </div>
        <a href="{{ route('roles.create', ['type' => ($type === 'system' ? 'system' : 'custom')]) }}" class="btn btn-primary"><i class="bi bi-plus"></i> Nuevo Rol</a>
    </div>

    <div class="card p-3 mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto ms-auto">
                <input type="text" name="search" class="form-control" placeholder="Buscar roles..." value="{{ request('search') }}">
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
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-center"><i class="bi bi-gear"></i></th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar rol?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No hay roles registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
            <div>Mostrando {{ $roles->firstItem() ?? 0 }} a {{ $roles->lastItem() ?? 0 }} de {{ $roles->total() }} roles</div>
            <div>{{ $roles->links() }}</div>
        </div>
    </div>
</div>
@endsection
