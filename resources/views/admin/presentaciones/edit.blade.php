@extends('layouts.app')
@section('title', 'Editar Presentación')
@section('content')
<div class="container mt-4">
    <h4>Editar Presentación</h4>
    <form action="{{ route('presentaciones.update', $presentacion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $presentacion->nombre }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ $presentacion->descripcion }}</textarea>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="1" {{ $presentacion->estado ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$presentacion->estado ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('presentaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 