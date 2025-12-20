@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('content')
<div class="container mt-4">
    <h4>Nuevo Producto</h4>
    <form action="{{ route('productos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="base_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="category" class="form-control">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Marca</label>
            <input type="text" name="brand" class="form-control">
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="is_active" class="form-control">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 