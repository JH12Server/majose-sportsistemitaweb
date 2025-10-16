@extends('layouts.app')
@section('content')
<div class="container">
    <h4>Crear Presentación</h4>
    <form action="{{ route('presentaciones.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
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
        <a href="{{ route('presentaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 