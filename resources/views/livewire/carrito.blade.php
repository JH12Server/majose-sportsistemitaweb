<div class="container py-4">
    <h2 class="mb-4">Mi Carrito</h2>
    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>${{ number_format($item['precio'], 2) }}</td>
                        <td>
                            <input type="number" min="1" wire:change="actualizarCantidad({{ $item['id'] }}, $event.target.value)" value="{{ $item['cantidad'] }}" class="form-control" style="width:80px;">
                        </td>
                        <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" wire:click="eliminar({{ $item['id'] }})">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">El carrito está vacío.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <h4>Total: <span class="text-success">${{ number_format($total, 2) }}</span></h4>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-primary" wire:click="checkout" @if(empty($items)) disabled @endif>Finalizar Compra</button>
    </div>
</div> 