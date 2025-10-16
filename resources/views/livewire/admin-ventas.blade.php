<div class="container-fluid py-4">
    <h2 class="mb-4">Panel de Administración de Ventas</h2>
    
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Ventas</h5>
                    <h3>{{ $estadisticas['total_ventas'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Ventas Hoy</h5>
                    <h3>{{ $estadisticas['ventas_hoy'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Ingresos</h5>
                    <h3>${{ number_format($estadisticas['total_ingresos'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Ingresos Hoy</h5>
                    <h3>${{ number_format($estadisticas['ingresos_hoy'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Buscar por cliente..." wire:model="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model="status">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model="fechaInicio" placeholder="Fecha inicio">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model="fechaFin" placeholder="Fecha fin">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Ventas -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventas as $venta)
                            <tr>
                                <td>#{{ $venta->id }}</td>
                                <td>{{ $venta->user->name }}</td>
                                <td>${{ number_format($venta->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $venta->status == 'completada' ? 'success' : ($venta->status == 'pendiente' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($venta->status) }}
                                    </span>
                                </td>
                                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detalleModal{{ $venta->id }}">
                                            Ver Detalles
                                        </button>
                                        <select class="form-select form-select-sm" wire:change="actualizarStatus({{ $venta->id }}, $event.target.value)">
                                            <option value="pendiente" {{ $venta->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="completada" {{ $venta->status == 'completada' ? 'selected' : '' }}>Completada</option>
                                            <option value="cancelada" {{ $venta->status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay ventas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $ventas->links() }}
            </div>
        </div>
    </div>

    <!-- Modales de Detalles -->
    @foreach($ventas as $venta)
        <div class="modal fade" id="detalleModal{{ $venta->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de Venta #{{ $venta->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Cliente:</strong> {{ $venta->user->name }}</p>
                                <p><strong>Email:</strong> {{ $venta->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Total:</strong> ${{ number_format($venta->total, 2) }}</p>
                            </div>
                        </div>
                        
                        <h6>Productos:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($venta->details as $detalle)
                                        <tr>
                                            <td>{{ $detalle->product->nombre }}</td>
                                            <td>{{ $detalle->quantity }}</td>
                                            <td>${{ number_format($detalle->price, 2) }}</td>
                                            <td>${{ number_format($detalle->price * $detalle->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div> 