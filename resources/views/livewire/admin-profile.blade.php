<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-danger d-flex align-items-center gap-2">
                <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                Mi Perfil
            </h2>
            <p class="text-muted">Administra tu información de perfil</p>
        </div>
    </div>

    <div class="row">
        <!-- Avatar Section -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    @if($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ffc107;">
                        <small class="text-warning d-block mb-2"><i class="bi bi-info-circle me-1"></i>Preview de la nueva imagen</small>
                    @else
                        <img src="{{ $avatar_url }}" alt="Avatar" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #e53935;">
                    @endif
                    
                    <h5 class="fw-bold mt-3">{{ Auth::user()->name }}</h5>
                    <p class="text-muted small">{{ Auth::user()->email }}</p>
                    
                    <!-- Upload Avatar Form -->
                    <div class="mt-4">
                        <form wire:submit.prevent="uploadAvatar">
                            <label class="form-label fw-semibold small">Selecciona una imagen</label>
                            <input type="file" wire:model="avatar" accept="image/*" class="form-control form-control-sm mb-2" @if(!$avatar) id="avatarInput" @endif>
                            <small class="text-muted d-block mb-2">Máximo 2MB (JPEG, PNG, JPG, GIF)</small>
                            @if($avatar)
                                <small class="text-success d-block mb-2"><i class="bi bi-check-circle me-1"></i>Imagen seleccionada</small>
                            @endif
                            @error('avatar')
                                <small class="text-danger d-block mb-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                            @if($avatar)
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-danger flex-grow-1" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="uploadAvatar">
                                            <i class="bi bi-cloud-arrow-up"></i> Guardar Avatar
                                        </span>
                                        <span wire:loading wire:target="uploadAvatar">
                                            <i class="spinner-border spinner-border-sm me-2"></i> Subiendo...
                                        </span>
                                    </button>
                                    <button type="button" wire:click="clearAvatar" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="document.getElementById('avatarInput').click()">
                                    <i class="bi bi-cloud-arrow-up"></i> Seleccionar Imagen
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Info Section -->
        <div class="col-md-8">
            <!-- Edit Name -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person"></i> Información Personal</h6>
                    @if(!$edit_mode)
                        <button type="button" wire:click="$set('edit_mode', true)" class="btn btn-sm btn-light">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($edit_mode)
                        <form wire:submit.prevent="updateProfile">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" wire:model.defer="name" class="form-control @error('name') is-invalid @enderror" placeholder="Tu nombre">
                                @error('name')
                                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" value="{{ Auth::user()->email }}" class="form-control" disabled>
                                <small class="text-muted">El email no puede ser modificado</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="updateProfile"><i class="bi bi-check"></i> Guardar</span>
                                    <span wire:loading wire:target="updateProfile"><i class="spinner-border spinner-border-sm me-2"></i></span>
                                </button>
                                <button type="button" wire:click="$set('edit_mode', false)" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </form>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Nombre</label>
                            <p class="fs-5 fw-bold">{{ $name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">Email</label>
                            <p class="fs-5">{{ Auth::user()->email }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-lock"></i> Seguridad</h6>
                    @if(!$change_password_mode)
                        <button type="button" wire:click="$set('change_password_mode', true)" class="btn btn-sm btn-light">
                            <i class="bi bi-pencil"></i> Cambiar Contraseña
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($change_password_mode)
                        <form wire:submit.prevent="changePassword">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Contraseña Actual</label>
                                <input type="password" wire:model.defer="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Tu contraseña actual">
                                @error('current_password')
                                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nueva Contraseña</label>
                                <input type="password" wire:model.defer="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Nueva contraseña (mín. 8 caracteres)">
                                @error('new_password')
                                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirmar Contraseña</label>
                                <input type="password" wire:model.defer="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror" placeholder="Confirma la nueva contraseña">
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback" style="display: block;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="changePassword"><i class="bi bi-check"></i> Actualizar</span>
                                    <span wire:loading wire:target="changePassword"><i class="spinner-border spinner-border-sm me-2"></i></span>
                                </button>
                                <button type="button" wire:click="$set('change_password_mode', false)" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </form>
                    @else
                        <p class="text-muted">Última actualización de contraseña hace algunos días</p>
                        <small class="text-muted d-block">Recomendamos cambiar tu contraseña regularmente por seguridad</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
