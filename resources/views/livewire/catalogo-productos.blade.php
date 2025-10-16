<div class="container py-4">
    <h2 class="mb-4">Catálogo de Productos</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Buscar..." wire:model="search">
        </div>
        <div class="col-md-4">
            <select class="form-select" wire:model="categoria">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ $producto->descripcion }}</p>
                        <span class="fw-bold text-danger mb-2">${{ number_format($producto->precio, 2) }}</span>
                        <button class="btn btn-primary mt-auto" wire:click="agregarAlCarrito({{ $producto->id }})">
                            <i class="bi bi-cart-plus"></i> Comprar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No hay productos disponibles.</div>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $productos->links() }}
    </div>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <div class="mb-3 text-end">
        @auth
            <a href="{{ route('carrito') }}" class="btn btn-primary">
                <i class="bi bi-cart"></i> Ver Carrito
            </a>
        @endauth
    </div>
</div> 