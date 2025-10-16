@extends('layouts.app')
@section('title', 'Editar Categoría')
@section('content')
<div class="container mt-4">
    <h4>Editar Categoría</h4>
    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $categoria->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $categoria->descripcion }}</textarea>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="1" {{ $categoria->estado ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$categoria->estado ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 