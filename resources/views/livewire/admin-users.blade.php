<div class="container-fluid py-4">
    <!-- Vista de edición dedicada -->
    @if($editingUserId !== null)
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-pencil-square"></i> Editar Usuario
                        </h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateUser">
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
                                        <input type="email" wire:model.defer="email" class="form-control" placeholder="juan@example.com">
                                        @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nueva Contraseña (opcional)</label>
                                        <input type="password" wire:model.defer="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                                        @error('password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Confirmar Nueva Contraseña</label>
                                        <input type="password" wire:model.defer="password_confirmation" class="form-control" placeholder="Confirma la nueva contraseña">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Rol</label>
                                        <select wire:model.defer="role" class="form-select">
                                            <option value="">Selecciona un rol</option>
                                            @foreach($roles as $r)
                                                @php($lr = strtolower($r))
                                                @switch($lr)
                                                    @case('user')
                                                        <option value="{{ $r }}">Cliente</option>
                                                        @break
                                                    @case('worker')
                                                        <option value="{{ $r }}">Trabajador</option>
                                                        @break
                                                    @case('provider')
                                                        <option value="{{ $r }}">Proveedor</option>
                                                        @break
                                                    @case('admin')
                                                        <option value="{{ $r }}">Administrador</option>
                                                        @break
                                                    @case('designer')
                                                        <option value="{{ $r }}">Diseñador</option>
                                                        @break
                                                    @case('embroiderer')
                                                        <option value="{{ $r }}">Bordador</option>
                                                        @break
                                                    @case('delivery_manager')
                                                        <option value="{{ $r }}">Encargado Entregas</option>
                                                        @break
                                                    @case('supervisor')
                                                        <option value="{{ $r }}">Supervisor</option>
                                                        @break
                                                    @case('customer')
                                                        <option value="{{ $r }}">Cliente</option>
                                                        @break
                                                    @default
                                                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                                                @endswitch
                                            @endforeach
                                        </select>
                                        @error('role') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="updateUser">
                                        <i class="bi bi-check"></i> Actualizar
                                    </span>
                                    <span wire:loading wire:target="updateUser">
                                        <i class="spinner-border spinner-border-sm me-2"></i> Procesando...
                                    </span>
                                </button>
                                <button type="button" wire:click="cancelEdit" class="btn btn-secondary">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Vista de listado -->
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
                @if(!$showCreateForm)
                    <button wire:click="openCreateForm" class="btn btn-danger">
                        <i class="bi bi-plus-circle"></i> Nuevo Usuario
                    </button>
                @endif
            </div>
        </div>

        <!-- Create Form -->
        @if($showCreateForm)
            <div class="row mb-4">
                <div class="col">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0 fw-bold">Crear Nuevo Usuario</h6>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="createUser">
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
                                            <input type="email" wire:model.defer="email" class="form-control" placeholder="juan@example.com">
                                            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Rol</label>
                                            <select wire:model.defer="role" class="form-select">
                                                <option value="">Selecciona un rol</option>
                                                <option value="admin">Administrador</option>
                                                <option value="user">Cliente</option>
                                                <option value="worker">Trabajador</option>
                                                <option value="provider">Proveedor</option>
                                            </select>
                                            @error('role') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="createUser">
                                            <i class="bi bi-check"></i> Crear
                                        </span>
                                        <span wire:loading wire:target="createUser">
                                            <i class="spinner-border spinner-border-sm me-2"></i> Procesando...
                                        </span>
                                    </button>
                                    <button type="button" wire:click="closeCreateForm" class="btn btn-secondary">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters (styled like productos) -->
        <form class="mb-3 d-flex justify-content-end align-items-center gap-2" wire:submit.prevent="applyFilters">
            <input type="text" wire:model.debounce.500ms="search" class="form-control w-50 me-2" placeholder="Buscar por nombre, email o rol...">
            <select wire:model="role_filter" class="form-select w-auto me-2">
                <option value="">Todos los roles</option>
                @foreach($roles as $r)
                    @php($lr = strtolower($r))
                    @switch($lr)
                        @case('user')
                            <option value="{{ $r }}">Cliente</option>
                            @break
                        @case('worker')
                            <option value="{{ $r }}">Trabajador</option>
                            @break
                        @case('provider')
                            <option value="{{ $r }}">Proveedor</option>
                            @break
                        @case('admin')
                            <option value="{{ $r }}">Administrador</option>
                            @break
                        @case('designer')
                            <option value="{{ $r }}">Diseñador</option>
                            @break
                        @case('embroiderer')
                            <option value="{{ $r }}">Bordador</option>
                            @break
                        @case('delivery_manager')
                            <option value="{{ $r }}">Encargado Entregas</option>
                            @break
                        @case('supervisor')
                            <option value="{{ $r }}">Supervisor</option>
                            @break
                        @case('customer')
                            <option value="{{ $r }}">Cliente</option>
                            @break
                        @default
                            <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                    @endswitch
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
        </form>

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
                                            @switch(strtolower($user->role))
                                                @case('user')
                                                @case('customer')
                                                    <span class="badge bg-info">Cliente</span>
                                                    @break
                                                @case('worker')
                                                    <span class="badge bg-info">Trabajador</span>
                                                    @break
                                                @case('provider')
                                                    <span class="badge bg-info">Proveedor</span>
                                                    @break
                                                @case('admin')
                                                    <span class="badge bg-info">Administrador</span>
                                                    @break
                                                @case('designer')
                                                    <span class="badge bg-info">Diseñador</span>
                                                    @break
                                                @case('embroiderer')
                                                    <span class="badge bg-info">Bordador</span>
                                                    @break
                                                @case('delivery_manager')
                                                    <span class="badge bg-info">Encargado Entregas</span>
                                                    @break
                                                @case('supervisor')
                                                    <span class="badge bg-info">Supervisor</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                                            @endswitch
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
    @endif
</div>
