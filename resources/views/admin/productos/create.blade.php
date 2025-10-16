@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('content')
<div class="container mt-4">
    <h4>Nuevo Producto</h4>
    <form action="{{ route('productos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Crear</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 