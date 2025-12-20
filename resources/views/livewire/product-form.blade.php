<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           wire:model.defer="name" required placeholder="Ej: Camiseta personalizada">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                           wire:model.defer="category" placeholder="Ej: Ropa, Accesorios">
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      wire:model.defer="description" rows="3"></textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Precio</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control @error('base_price') is-invalid @enderror" 
                               wire:model.defer="base_price" placeholder="0.00">
                    </div>
                    @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" wire:model.defer="is_active">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen del Producto</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" wire:model="image" accept="image/*">
            <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</div>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        @if($image)
            <div class="mb-3">
                <label class="form-label">Vista previa:</label>
                <div class="border rounded p-2 d-inline-block">
                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="max-height:150px;">
                </div>
            </div>
        @elseif($image_actual)
            <div class="mb-3">
                <label class="form-label">Imagen actual:</label>
                <div class="border rounded p-2 d-inline-block">
                    <img src="{{ $image_actual }}" class="img-thumbnail" style="max-height:150px;">
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-secondary" wire:click="$emitUp('hideForm')">
                <i class='bx bx-x'></i> Cancelar
            </button>
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-save'></i>
                {{ $productId ? 'Actualizar' : 'Guardar' }}
            </button>
        </div>
    </form>
</div>
