@extends('layouts.app')
@section('title', 'Configuración')
@section('content')
<div class="container mt-4">
    <h4>Configuración de Cuenta</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('configuracion.actualizar') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Contraseña actual</label>
            <input type="password" name="password_actual" class="form-control" required>
            @error('password_actual')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>Nueva contraseña</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        @if($user->role === 'admin')
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control">
                <option value="admin" @if($user->role=='admin') selected @endif>Administrador</option>
                <option value="user" @if($user->role=='user') selected @endif>Usuario</option>
            </select>
        </div>
        @endif
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection 