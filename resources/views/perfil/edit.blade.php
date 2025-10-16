@extends('layouts.app')
@section('title', 'Editar Perfil')
@section('content')
<div class="container mt-4">
    <h4>Editar Perfil</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label>Foto de perfil</label><br>
            @if($user->foto)
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto de perfil" style="width:80px; height:80px; object-fit:cover; border-radius:50%;">
            @endif
            <input type="file" name="foto" class="form-control mt-2">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection 