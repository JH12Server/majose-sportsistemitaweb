@extends('layouts.app')
@section('title', 'Editar Producto')
@section('content')
<div class="container mt-4">
    <h4>Editar Producto</h4>
    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-control" required>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @if($producto->categoria_id==$categoria->id) selected @endif>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" required>
        </div>
        <div class="mb-3">
            <label>Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 