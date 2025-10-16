@extends('layouts.app')
@section('title', 'Editar Entrega')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-truck"></i> Editar Entrega</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('entregas.update', $entrega->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required value="{{ old('nombre', $entrega->nombre) }}">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $entrega->direccion) }}">
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo de producto</label>
                            <input type="text" class="form-control" id="tipo_producto" name="tipo_producto" value="{{ old('tipo_producto', $entrega->tipo_producto) }}">
                        </div>
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" value="{{ old('cedula', $entrega->cedula) }}">
                        </div>
                        <div class="mb-3">
                            <label for="tipo_persona" class="form-label">Tipo de persona</label>
                            <select class="form-select" id="tipo_persona" name="tipo_persona">
                                <option value="Natural" {{ old('tipo_persona', $entrega->tipo_persona) == 'Natural' ? 'selected' : '' }}>Natural</option>
                                <option value="Jurídica" {{ old('tipo_persona', $entrega->tipo_persona) == 'Jurídica' ? 'selected' : '' }}>Jurídica</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', $entrega->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('entregas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 