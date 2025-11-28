@extends('layouts.app')
@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Cambiar Contraseña</h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form id="passwordForm" method="POST" action="{{ route('configuracion.actualizar') }}" novalidate>
                        @csrf
                        
                        <div class="mb-4">
                            <label for="password_actual" class="form-label fw-bold">
                                <i class="fas fa-lock me-2"></i>Contraseña Actual
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password_actual') is-invalid @enderror" 
                                       id="password_actual" 
                                       name="password_actual" 
                                       required
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_actual">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password_actual')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-key me-2"></i>Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       minlength="8"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text text-muted">
                                <small>La contraseña debe tener al menos 8 caracteres.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-check-double me-2"></i>Confirmar Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       minlength="8"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div id="passwordMatch" class="form-text d-none">
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Las contraseñas coinciden</small>
                            </div>
                            <div id="passwordMismatch" class="form-text d-none">
                                <small class="text-danger"><i class="fas fa-times-circle me-1"></i>Las contraseñas no coinciden</small>
                            </div>
                        </div>

                        @if($user->role === 'admin')
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold">
                                <i class="fas fa-user-tag me-2"></i>Rol
                            </label>
                            <select name="role" id="role" class="form-select form-select-lg">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </div>
                        @endif

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Actualizar Contraseña
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const form = document.getElementById('passwordForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');
    const passwordMismatch = document.getElementById('passwordMismatch');
    const submitBtn = document.getElementById('submitBtn');
    
    // Función para mostrar/ocultar contraseña
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Función para verificar si las contraseñas coinciden
    function checkPasswordsMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password === '' || confirmPassword === '') {
            passwordMatch.classList.add('d-none');
            passwordMismatch.classList.add('d-none');
            return false;
        }
        
        if (password === confirmPassword) {
            passwordMatch.classList.remove('d-none');
            passwordMismatch.classList.add('d-none');
            return true;
        } else {
            passwordMatch.classList.add('d-none');
            passwordMismatch.classList.remove('d-none');
            return false;
        }
    }
    
    // Validar en tiempo real
    [passwordInput, confirmPasswordInput].forEach(input => {
        input.addEventListener('input', function() {
            if (passwordInput.value && confirmPasswordInput.value) {
                checkPasswordsMatch();
            }
        });
    });
    
    // Validar antes de enviar el formulario
    form.addEventListener('submit', function(event) {
        if (!checkPasswordsMatch()) {
            event.preventDefault();
            passwordMismatch.classList.remove('d-none');
            confirmPasswordInput.focus();
        }
    });
    
    // Deshabilitar el botón de enviar si hay campos inválidos
    form.addEventListener('input', function() {
        const isFormValid = form.checkValidity();
        submitBtn.disabled = !isFormValid || !checkPasswordsMatch();
    });
    
    // Validación inicial
    checkPasswordsMatch();
});
</script>
@endpush