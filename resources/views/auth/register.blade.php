@extends('layouts.app')

@section('title', 'Registro')
@section('body-class', 'bg-login')
@section('simple', true)

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%; border-radius: 18px;">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/majose logo.png') }}" alt="Logo" class="logo mb-2" style="width: 7.5rem; height: 7.5rem;">
            <h4 class="fw-bold" style="color: var(--primary-color)">MajoseSport</h4>
            <p class="text-muted mb-0">Crear Cuenta</p>
        </div>
        <form method="POST" action="{{ route('register') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Nombre</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required aria-label="Nombre">
                @error('name')
                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ str_contains($message, 'required') ? 'El nombre es requerido.' : (str_contains($message, 'max') ? 'El nombre no puede exceder 255 caracteres.' : $message) }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Correo Electrónico</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required aria-label="Correo electrónico">
                @error('email')
                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ str_contains($message, 'required') ? 'El email es requerido.' : (str_contains($message, 'email') ? 'Por favor ingresa un email válido.' : (str_contains($message, 'unique') ? 'Este email ya está registrado.' : $message)) }}</div>
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
                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ str_contains($message, 'required') ? 'La contraseña es requerida.' : (str_contains($message, 'min') ? 'La contraseña debe tener al menos 8 caracteres.' : (str_contains($message, 'confirmed') ? 'Las contraseñas no coinciden.' : $message)) }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required aria-label="Confirmar contraseña">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword(this)" tabindex="-1" aria-label="Mostrar/ocultar contraseña">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-danger w-100 mb-2">Registrarse</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">¿Ya tienes cuenta? <span class="fw-semibold" style="color: var(--primary-color)">Inicia Sesión</span></a>
        </div>
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
@endsection