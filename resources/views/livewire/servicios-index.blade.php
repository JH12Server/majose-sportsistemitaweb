<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Gestión de Servicios</h5>
            @if($this->user && $this->user->role === 'admin')
                <button class="btn btn-primary" wire:click="showForm">
                    <i class='bx bx-plus'></i> Nuevo Servicio
                </button>
            @endif
        </div>
        <div class="card-body">
            <!-- Filtros y Búsqueda -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                        <input type="text" class="form-control" placeholder="Buscar servicios..." wire:model.live="search">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterCategoria">
                        <option value="">Todas las categorías</option>
                        @foreach($this->categorias as $categoria)
                            <option value="{{ $categoria }}">{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="filterEstado">
                        <option value="">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')" wire:click="$set('filterCategoria', '')" wire:click="$set('filterEstado', '')">
                        <i class='bx bx-refresh'></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Tabla de Servicios -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            @if($this->user && $this->user->role === 'admin')
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->id }}</td>
                                <td>
                                    @if($servicio->imagen)
                                        <img src="{{ asset('storage/' . $servicio->imagen) }}" 
                                             alt="{{ $servicio->nombre }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class='bx bx-image text-muted'></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $servicio->nombre }}</strong></td>
                                <td>{{ Str::limit($servicio->descripcion, 50) }}</td>
                                <td>
                                    @if($servicio->categoria)
                                        <span class="badge bg-info">{{ $servicio->categoria }}</span>
                                    @else
                                        <span class="text-muted">Sin categoría</span>
                                    @endif
                                </td>
                                <td>
                                    @if($servicio->precio)
                                        <span class="text-success fw-bold">${{ number_format($servicio->precio, 2) }}</span>
                                    @else
                                        <span class="text-muted">Sin precio</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $servicio->estado ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $servicio->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                @if($this->user && $this->user->role === 'admin')
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-warning" 
                                                    wire:click="showForm({{ $servicio->id }})" 
                                                    title="Editar">
                                                <i class='bx bx-edit'></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="if(confirm('¿Seguro que deseas eliminar este servicio?')) { window.livewire.emit('eliminarServicio', {{ $servicio->id }}); }" 
                                                    title="Eliminar">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $this->user && $this->user->role === 'admin' ? 8 : 7 }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class='bx bx-package' style="font-size: 3rem;"></i>
                                        <p class="mt-2">No se encontraron servicios</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($this->servicios->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $this->servicios->links() }}
                </div>
            @endif
        </div>
</div>

    <!-- Modal para formulario de servicio -->
    <div class="modal fade @if($showFormModal) show d-block @endif" 
         tabindex="-1" 
         role="dialog" 
         @if($showFormModal) style="background:rgba(0,0,0,0.5);" @endif>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($editingServicio) 
                            <i class='bx bx-edit'></i> Editar Servicio
                        @else 
                            <i class='bx bx-plus'></i> Nuevo Servicio
                        @endif
                    </h5>
                    <button type="button" class="btn-close" wire:click="hideForm" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @livewire('servicio-form', ['servicioId' => $editingServicio], key($editingServicio))
                </div>
            </div>
        </div>
    </div>
</div> 