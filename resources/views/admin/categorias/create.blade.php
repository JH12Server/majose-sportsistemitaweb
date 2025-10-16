@extends('layouts.app')
@section('title', 'Nueva Categoría')
@section('content')
<div class="container mt-4">
    <h4>Crear Categoría</h4>
    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion') }}">
        </div>
        <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 