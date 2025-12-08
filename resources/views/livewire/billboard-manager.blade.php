<div class="p-6 bg-white rounded-lg shadow" x-data="{ showForm: false }">
    <!-- SECTION 1: Upload Images -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4">Paso 1: Subir imágenes</h3>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona imágenes</label>
            <input type="file" wire:model="images" multiple class="mt-2 block w-full" accept="image/*">
            <div wire:loading wire:target="images" class="text-blue-600 mt-2">Preparando archivo...</div>
            @error('images.*') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button wire:click="upload" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            <span wire:loading.remove wire:target="upload">Subir imágenes</span>
            <span wire:loading wire:target="upload">Procesando...</span>
        </button>
    </div>

    <!-- SECTION 2: Display Uploaded Images -->
    @if(count($uploaded) > 0)
        <div class="mt-6 border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Paso 2: Selecciona imagen para producto</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($uploaded as $img)
                    @php
                        $imgPath = str_replace('\\', '/', $img);
                        if (str_starts_with($imgPath, 'http') || str_starts_with($imgPath, '//')) {
                            $imgUrl = $imgPath;
                        } elseif (file_exists(public_path('storage/' . ltrim($imgPath, '/')))) {
                            $imgUrl = '/storage/' . ltrim($imgPath, '/');
                        } elseif (file_exists(storage_path('app/public/' . ltrim($imgPath, '/')))) {
                            $imgUrl = '/product-image/' . basename($imgPath);
                        } else {
                            $imgUrl = asset('images/placeholder.jpg');
                        }
                    @endphp
                    <div class="border-2 rounded p-2 hover:border-green-500 cursor-pointer transition" wire:click="selectImage('{{ $img }}')">
                        <img src="{{ $imgUrl }}" alt="uploaded" class="w-full h-32 object-cover rounded mb-2 border">
                        <div class="text-xs text-gray-600 break-words mb-2">{{ basename($img) }}</div>
                        <button type="button" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 w-full">
                            Usar
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- SECTION 3: Product Form -->
    @if($selectedImage)
        <div class="mt-8 border-t pt-6 bg-gray-50 p-6 rounded">
            <h3 class="text-lg font-semibold mb-4">Paso 3: Crear producto</h3>
            
            <!-- Image Preview -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Previsualización de imagen</h4>
                @php
                    $val = $selectedImage;
                    $val = str_replace('\\', '/', $val);
                    if (empty($val)) {
                        $previewUrl = asset('images/placeholder.jpg');
                    } elseif (str_starts_with($val, 'http') || str_starts_with($val, '//')) {
                        $previewUrl = $val;
                    } else {
                        $candidate = ltrim($val, '/');
                        if (str_starts_with($candidate, 'storage/')) {
                            $publicPath = public_path($candidate);
                            if (file_exists($publicPath)) {
                                $previewUrl = '/' . $candidate;
                            } else {
                                $filename = basename($candidate);
                                if ($filename && file_exists(storage_path('app/public/products/' . $filename))) {
                                    $previewUrl = '/product-image/' . $filename;
                                } else {
                                    $previewUrl = asset('images/placeholder.jpg');
                                }
                            }
                        } else {
                            if (file_exists(public_path('storage/' . $candidate))) {
                                $previewUrl = '/storage/' . $candidate;
                            } elseif (file_exists(storage_path('app/public/' . $candidate))) {
                                $previewUrl = '/product-image/' . basename($candidate);
                            } elseif (file_exists(storage_path('app/public/products/' . basename($candidate)))) {
                                $previewUrl = '/product-image/' . basename($candidate);
                            } else {
                                $previewUrl = asset('images/placeholder.jpg');
                            }
                        }
                    }
                @endphp
                <img src="{{ $previewUrl }}" alt="preview" class="h-48 w-auto rounded border border-gray-300">
            </div>

            <!-- Product Form Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Row 1: Name and Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Nombre del producto
                    </label>
                    <input wire:model.defer="name" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="Ej: Polo Empresarial">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="text-red-500">*</span> Precio (COP)
                    </label>
                    <input wire:model.defer="base_price" type="number" step="0.01" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="25000">
                    @error('base_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Row 2: Category and Brand -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <input wire:model.defer="category" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="Ej: Ropa">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <input wire:model.defer="brand" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="Ej: Nike">
                </div>

                <!-- Row 3: Material and Production Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                    <input wire:model.defer="material" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="Ej: Algodón 100%">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Días de producción</label>
                    <input wire:model.defer="production_days" type="number" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="5">
                </div>

                <!-- Row 4: Description (Full Width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea wire:model.defer="description" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" rows="3" placeholder="Escribe la descripción del producto..."></textarea>
                </div>

                <!-- Row 5: Sizes and Colors (Full Width) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tamaños disponibles (separados por coma)</label>
                    <input wire:model.defer="available_sizes" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="XS,S,M,L,XL,XXL">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Colores disponibles (separados por coma)</label>
                    <input wire:model.defer="available_colors" type="text" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded" placeholder="Rojo,Azul,Negro,Blanco">
                </div>

                <!-- Row 6: Checkboxes (Full Width) -->
                <div class="md:col-span-2 space-y-2 border-t pt-4">
                    <div class="flex items-center">
                        <input type="checkbox" wire:model.defer="allows_customization" id="custom" class="h-4 w-4">
                        <label for="custom" class="ml-2 text-sm font-medium text-gray-700">Permitir personalización</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model.defer="is_active" id="active" class="h-4 w-4" checked>
                        <label for="active" class="ml-2 text-sm font-medium text-gray-700">Activo</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model.defer="featured" id="featured" class="h-4 w-4">
                        <label for="featured" class="ml-2 text-sm font-medium text-gray-700">Destacado en catálogo</label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-2 border-t pt-4">
                <button wire:click="createProductFromSelected" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                    <span wire:loading.remove wire:target="createProductFromSelected">✓ Crear producto</span>
                    <span wire:loading wire:target="createProductFromSelected">Creando...</span>
                </button>
                <button wire:click="$set('selectedImage', null)" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    ✕ Cancelar
                </button>
            </div>
        </div>
    @endif
</div>
