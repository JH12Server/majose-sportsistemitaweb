@extends('layouts.app')
@section('content')
<div class="container">
    <h4>Editar Marca</h4>
    <form action="{{ route('marcas.update', $marca->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $marca->nombre) }}" required>
        </div>
        <div class="form-group mb-3">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion', $marca->descripcion) }}">
        </div>
        <div class="form-group mb-3">
            <label for="estado">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value="1" {{ $marca->estado ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$marca->estado ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('marcas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 