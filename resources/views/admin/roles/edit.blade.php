@extends('layouts.app')
@section('title', 'Editar Rol')
@section('content')
<div class="container mt-4">
    <h4>Editar Rol</h4>
    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="description" class="form-control" value="{{ $role->description }}">
        </div>
        <div class="mb-3">
            <label>Tipo</label>
            <select name="type" class="form-control">
                <option value="custom" @if($role->type === 'custom' || empty($role->type)) selected @endif>Personalizado</option>
                <option value="system" @if($role->type === 'system') selected @endif>Sistema</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
