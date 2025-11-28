@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('content')
<div class="container mt-4">
    <h4>Editar Usuario</h4>
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ $usuario->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $usuario->email }}" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control" required>
                <option value="user" @if($usuario->role=='user') selected @endif>Cliente</option>
                <option value="worker" @if($usuario->role=='worker') selected @endif>Trabajador</option>
                <option value="provider" @if($usuario->role=='provider') selected @endif>Proveedor</option>
                <option value="admin" @if($usuario->role=='admin') selected @endif>Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection 