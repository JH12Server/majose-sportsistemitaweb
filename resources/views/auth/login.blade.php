@extends('layouts.app')

@section('title', 'Login')
@section('body-class', 'bg-login')
@section('simple', true)

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%; border-radius: 18px;">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/majose logo.png') }}" alt="Logo" class="logo mb-2" style="width: 7.5rem; height: 7.5rem;">
            <h4 class="fw-bold" style="color: var(--primary-color)">MajoseSport</h4>
            <p class="text-muted mb-0">Sistema de Gestión</p>
        </div>
        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus aria-label="Correo electrónico">
                @error('email')
                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>Las credenciales proporcionadas no son correctas.</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required aria-label="Contraseña">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar/ocultar contraseña">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>Las credenciales proporcionadas no son correctas.</div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>
            <button type="submit" class="btn btn-danger w-100 mb-2">Iniciar Sesión</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(button) {
        let input = button.previousElementSibling;
        let icon = button.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endpush

<style>
    .card {
        border-radius: 15px;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }
</style>
@endsection