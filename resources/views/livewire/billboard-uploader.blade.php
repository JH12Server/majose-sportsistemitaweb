<div>
    <div class="p-6 bg-white rounded-lg shadow">
    

<!-- PRODUCTS LIST -->
<div class="mt-6 p-6 bg-white rounded-lg shadow">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="m-0">Productos publicados</h4>
        <div>
            <button type="button" wire:click="showCreate" class="btn btn-sm btn-primary">
                Nuevo Producto
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:80px">Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th style="width:120px">Precio</th>
                    <th style="width:100px">Activo</th>
                    <th style="width:180px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr wire:key="product-{{ $product->id }}">
                        <td>
                            @php
                                $thumb = $product->main_image_url ?? '';
                                // normalize path/backslashes
                                $thumb = str_replace('\\', '/', $thumb);

                                // If it's an absolute URL, use it
                                if (!empty($thumb) && (str_starts_with($thumb, 'http') || str_starts_with($thumb, '//'))) {
                                    $thumbUrl = $thumb;
                                } else {
                                    // If starts with /storage, check file exists in public/storage
                                    $candidate = ltrim($thumb, '/');
                                    $publicPath = public_path($candidate);
                                    if (!empty($candidate) && file_exists($publicPath)) {
                                        $thumbUrl = '/' . $candidate;
                                    } else {
                                        // try storage/app/public/products/<basename>
                                        $filename = basename($candidate ?: $product->main_image ?: '');
                                        $storagePath = storage_path('app/public/products/' . $filename);
                                        if ($filename && file_exists($storagePath)) {
                                            $thumbUrl = '/product-image/' . $filename;
                                        } else {
                                            // fallback: prefer public/storage, then storage/app/public, else placeholder
                                            if (!empty($candidate) && file_exists(public_path('storage/' . $candidate))) {
                                                $thumbUrl = '/storage/' . $candidate;
                                            } elseif (!empty($candidate) && file_exists(storage_path('app/public/' . $candidate))) {
                                                $thumbUrl = '/product-image/' . basename($candidate);
                                            } elseif (!empty($candidate) && file_exists(public_path($candidate))) {
                                                $thumbUrl = '/' . $candidate;
                                            } else {
                                                $thumbUrl = asset('images/placeholder.jpg');
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <img src="{{ $thumbUrl }}" alt="thumb" class="img-fluid rounded" style="max-width:72px; max-height:72px; object-fit:cover">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>{{ $product->getFormattedPriceAttribute() }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="badge badge-success">Sí</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" wire:click="editProduct({{ $product->id }})" class="btn btn-sm btn-outline-primary">Editar</button>
                                <button type="button" wire:click="confirmDelete({{ $product->id }})" class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted">No hay productos publicados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <small class="text-muted">Mostrando {{ $products->count() }} de {{ $products->total() }} productos</small>
        </div>
        <div>
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- CREATE MODAL (Bootstrap-style, controlled by Livewire boolean) -->
@if($showCreateModal)
    <div class="modal-backdrop fade show"></div>
    <div class="modal d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4)">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Producto</h5>
                    <button type="button" class="close btn" aria-label="Close" wire:click="$set('showCreateModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="createProduct">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Imagen Principal</label>
                                <input type="file" wire:model="main_image_file" accept="image/*" class="form-control">
                                @if($main_image_file)
                                    <img src="{{ $main_image_file->temporaryUrl() }}" class="img-fluid mt-2 rounded">
                                @endif
                                @error('main_image_file') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" wire:model.defer="name" class="form-control">
                                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-2 row">
                                    <div class="col">
                                        <label class="form-label">Precio *</label>
                                        <input type="number" step="0.01" wire:model.defer="base_price" class="form-control">
                                        @error('base_price') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Categoría</label>
                                        <input type="text" wire:model.defer="category" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Marca</label>
                                    <input type="text" wire:model.defer="brand" class="form-control">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Descripción</label>
                                    <textarea wire:model.defer="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="mb-2 row">
                                    <div class="col">
                                        <label class="form-label">Tallas (coma separadas)</label>
                                        <input type="text" wire:model.defer="available_sizes" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Colores (coma separadas)</label>
                                        <input type="text" wire:model.defer="available_colors" class="form-control">
                                    </div>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="allows_customization" id="create_custom">
                                    <label class="form-check-label" for="create_custom">Permitir personalización</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="is_active" id="create_active">
                                    <label class="form-check-label" for="create_active">Activo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="featured" id="create_featured" checked>
                                    <label class="form-check-label" for="create_featured">Destacado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showCreateModal', false)">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- EDIT MODAL -->
@if($showEditModal)
    <div class="modal-backdrop fade show"></div>
    <div class="modal d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4)">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="close btn" aria-label="Close" wire:click="$set('showEditModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="updateProduct">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Imagen Principal</label>
                                <input type="file" wire:model="edit_main_image_file" accept="image/*" class="form-control">
                                @if($edit_main_image_file)
                                    <img src="{{ $edit_main_image_file->temporaryUrl() }}" class="img-fluid mt-2 rounded">
                                @elseif($selectedImage)
                                    @php
                                        $val = $selectedImage;
                                        $val = str_replace('\\', '/', $val);
                                        if (empty($val)) {
                                            $editUrl = asset('images/placeholder.jpg');
                                        } elseif (str_starts_with($val, 'http') || str_starts_with($val, '//')) {
                                            $editUrl = $val;
                                        } else {
                                            $candidate = ltrim($val, '/');
                                            // if it's already a public/storage path
                                            if (str_starts_with($candidate, 'storage/')) {
                                                $publicPath = public_path($candidate);
                                                if (file_exists($publicPath)) {
                                                    $editUrl = '/' . $candidate;
                                                } else {
                                                    $filename = basename($candidate);
                                                    if ($filename && file_exists(storage_path('app/public/products/' . $filename))) {
                                                        $editUrl = '/product-image/' . $filename;
                                                    } else {
                                                        $editUrl = asset('images/placeholder.jpg');
                                                    }
                                                }
                                            } else {
                                                // candidate like products/xxx or just filename
                                                if (file_exists(public_path('storage/' . $candidate))) {
                                                    $editUrl = '/storage/' . $candidate;
                                                } elseif (file_exists(storage_path('app/public/' . $candidate))) {
                                                    $editUrl = '/product-image/' . basename($candidate);
                                                } elseif (file_exists(storage_path('app/public/products/' . basename($candidate)))) {
                                                    $editUrl = '/product-image/' . basename($candidate);
                                                } else {
                                                    $editUrl = asset('images/placeholder.jpg');
                                                }
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $editUrl }}" class="img-fluid mt-2 rounded">
                                @endif
                                @error('edit_main_image_file') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-8">
                                <div class="mb-2">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" wire:model.defer="name" class="form-control">
                                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-2 row">
                                    <div class="col">
                                        <label class="form-label">Precio *</label>
                                        <input type="number" step="0.01" wire:model.defer="base_price" class="form-control">
                                        @error('base_price') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Categoría</label>
                                        <input type="text" wire:model.defer="category" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Marca</label>
                                    <input type="text" wire:model.defer="brand" class="form-control">
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Descripción</label>
                                    <textarea wire:model.defer="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="mb-2 row">
                                    <div class="col">
                                        <label class="form-label">Tallas (coma separadas)</label>
                                        <input type="text" wire:model.defer="available_sizes" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Colores (coma separadas)</label>
                                        <input type="text" wire:model.defer="available_colors" class="form-control">
                                    </div>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="allows_customization" id="edit_custom">
                                    <label class="form-check-label" for="edit_custom">Permitir personalización</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="is_active" id="edit_active">
                                    <label class="form-check-label" for="edit_active">Activo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" wire:model.defer="featured" id="edit_featured" checked>
                                    <label class="form-check-label" for="edit_featured">Destacado</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showEditModal', false)">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- DELETE CONFIRM -->
@if($showDeleteModal)
    <div class="modal-backdrop fade show"></div>
    <div class="modal d-block" tabindex="-1" role="dialog" style="background:rgba(0,0,0,0.4)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Producto</h5>
                    <button type="button" class="close btn" aria-label="Close" wire:click="$set('showDeleteModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click.prevent="deleteProduct">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
