<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-danger d-flex align-items-center gap-2">
                <i class="bi bi-people" style="font-size: 2rem;"></i>
                Gestión de Usuarios
            </h2>
            <p class="text-muted">Crea, edita y administra los usuarios del sistema</p>
        </div>
        <div class="col-md-6 text-end">
            @if(!$showCreateForm && $editingUserId === null)
                <button wire:click="openCreateForm" class="btn btn-danger">
                    <i class="bi bi-plus-circle"></i> Nuevo Usuario
                </button>
            @endif
        </div>
    </div>

    <!-- Create/Edit Form -->
    @if($showCreateForm || $editingUserId !== null)
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0 fw-bold">
                            {{ $editingUserId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $editingUserId ? 'updateUser' : 'createUser' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nombre Completo</label>
                                        <input type="text" wire:model.defer="name" class="form-control" placeholder="Juan Pérez">
                                        @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" wire:model.defer="email" class="form-control" placeholder="juan@example.com" {{ $editingUserId ? 'disabled' : '' }}>
                                        @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                            </div>

                            @if(!$editingUserId)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Contraseña</label>
                                            <input type="password" wire:model.defer="password" class="form-control" placeholder="Mínimo 8 caracteres">
                                            @error('password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Confirmar Contraseña</label>
                                            <input type="password" wire:model.defer="password_confirmation" class="form-control" placeholder="Confirma la contraseña">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Rol</label>
                                        <select wire:model.defer="role" class="form-select">
                                            <option value="">Selecciona un rol</option>
                                            <option value="admin">Admin</option>
                                            <option value="cliente">Cliente</option>
                                            <option value="trabajador">Trabajador</option>
                                            <option value="proveedor">Proveedor</option>
                                        </select>
                                        @error('role') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="createUser|updateUser">
                                        <i class="bi bi-check"></i> {{ $editingUserId ? 'Actualizar' : 'Crear' }}
                                    </span>
                                    <span wire:loading wire:target="createUser|updateUser">
                                        <i class="spinner-border spinner-border-sm me-2"></i> Procesando...
                                    </span>
                                </button>
                                <button type="button" wire:click="{{ $editingUserId ? 'cancelEdit' : 'closeCreateForm' }}" class="btn btn-secondary">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="🔍 Buscar por nombre o email...">
        </div>
        <div class="col-md-6">
            <select wire:model="role_filter" class="form-select">
                <option value="">Todos los roles</option>
                <option value="admin">Admin</option>
                <option value="cliente">Cliente</option>
                <option value="trabajador">Trabajador</option>
                <option value="proveedor">Proveedor</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                            <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                        @else
                                            <span class="badge bg-secondary">Sin rol</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Activo</span>
                                    </td>
                                    <td>
                                        <button wire:click="editUser({{ $user->id }})" class="btn btn-sm btn-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($user->id !== Auth::id())
                                            <button wire:click="deleteUser({{ $user->id }})" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <span class="text-muted small">Es tu cuenta</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No se encontraron usuarios
                </div>
            @endif
        </div>
    </div>
</div>
