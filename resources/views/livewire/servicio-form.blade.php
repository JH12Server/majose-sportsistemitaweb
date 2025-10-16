<div>
    {{-- Success is as dangerous as failure. --}}

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Servicio <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                           id="nombre" wire:model.defer="nombre" required 
                           placeholder="Ej: Sublimación de Camisetas">
                    @error('nombre') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <input type="text" class="form-control @error('categoria') is-invalid @enderror" 
                           id="categoria" wire:model.defer="categoria" 
                           placeholder="Ej: Ropa, Accesorios, etc.">
                    @error('categoria') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                      id="descripcion" wire:model.defer="descripcion" rows="3"
                      placeholder="Describe el servicio..."></textarea>
            @error('descripcion') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                               id="precio" wire:model.defer="precio" 
                               placeholder="0.00">
                    </div>
                    @error('precio') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select @error('estado') is-invalid @enderror" 
                            id="estado" wire:model.defer="estado">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    @error('estado') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">
                Imagen del Servicio 
                @if(!$servicioId)
                    <span class="text-danger">*</span>
                @endif
            </label>
            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                   id="imagen" wire:model="imagen" accept="image/*">
            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</div>
            @error('imagen') 
                <div class="invalid-feedback">{{ $message }}</div> 
            @enderror
        </div>

        <!-- Vista previa de imagen -->
        @if($imagen)
            <div class="mb-3">
                <label class="form-label">Vista previa:</label>
                <div class="border rounded p-2 d-inline-block">
                    <img src="{{ $imagen->temporaryUrl() }}" 
                         class="img-thumbnail" 
                         style="max-height: 150px; max-width: 200px;">
                </div>
            </div>
        @elseif($imagen_actual)
            <div class="mb-3">
                <label class="form-label">Imagen actual:</label>
                <div class="border rounded p-2 d-inline-block">
                    <img src="{{ asset('storage/' . $imagen_actual) }}" 
                         class="img-thumbnail" 
                         style="max-height: 150px; max-width: 200px;">
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary" wire:click="$emitUp('hideForm')">
                <i class='bx bx-x'></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-save'></i> 
                {{ $servicioId ? 'Actualizar' : 'Guardar' }}
            </button>
        </div>
    </form>
</div>
