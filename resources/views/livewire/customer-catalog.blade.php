<div class="min-h-screen bg-gray-50">
    <!-- Header con búsqueda -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Catálogo de Productos</h1>
                    <span class="text-sm text-gray-500">{{ $products->total() }} productos</span>
                    <livewire:floating-icons />
                </div>

                <!-- Búsqueda -->
                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Buscar productos..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Controles -->
                <div class="flex items-center space-x-4">
                    <button 
                        wire:click="toggleFilters"
                        class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                        </svg>
                        Filtros
                    </button>

                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button 
                            wire:click="toggleViewMode"
                            class="p-2 hover:bg-gray-100 transition-colors {{ $viewMode === 'grid' ? 'bg-gray-100' : '' }}"
                            title="Vista de cuadrícula"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button 
                            wire:click="toggleViewMode"
                            class="p-2 hover:bg-gray-100 transition-colors {{ $viewMode === 'list' ? 'bg-gray-100' : '' }}"
                            title="Vista de lista"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar de filtros -->
            @if($showFilters)
                <div class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
                            <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800">Limpiar</button>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                            <select wire:model.live="selectedCategory" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de productos -->
            <div class="flex-1">
                @if($products->count())
                    @if($viewMode === 'grid')
                        <!-- Vista Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                        @if($product->main_image_url)
                                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover cursor-pointer hover:scale-105 transition-transform" wire:click="showProductDetail({{ $product->id }})">
                                        @else
                                            <div class="w-full h-64 flex items-center justify-center bg-gray-100">
                                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->category }}</p>
                                        <div class="mt-3 flex items-center justify-between">
                                            <div>
                                                <p class="text-lg font-bold text-blue-600">${{ number_format($product->base_price, 2) }}</p>
                                                @if($product->allows_customization)
                                                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Personalizable</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-4 flex space-x-2">
                                            <button wire:click="showProductDetail({{ $product->id }})" class="flex-1 text-sm bg-gray-100 hover:bg-gray-200 py-2 rounded-md transition">
                                                Ver detalles
                                            </button>
                                            <button wire:click="addToCart({{ $product->id }})" class="flex-1 text-sm bg-blue-600 text-white hover:bg-blue-700 py-2 rounded-md transition">
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <!-- Vista Lista -->
                        <div class="space-y-4">
                            @foreach($products as $product)
                                <div class="flex items-center p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 mr-6">
                                        @if($product->main_image_url)
                                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform" wire:click="showProductDetail({{ $product->id }})">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->category }} @if($product->brand) • {{ $product->brand }}@endif</p>
                                        @if($product->description)
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-blue-600">${{ number_format($product->base_price, 2) }}</p>
                                        @if($product->allows_customization)
                                            <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">Personalizable</span>
                                        @endif
                                    </div>
                                    <div class="ml-6 flex space-x-3">
                                        <button wire:click="showProductDetail({{ $product->id }})" class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-md transition">
                                            Ver detalles
                                        </button>
                                        <button wire:click="addToCart({{ $product->id }})" class="px-4 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-md transition">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>

                @else
                    <!-- Sin productos -->
                    <div class="text-center py-16">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-6 text-xl font-medium text-gray-900">No se encontraron productos</h3>
                        <p class="mt-2 text-gray-500">Prueba ajustar los filtros o la búsqueda.</p>
                        <button wire:click="clearFilters" class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Limpiar filtros
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal único de producto (al final del archivo) -->
    @if($showProductModal && $selectedProduct)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-2xl rounded-lg bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $selectedProduct->name }}</h3>
                        <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600 text-3xl">×</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Imágenes -->
                        <div>
                            <div class="relative bg-gray-100 rounded-lg overflow-hidden">
                                @if(count($selectedProduct->image_urls))
                                    <img src="{{ $selectedProduct->image_urls[0] }}" alt="{{ $selectedProduct->name }}" class="w-full h-96 object-contain" id="main-product-image">
                                    <img id="customization-overlay" class="hidden absolute inset-0 m-auto max-w-full max-h-full object-contain pointer-events-none opacity-80" />
                                @else
                                    <div class="h-96 flex items-center justify-center text-gray-400">
                                        <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            @if(count($selectedProduct->image_urls) > 1)
                                <div class="grid grid-cols-5 gap-3 mt-4">
                                    @foreach($selectedProduct->image_urls as $index => $url)
                                        <button onclick="document.getElementById('main-product-image').src='{{ $url }}'" class="border-2 {{ $index===0 ? 'border-blue-blue-500' : 'border-transparent' }} rounded overflow-hidden">
                                            <img src="{{ $url }}" class="h-20 w-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Info y personalización -->
                        <div class="space-y-6">
                            <div>
                                <span class="text-sm text-gray-500">Categoría:</span>
                                <p class="text-lg font-medium">{{ $selectedProduct->category }}</p>
                            </div>
                            @if($selectedProduct->brand)
                                <div>
                                    <span class="text-sm text-gray-500">Marca:</span>
                                    <p class="text-lg font-medium">{{ $selectedProduct->brand }}</p>
                                </div>
                            @endif

                            <div class="text-3xl font-bold text-blue-600">
                                ${{ number_format($selectedProduct->base_price, 2) }}
                            </div>

                            @if($selectedProduct->description)
                                <div>
                                    <span class="text-sm text-gray-500">Descripción:</span>
                                    <p class="mt-1 text-gray-700">{{ $selectedProduct->description }}</p>
                                </div>
                            @endif

                            @if($selectedProduct->allows_customization)
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <div class="flex items-center text-purple-800 font-medium">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Producto personalizable
                                    </div>

                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Texto personalizado</label>
                                            <textarea wire:model="customizationText" rows="3" class="w-full mt-1 border rounded-md px-3 py-2" placeholder="Ej: Juan Pérez, Feliz cumpleaños..."></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Color</label>
                                                @if(is_array($selectedProduct->available_colors) && count($selectedProduct->available_colors))
                                                    <select wire:model="customizationColor" class="w-full mt-1 border rounded-md px-3 py-2">
                                                        @foreach($selectedProduct->available_colors as $color)
                                                            <option>{{ $color }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input wire:model="customizationColor" type="text" class="w-full mt-1 border rounded-md px-3 py-2" placeholder="Ej: Rojo">
                                                @endif
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Talla</label>
                                                @if(is_array($selectedProduct->available_sizes) && count($selectedProduct->available_sizes))
                                                    <select wire:model="customizationSize" class="w-full mt-1 border rounded-md px-3 py-2">
                                                        @foreach($selectedProduct->available_sizes as $size)
                                                            <option>{{ $size }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input wire:model="customizationSize" type="text" class="w-full mt-1 border rounded-md px-3 py-2" placeholder="Ej: M, L, XL">
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Imagen de referencia</label>
                                            <input id="customization-file-input" type="file" accept="image/*" wire:model="customizationFile" class="w-full mt-1">
                                            @error('customizationFile') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                            @if($customizationFile)
                                                <img src="{{ $customizationFile->temporaryUrl() }}" class="mt-2 h-32 rounded border">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="flex space-x-4 pt-4">
                                <button wire:click="addSelectedToCart" class="flex-1 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                    Agregar al carrito
                                </button>
                                <button wire:click="closeProductModal" onclick="clearCustomizationOverlay()" class="px-8 py-3 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function clearCustomizationOverlay() {
        const overlay = document.getElementById('customization-overlay');
        if (overlay) {
            overlay.style.display = 'none';
            overlay.src = '';
        }
        const input = document.getElementById('customization-file-input');
        if (input) input.value = null;
        if (window._customizationObjectUrl) {
            URL.revokeObjectURL(window._customizationObjectUrl);
            window._customizationObjectUrl = null;
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target.id === 'customization-file-input' && e.target.files[0]) {
            if (window._customizationObjectUrl) URL.revokeObjectURL(window._customizationObjectUrl);
            const url = URL.createObjectURL(e.target.files[0]);
            window._customizationObjectUrl = url;
            const overlay = document.getElementById('customization-overlay');
            if (overlay) {
                overlay.src = url;
                overlay.style.display = 'block';
            }
        }
    });

    document.addEventListener('livewire:load', () => {
        Livewire.hook('message.processed', () => {
            const input = document.getElementById('customization-file-input');
            const overlay = document.getElementById('customization-overlay');
            if (input?.files?.[0] && overlay) {
                if (window._customizationObjectUrl) URL.revokeObjectURL(window._customizationObjectUrl);
                window._customizationObjectUrl = URL.createObjectURL(input.files[0]);
                overlay.src = window._customizationObjectUrl;
                overlay.style.display = 'block';
            }
        });
    });
</script>
@endpush