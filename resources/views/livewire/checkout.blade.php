<div class="container py-4">
    <h2 class="mb-4">Finalizar Compra</h2>
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
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>${{ number_format($item['precio'], 2) }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <h4>Total: <span class="text-success">${{ number_format($total, 2) }}</span></h4>
    </div>
    <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-success" wire:click="procesarCompra" @if(empty($items)) disabled @endif>Confirmar y Pagar</button>
    </div>
</div> 