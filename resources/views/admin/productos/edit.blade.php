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
            <input type="text" name="name" class="form-control" value="{{ $producto->name }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control">{{ $producto->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="base_price" step="0.01" class="form-control" value="{{ $producto->base_price }}" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="category" class="form-control">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->nombre }}" @if($producto->category==$categoria->nombre) selected @endif>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Marca</label>
            <input type="text" name="brand" class="form-control" value="{{ $producto->brand }}">
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="is_active" class="form-control">
                <option value="1" @if($producto->is_active) selected @endif>Activo</option>
                <option value="0" @if(!$producto->is_active) selected @endif>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 