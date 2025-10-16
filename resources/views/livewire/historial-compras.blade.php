<div class="container py-4">
    <h2 class="mb-4">Mi Historial de Compras</h2>
    
    @forelse($compras as $compra)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Compra #{{ $compra->id }}</h5>
                <span class="badge bg-{{ $compra->status == 'completada' ? 'success' : 'warning' }}">
                    {{ ucfirst($compra->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Total:</strong> ${{ number_format($compra->total, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Estado:</strong> {{ ucfirst($compra->status) }}</p>
                    </div>
                </div>
                
                <h6 class="mt-3">Productos:</h6>
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
                            @foreach($compra->details as $detalle)
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
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No tienes compras registradas aún.
        </div>
    @endforelse
    
    <div class="d-flex justify-content-center">
        {{ $compras->links() }}
    </div>
</div> 